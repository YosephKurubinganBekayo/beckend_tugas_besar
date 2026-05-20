import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

window.setRealtimeToken = (token) => {
    if (token) {
        localStorage.setItem('access_token', token);
        window.axios.defaults.headers.common.Authorization = `Bearer ${token}`;
    } else {
        localStorage.removeItem('access_token');
        delete window.axios.defaults.headers.common.Authorization;
    }
};

window.listenForAppUpdates = (callback) => window.Echo
    .private('app-updates')
    .listen('.model.changed', callback);
