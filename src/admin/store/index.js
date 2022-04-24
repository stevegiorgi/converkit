import Vue from "vue";
import Vuex from "vuex";
import { getField, updateField } from "vuex-map-fields";
import moment from "moment";

// import uniqBy from "lodash/uniqBy";
import _ from "lodash";

let today = new Date();
let dd = String(today.getDate()).padStart(2, "0");
let mm = String(today.getMonth() + 1).padStart(2, "0");
let yyyy = today.getFullYear();

today = yyyy + "-" + mm + "-" + dd;

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        subscribers: [],
        subscribers_tags: [],
        tags: []
    },
    mutations: {
        updateField,
        SET_SUBSCRIBERS(state, payload) {
            state.subscribers = payload;
        },
        SET_SUBSCRIBERS_TAGS(state, payload) {
            state.subscribers_tags = payload;
        },
        SET_TAGS(state, payload) {
            state.tags = payload;
        }
    },
    actions: {
        async getSubscribers({ commit }) {
            const apiKey = "8xjZlAPwIpU62U7SQjjS-Q";
            const subscribers = await axios.get("ldd/v2/ck-users?get_all=true");

            console.log('All Subscriber Data', subscribers);

            // let tags = {};
            // for (const subscriber of subscribers.data) {
            //     await axios
            //         .get(
            //             `https://api.convertkit.com/v3/subscribers/${subscriber.ck_id}/tags?api_key=${apiKey}`
            //         )
            //         .then(({ data }) => (tags[subscriber.ck_id] = data.tags));
            // }

            // We are adding tag data to each subscriber at our custom endpoint due to API limitations
            // for (const subscriber of subscribers.data) {
            //     const response = await axios.get(
            //         `https://api.convertkit.com/v3/subscribers/${subscriber.ck_id}/tags?api_key=${apiKey}`
            //     );
            //     tags[subscriber.ck_id] = response.data.tags;
            // }

            commit("SET_SUBSCRIBERS", subscribers.data);
            // commit("SET_SUBSCRIBERS_TAGS", tags);
        },

        async getTags({ commit }) {
            const apiKey = "8xjZlAPwIpU62U7SQjjS-Q";
            const response = await axios.get(
                `https://api.convertkit.com/v3/tags?api_key=${apiKey}`
            );
            commit("SET_TAGS", response.data.tags);
        }
    },

    getters: {
        getField,
        getSubscribers: state => state.subscribers,
        getSubscribersTags: state => state.subscribers_tags,
        getTags: state => state.tags
    }
});