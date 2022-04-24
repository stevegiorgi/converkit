// import Vue from "vue";
// import App from "./App.vue";
// import router from "./router";
// import menuFix from "./utils/admin-menu-fix";
// import vuetify from "./plugins/vuetify";

// Vue.config.productionTip = false;

// /* eslint-disable no-new */
// new Vue({
//     el: "#vue-admin-app",
//     router,
//     vuetify,
//     render: h => h(App)
// });

// // fix the admin menu for the slug "vue-app"
// menuFix("vue-app");

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import "./plugins/axios";
import store from "./store";
import menuFix from "./utils/admin-menu-fix";
import vuetify from "./plugins/vuetify";

export const bus = new Vue();

// import VueApexCharts from "vue-apexcharts";
// import VCalendar from './plugins/v-calendar'
// import './sass/main.scss'
// import "./mixins/mixins";
// import "./mixins/filters/filters";

// Vue.use(VueApexCharts);
// Vue.component("apexchart", VueApexCharts);

Vue.config.productionTip = false;
// Bootstrapping CSS

/* eslint-disable no-new */
new Vue({
    el: "#vue-admin-app",
    vuetify,
    router,
    store,
    render: h => h(App)
});

// fix the admin menu for the slug "vue-app"
menuFix("vue-app");