"use strict";

import Vue from "vue";
import axios from "axios";

// const username = "steve@dotekimedia.com";
// const password = "fxGW yIqW 4GSt 30Oj sQo5 Wtjt";
// const token = Buffer.from(`${username}:${password}`, "utf8").toString("base64");

// const apiSecret = "8xjZlAPwIpU62U7SQjjS-Q";

// // axios.defaults.headers.common["Authorization"] = `Basic ${token}`;
// const _axios = axios.create({
//   baseURL: "https://api.convertkit.com/v3"
// });

// const querystring = require("querystring");
// axios.get(
//   `https://api.convertkit.com/v3/subscribers/?api_secret=${apiSecret}`
// );

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
      }
    },
    $axios: {
      get() {
        return _axios;
      }
    }
  });
};

Vue.use(Plugin);

export default Plugin;
