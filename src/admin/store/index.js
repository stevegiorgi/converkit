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
        tags: []
    },
    mutations: {
        updateField,
        SET_SUBSCRIBERS(state, payload) {
            state.subscribers = payload;
        },
        SET_SUBSCRIBER_TAGS(state, payload) {
            state.tags = payload;
        }
        // async SET_SUBSCRIBER_TAGS(state, payload) {
        //     state.tags = await payload;
        // }
    },
    actions: {
        async getSubscribers({ commit }) {
            Vue.axios
                .get("ldd/v2/ck-users?get_all=true")
                .then(response => {
                    console.log("Subscribers", response.data);
                    commit("SET_SUBSCRIBERS", response.data);
                })
                .catch(error => {
                    this.error(e.request ? e.request.statusText : e.request);
                });

            // const apiSecret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";
            // Vue.axios
            //     .get(
            //         `https://api.convertkit.com/v3/subscribers?api_secret=${apiSecret}&from=2018-01-01&to=2022-02-25`
            //     )
            //     .then(response => {
            //         commit("SET_SUBSCRIBERS", response.data);
            //     })
            //     .catch(error => {
            //         this.error(e.request ? e.request.statusText : e.request);
            //     });
        },

        // async getSubscriberTags({ commit }, { id }) {
        //     const apiKey = "8xjZlAPwIpU62U7SQjjS-Q";
        //     Vue.axios
        //         .get(
        //             `https://api.convertkit.com/v3/subscribers/${id}/tags?api_key=${apiKey}`
        //         )
        //         .then(response => {
        //             commit("SET_SUBSCRIBER_TAGS", response.data);
        //         })
        //         .catch(error => {
        //             this.error(e.request ? e.request.statusText : e.request);
        //         });
        // }

        async getSubscriberTags({ commit }, { id: id }) {
            // Vue.axios
            //     .get("/ldd/v2/ck-tags")
            //     .then(response => {
            //         console.log("Tags", response.data);
            //         commit("SET_SUBSCRIBER_TAGS", response.data);
            //     })
            //     .catch(e => {
            //         this.error(e.request ? e.request.statusText : e.request);
            //     });

            const tags = window.localStorage.getItem(`subscriberTags-${id}`);
            // window.localStorage.removeItem(`subscriberTags-${id}`);
            if (tags) {
                return tags;
            } else {
                const apiKey = "8xjZlAPwIpU62U7SQjjS-Q";
                try {
                    this.isLoading = true;
                    const response = await axios.get(
                        `https://api.convertkit.com/v3/subscribers/${id}/tags?api_key=${apiKey}`
                    );
                    commit("SET_SUBSCRIBER_TAGS", response.data);

                    // window.localStorage.setItem(`subscriberTags-${id}`, response.data);
                } catch (error) {
                    console.log(error);
                } finally {
                    this.isLoading = false;
                }
            }
        }

        // async getSubscribers({ commit }) {
        //     try {
        //         this.isLoading = true;
        //         const apiKey = "8xjZlAPwIpU62U7SQjjS-Q";
        //         const apiSecret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";
        //         const response = axios.get(
        //             `https://api.convertkit.com/v3/subscribers?api_secret=${apiSecret}&from=2018-01-01&to=2021-10-10`
        //         );
        //         commit("SET_SUBSCRIBERS", response);
        //     } catch (e) {
        //         this.error(e.request ? e.request.statusText : e.request);
        //     } finally {
        //         this.isLoading = false;
        //     }
        // }

        // async getMultipleGroupUsers({ commit }) {
        //     this.state.filters.selectedGroup.forEach(group => {
        //         Vue.axios
        //             .get("ldd/v2/group-users/" + group.id)
        //             .then(response => {
        //                 commit("SET_MULTIPLE_GROUP_USERS", response.data);
        //             })
        //             .catch(error => {
        //                 throw new Error(`API ${error}`);
        //             });
        //     });
        // }
    },
    getters: {
        getField,
        getSubscribers: state => state.subscribers,
        getSubscriberTags: state => state.tags
    }
});