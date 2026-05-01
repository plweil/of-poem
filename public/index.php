<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('DATA_PATH', BASE_PATH . '/data');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/app/helpers.php';

use App\Bootstrap;

(new Bootstrap())->run();
