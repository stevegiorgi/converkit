<template>
  <v-app>
    <div id="vue-frontend-app">
      <loading
        :active.sync="isLoading"
        color="#fff"
        background-color="#000"
        :can-cancel="true"
        :is-full-page="true"
      ></loading>
      <Header :items="groups" />
      <Filters :items="groups" />
      <GroupList :items="groups" />
      <Snackbar />
    </div>
  </v-app>
</template>

<script>
import Snackbar from "./components/Snackbar.vue";
import { mapGetters } from "vuex";
import { Bus } from "@/frontend/mixins/bus";
import Header from "@/frontend/components/Header.vue";
import Filters from "@/frontend/components/Filters.vue";
import GroupList from "@/frontend/components/GroupList.vue";
import Course from "@/frontend/components/GroupLeader.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "App",
  components: {
    Header,
    Filters,
    GroupList,
    Loading,
    Course,
    Snackbar
  },
  data() {
    return {
      leaders: [],
      isLoading: false,
      fullPage: true
    };
  },
  mounted() {
    this.getGroups();
    this.getCourses();
    // setTimeout(() => { this.$store.dispatch('getStartupData'); }, 7500);
  },
  methods: {
    async getGroups() {
      try {
        this.isLoading = true;
        // const res = await this.$axios.get("ldd/v2/groups")
        const res = await this.$axios.get("ldd/v2/groups", {
          headers: { "X-WP-Nonce": wpApiSettings.nonce }
        });
        this.$store.dispatch("getGroups", res.data);
      } catch (e) {
        // this.error(e.response ? e.response.data.message : e.message);
        console.log("axios error", e);
      } finally {
        this.isLoading = false;
      }
    },

    // getuser(){
    // 	return axios.get(wpApiSettings.root+'wp/v2/users/me?_wpnonce='+wpApiSettings.nonce,{headers: { 'X-WP-Nonce': wpApiSettings.nonce }})
    // 	// return axios.get(wpApiSettings.root+'wp-json/wp/v2/users/me?_wpnonce='+wpApiSettings.nonce,{headers: { 'X-WP-Nonce': wpApiSettings.nonce }})
    // },
    async getCourses() {
      try {
        this.isLoading = true;
        const res = await this.$axios.get("ldlms/v2/sfwd-courses");
        this.$store.dispatch("getCourses", res.data);
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    }
    // async getLessons() {
    //   try {
    //     this.isLoading = true;
    //     const res = await this.$axios.get("ldlms/v2/groups");
    //     this.$store.dispatch("getLessons", res.data);
    //   } catch (e) {
    //     this.error(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // },
    // async getQuizzes() {
    //   try {
    //     this.isLoading = true;
    //     const res = await this.$axios.get("ldlms/v1/sfwd-quiz");
    //     this.$store.dispatch("getQuizzes", res.data);
    //     console.log("Quiz Data:", res.data);
    //   } catch (e) {
    //     this.error(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // },
    // async getGroupLeaders() {
    //   try {
    //     this.isLoading = true;
    //     const res = await this.$axios.get("ldlms/v2/groups/1104/users");
    //     this.leaders = res.data;
    //   } catch (e) {
    //     this.error(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // },
  },
  computed: {
    ...mapGetters({
      groups: "groups"
    })
  }
};
</script>

<style lang="scss">
$red: #cb080f;
$black: #000;
$white: #fff;
$grey: #707070;
</style>
