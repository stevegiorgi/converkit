import Vue from "vue";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";

Vue.use(Vuetify);

const options = {
    theme: {
        dark: false,
        themes: {
            dark: {
                primary: "#fff",
                secondary: "#fff",
                background: "#fff",
                accent: "#cb080f",
                error: "#cb080f"
            },
            light: {
                primary: "#fff",
                secondary: "#fff",
                background: "#fff",
                accent: "#cb080f",
                error: "#cb080f"
            }
        }
    }
};

export default new Vuetify(options);