<?php

namespace App\Core;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Exception;

class Container
{
    protected array $bindings = [];
    protected array $instances = [];
    protected array $parameters = [];

    /**
     * Bind a class or interface to a factory or concrete class
     */
    public function set(string $id, callable|string $concrete, bool $singleton = true): void
    {
        $this->bindings[$id] = [
            'concrete' => $concrete,
            'singleton' => $singleton,
        ];
    }

    /**
     * Define scalar parameters for a class
     */
    public function params(string $class, array $params): void
    {
        $this->parameters[$class] = $params;
    }

    /**
     * Get an instance
     */
    public function get(string $id)
    {
        // cached singleton
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        // binding exists
        if (isset($this->bindings[$id])) {
            $binding = $this->bindings[$id];
            $object = $this->build($binding['concrete'], $id);

            if ($binding['singleton']) {
                $this->instances[$id] = $object;
            }

            return $object;
        }

        // auto-resolve
        return $this->instances[$id] = $this->build($id, $id);
    }

    /**
     * Build a class or factory
     */
    protected function build(callable|string $concrete, string $id)
    {
        // closure factory
        if (is_callable($concrete)) {
            return $concrete($this);
        }

        // concrete class name
        $class = $concrete;

        if (!class_exists($class)) {
            throw new Exception("Class {$class} does not exist (while resolving {$id})");
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class;
        }

        $dependencies = array_map(
            fn($param) => $this->resolveParameter($param, $class),
            $constructor->getParameters()
        );

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * Resolve a single constructor parameter
     */
    protected function resolveParameter(ReflectionParameter $param, string $class)
    {
        $type = $param->getType();

        // class dependency
        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            return $this->get($type->getName());
        }

        $name = $param->getName();

        // configured scalar
        if (isset($this->parameters[$class][$name])) {
            return $this->parameters[$class][$name];
        }

        // default value
        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        throw new Exception(
            "Unresolvable parameter \${$name} in {$class}. " .
            "Add it via Container::params()."
        );
    }
}
