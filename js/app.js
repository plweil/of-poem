import * as Vue from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
import FeaturedPoem from './components/FeaturedPoem.js';

const { createApp } = Vue;
const app = createApp({});
app.component('FeaturedPoem', FeaturedPoem);
app.mount('#app');
