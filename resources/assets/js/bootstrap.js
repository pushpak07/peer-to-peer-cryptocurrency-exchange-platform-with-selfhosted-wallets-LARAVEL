window._ = require('lodash/core');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {
    console.error("Unable to load Jquery!")
}

try {
    window.bootstrap = require('bootstrap');
} catch (e) {
    console.error("Unable to load Bootstrap!")
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.$.ajaxSetup({
        headers: {'X-CSRF-TOKEN': token.content}
    });

    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found!');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');
window.io = require('socket.io-client');

const broadcaster = window.Laravel.broadcaster;

if(broadcaster === "pusher"){
    const pusher = window.Laravel.pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        cluster: pusher.cluster,
        key: pusher.key,
        encrypted: true
    });
}else if(broadcaster === "redis"){
    const host = window.location.hostname;

    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: host + ':6001'
    });
}

/**
 * This section includes other NPM dependecies which are require by the
 * application's frontend. Removing any one of these may cause some glitch
 * to the frontend.
 */

// DataTables
require('datatables.net-bs4');
require('datatables.net-responsive-bs4');

// Bootstrap Fileselect
require('bootstrap-fileselect/src/bootstrap-fileselect');

// jQuery Form
window.jqueryForm = require('jquery-form');

// Chart
window.Chart = require('chart.js');

// Swal
window.swal = require('sweetalert');

// Jquery Match Height
window.matchHeight = require("jquery-match-height");

// raty
window.raty = require('jquery-raty-js');

// Select2
window.select2 = require('select2');

// JqueryValidate
window.validate = require('jquery-validation');

// jQuery Idle
window.idle = require('jquery.idle');

// intl-tel-input
window.intlTelInput = require('intl-tel-input');

// Moment
window.moment = require('moment');

// jQuery Knob
window.knob = require('jquery-knob');

// jQuery Slim Scroll
window.slimscroll = require('jquery-slimscroll');

// Toastr
window.toastr = require('toastr');

// jQuery Sticky
window.sticky = require('jquery-sticky');

// Ladda
window.Ladda = require('ladda');

// Screenfull
window.screenfull = require('screenfull');


/**
 * This section includes important Javascript Libraries which are made
 * available locally. These are also required by the application's
 * frontend. Removing any one of these may cause some glitch to the frontend.
 */

// Unison Js
require('./plugins/unison.min');

// Jquery BlockUI Js
require('./plugins/jquery-blockui.min');

// JQuery Bootstrap Validation
require("./plugins/jqBootstrapValidation");

// Perfect Scrollbar Js
require('./plugins/perfect-scrollbar.min');

// Sliding Menu
require('./plugins/sliding-menu.min');

// Actual
require('./plugins/jquery.actual.min');
