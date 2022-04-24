"use strict";

import Vue from "vue";
import axios from "axios";

const username = "steve@dotekimedia.com";
const password = "fxGW yIqW 4GSt 30Oj sQo5 Wtjt";
const token = Buffer.from(`${username}:${password}`, "utf8").toString("base64");

axios.defaults.headers.common["Authorization"] = `Basic ${token}`;
const _axios = axios.create({
    baseURL: "https://samesidesellingacademy.com/wp-json/",
    // baseURL: "http://localhost:10004/wp-json/"
    // baseURL: "https://sandbox.local/wp-json/",
});

_axios.interceptors.request.use(
    function(config) {
        // Do something before request is sent
        return config;
    },
    function(error) {
        // Do something with request error
        return Promise.reject(error);
    }
);

// Add a response interceptor
_axios.interceptors.response.use(
    function(response) {
        // Do something with response data
        return response;
    },
    function(error) {
        // Do something with response error
        return Promise.reject(error);
    }
);

Plugin.install = function(Vue) {
    Vue.axios = _axios;
    window.axios = _axios;
    Object.defineProperties(Vue.prototype, {
        axios: {
            get() {
                return _axios;
            },
        },
        $axios: {
            get() {
                return _axios;
            },
        },
    });
};

Vue.use(Plugin);

export default Plugin;