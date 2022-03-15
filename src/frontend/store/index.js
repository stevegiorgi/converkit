/* eslint-disable */
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
        groups: [],
        courses: [],
        quizzes: [],
        groupUsers: [],
        userId: null,
        userActivity: [],
        userCourses: [],
        userCourseProgress: [],
        userQuizProgress: [],
        userDetails: [],
        multipleGroupUsers: [],
        filteredCourses: [],
        filteredQuizzes: [],
        filters: {
            selectedGroup: [],
            dateRange: ["2018-01-01", today]
        },
        uniqueUsers: [],
        multipleGroupLeaders: [],
        message: ""
    },
    mutations: {
        updateField,
        SET_GROUPS(state, payload) {
            state.groups = payload;
        },
        SET_COURSES(state, payload) {
            state.courses = payload;
        },
        SET_LESSONS(state, payload) {
            state.lessons = payload;
        },
        SET_QUIZZES(state, payload) {
            state.quizzes = payload;
        },

        SET_USER_ID(state, payload) {
            state.userId = payload;
        },

        SET_USER_ACTIVITY(state, payload) {
            state.userActivity = payload;
        },

        SET_GROUP_USERS(state, payload) {
            state.groupUsers = payload;
        },
        SET_USER_COURSE_BY_ID(state, payload) {
            state.users[payload.id] = payload;
        },
        SET_USER_COURSES(state, payload) {
            state.userCourses = payload;
        },
        SET_USER_COURSE_PROGRESS(state, payload) {
            state.userCourseProgress = payload;
        },
        SET_USER_QUIZ_PROGRESS(state, payload) {
            state.userQuizProgress = payload;
        },
        SET_USER_DETAILS(state, payload) {
            state.userDetails = payload;
        },

        SET_MULTIPLE_GROUP_USERS(state, payload) {
            // state.multipleGroupUsers = payload;
            state.multipleGroupUsers.push(payload);
        },
        SET_MESSAGE(state, payload) {
            // state.multipleGroupUsers = payload;
            state.message = payload;
        },

        RESET_MULTIPLE_GROUP_USERS: state => {
            state.multipleGroupUsers = [];
        }
    },
    actions: {
        async getGroups({ commit }, payload) {
            commit("SET_GROUPS", payload);
        },
        async getCourses({ commit }, payload) {
            commit("SET_COURSES", payload);
        },
        async getQuizzes({ commit }, payload) {
            commit("SET_QUIZZES", payload);
        },
        async getGroupUsers({ commit }, payload) {
            commit("SET_GROUP_USERS", payload);
        },
        async getUserCoursesById({ commit }, payload) {
            commit("SET_USER_COURSES_BY_ID", payload);
        },
        async getUserCourses({ commit }, payload) {
            commit("SET_USER_COURSES", payload);
        },
        async getUserCourseProgress({ commit }, payload) {
            commit("SET_USER_COURSE_PROGRESS", payload);
        },
        async getUserQuizProgress({ commit }, payload) {
            commit("SET_USER_QUIZ_PROGRESS", payload);
        },
        async getUserDetails({ commit }, payload) {
            commit("SET_USER_DETAILS", payload);
        },
        // async getStartupData({ commit }) {
        // 	this.state.groups.forEach(group => {
        // 		Vue.axios
        // 			.get("ldd/v2/group-users/" + group.id)
        // 			.then(response => {
        // 				commit("SET_MULTIPLE_GROUP_USERS", response.data);
        // 			})
        // 			.catch(error => {
        // 				throw new Error(`API ${error}`);
        // 			})
        // 	})
        // },
        async getMultipleGroupUsers({ commit }) {
            this.state.filters.selectedGroup.forEach(group => {
                Vue.axios
                    .get("ldd/v2/group-users/" + group.id)
                    .then(response => {
                        commit("SET_MULTIPLE_GROUP_USERS", response.data);
                    })
                    .catch(error => {
                        throw new Error(`API ${error}`);
                    });
            });
        },
        async getUserId({ commit }, payload) {
            commit("SET_USER_ID", payload);
        },
        async getUserActivity({ commit }) {
            Vue.axios
                .get("ldd/v2/activity/" + this.state.userId)
                .then(response => {
                    commit("SET_USER_ACTIVITY", response.data);
                })
                .catch(error => {
                    throw new Error(`API ${error}`);
                });
        },
        resetMultipleGroupUsers: ({ commit }) => {
            commit("RESET_MULTIPLE_GROUP_USERS");
        }
    },
    getters: {
        getField,
        groups: state => state.groups,
        courses: state =>
            state.courses.map(el => {
                const convertHtmlEntities = string => {
                    return (string + "").replace(/&#\d+;/gm, function(s) {
                        return String.fromCharCode(s.match(/\d+/gm)[0]);
                    });
                };

                return convertHtmlEntities(el.title.rendered);

                // return el.title.rendered.replace(/(<([^>]+)>)/gi, "");
            }),
        quizzes: state => state.quizzes,
        groupUsers: state => state.groupUsers,

        userCoursesById: state => id => {
            return state.users[id];
        },
        userCourses: state => state.userCourses,
        userLessons: state => state.userLessons,
        userCourseProgress: state => state.userCourseProgress,
        userQuizProgress: state => state.userQuizProgress,

        userActivity: state => state.userActivity,

        userDetails: state => state.userDetails,

        selectedGroups: state => state.filters.selectedGroup,

        multipleGroupUsers: state => state.multipleGroupUsers,
        filteredCourses: state =>
            (state.userDetails.courses || []).filter(course => {
                if (course !== undefined) {
                    if (
                        moment(course.date_started).isBetween(
                            moment(state.filters.dateRange[0]),
                            moment(state.filters.dateRange[1]),
                            "day",
                            "[]"
                        ) ||
                        moment(course.date_completed).isBetween(
                            moment(state.filters.dateRange[0]),
                            moment(state.filters.dateRange[1]),
                            "day",
                            "[]"
                        )
                    ) {
                        return course;
                    }
                } else {
                    return false;
                }
            }),
        filteredQuizzes: state =>
            (state.userDetails.quizzes || []).filter(quiz => {
                if (quiz !== undefined) {
                    if (
                        moment(quiz.quiz_completed).isBetween(
                            moment(state.filters.dateRange[0]),
                            moment(state.filters.dateRange[1]),
                            "day",
                            "[]"
                        )
                    ) {
                        return quiz;
                    }
                } else {
                    return false;
                }
            }),
        uniqueUsers: state => {
            return _.uniqBy(state.multipleGroupUsers.flat(), u => u.id);
        },
        multipleGroupLeaders: state =>
            _.uniqBy(state.multipleGroupUsers.flat(), u => u.id).filter(user => {
                if (user.group_leader === true) {
                    return user;
                } else {
                    return false;
                }
            })
    }
});