import Vue from "vue";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.css";
import 'material-design-icons-iconfont/dist/material-design-icons.css'

Vue.use(Vuetify);

const options = {
    defaultAssets: {
        font: true,
        icons: 'mdi'
    },
    icons: {
        iconfont: 'mdi',
    },
    theme: {
        dark: false,
        themes: {
            dark: {
                primary: "#000",
                secondary: "#fff",
                background: "#000",
                accent: "#cb080f",
                error: "#cb080f"
            },
            light: {
                primary: "#000",
                secondary: "#000",
                background: "#fff",
                accent: "#cb080f",
                error: "#cb080f"
            }
        }
    }
};

export default new Vuetify(options);