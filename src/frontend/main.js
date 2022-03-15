import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import "./plugins/axios";
import store from "./store";
import vuetify from "./plugins/vuetify";
import VueApexCharts from "vue-apexcharts";
// import VCalendar from './plugins/v-calendar'
// import './sass/main.scss'
import "./tailwind.css";
import "./mixins/mixins";
import "./mixins/filters/filters";

Vue.use(VueApexCharts);
Vue.component("apexchart", VueApexCharts);

Vue.config.productionTip = false;
// Bootstrapping CSS

/* eslint-disable no-new */
new Vue({
    el: "#vue-frontend-app",
    vuetify,
    router,
    store,
    render: h => h(App)
});