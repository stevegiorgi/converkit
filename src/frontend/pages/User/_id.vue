<template>
    <div>
        <h2>User Groups</h2>
        <button @click="goBack()">Back</button>
        <loading :active.sync="isLoading" 
        :can-cancel="true" 
        :is-full-page="fullPage"></loading>
        <table>
            <tr>
                <td>Name</td>
                <td>Action</td>
            </tr>
            <tr v-for="item in groupUsers" :key="item.id">
                <td> {{ item.name }} </td>
                <td> <button @click="viewCourseByUser(item.id)">View Courses</button></td>
            </tr>
        </table>
        <div v-show="userCourses.length > 0">
            <h3>User Courses</h3>
            <table>
            <tr>
                <td>Avatar</td>
                <td>Name</td>
            </tr>
            <tr v-for="course in userCourses" :key="course.id">
                <td> <img :src="course.avatar_urls['48']" class="img"> </td>
                <td> {{ course.name}} </td>
            </tr>
        </table>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    name: 'UserGroup',
    components: {
        Loading
    },
    data () {
        return {
            groupusers: [],
            leaders: [],
            isLoading: false,
            fullPage: true,
            userCourses: []
        }
    },
    mounted() {
        this.getGroupUsers()
    },
    methods: {
        async getGroupUsers() {
            try {
                this.isLoading = true
                const res = await this.$axios.get('v2/groups/' + this.$route.params.id + '/users')
                this.$store.dispatch('getGroupUsers', res.data)
            } catch (e) {
                this.error(e.response ? e.response.data.message : e.message)
            } finally {
                this.isLoading = false
            }
        },
        async viewCourseByUser(id) {
            try {
                this.userCourses = []
                this.isLoading = true
                const res = await this.$axios.get('v2/sfwd-courses/' + id + '/users')
                this.userCourses = res.data
            } catch (e) {
                this.error(e.response ? e.response.data.message : e.message)
            } finally {
                this.isLoading = false
            }
        },
        goBack() {
            this.$router.go(-1)
        }
    },
    computed: {
        ...mapGetters({
            groupUsers: 'groupUsers'
        })
    }
}
</script>

<style scoped>
    .img {
        width: 100%;
    }
</style>