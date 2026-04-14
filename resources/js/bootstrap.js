import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo is loaded on-demand only on pages that need real-time features.
 * Import './echo' in a @push('scripts') block where needed.
 */
