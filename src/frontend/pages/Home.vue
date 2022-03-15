<template>
  <div id="vue-frontend-app">
    <loading :active.sync="isLoading" 
        :can-cancel="true" 
        :is-full-page="fullPage"></loading>
    <router-link to="/">Home</router-link>
    <router-link to="/profile">Profile</router-link>
    <GroupList :items="groups" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Header from '@/frontend/components/Header.vue'
import GroupList from '@/frontend/components/GroupList.vue'
import Course from '@/frontend/components/GroupLeader.vue'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: 'App',
    components: {
      Header,
      GroupList,
      Loading,
      Course
    },
    data() {
        return {
            leaders: [],
            isLoading: false,
            fullPage: true
        }
    },
    mounted() {
    this.getGroups()
		this.getGroupLeaders()
    this.getCourses()
	},
  methods: {
    async getGroups() {
      try {
        this.isLoading = true
        const res = await this.$axios.get('v2/groups')
        this.$store.dispatch('getGroups', res.data)
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message)
      } finally {
        this.isLoading = false
      }
    },
    async getGroupLeaders() {
      try {
        this.isLoading = true
        const res = await this.$axios.get('v2/groups/1104/users')
        this.leaders = res.data
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message)
      } finally {
        this.isLoading = false
      }
    },
    async getCourses() {
      try {
        this.isLoading = true
        const res = await this.$axios.get('v2/sfwd-courses')
        this.$store.dispatch('getCourses', res.data)
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message)
      } finally {
        this.isLoading = false
      }
    }
  },
  computed: {
    ...mapGetters({
      groups: 'groups'
    })
  }
}
</script>

<style>

</style>
