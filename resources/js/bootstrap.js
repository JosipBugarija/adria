window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import "@babel/polyfill";
import "vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css";