// resources/js/bootstrap.js
import axios from 'axios';
window.axios = axios;

const loader = document.getElementById('global-loader');

window.axios.interceptors.request.use((config) => {
    if (loader) loader.classList.remove('hidden');
    return config;
});

window.axios.interceptors.response.use(
    (response) => {
        if (loader) loader.classList.add('hidden');
        return response;
    },
    (error) => {
        if (loader) loader.classList.add('hidden');
        return Promise.reject(error);
    }
);