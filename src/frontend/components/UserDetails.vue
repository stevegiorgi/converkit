<template>
  <v-container fluid class="user-details__panel">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
    ></loading>
    <v-row class="course__select" justify="start" align="center">
      <v-col cols="4">
        <v-select
          :items="userCourses.courses"
          item-text="title.rendered"
          item-value="courses"
          label="Filter User Courses"
          v-model="defaultCourse"
          outlined
          @change="selectUserCourse()"
        ></v-select>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-data-table
          :headers="courseHeaders"
          :items="userCourses.courses"
          item-key="courses"
          :hide-default-footer="true"
          height="400"
          fixed-header
          class="user-details__container"
        >
          <template v-slot:item="{ item }">
            <tr>
              <td :key="item.id" v-html="item.title.rendered"></td>
            </tr>
            <Lessons :courseId="item.id" />
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Bus } from "@/frontend/mixins/bus.js";
// import VCalendar from "./VCalendar.vue";
import Lessons from "@/frontend/components/Lessons.vue";

export default {
  name: "GroupList",
  components: {
    Loading,
    Lessons
  },
  data: () => ({
    expand: [],
    expanded: [],
    isExpanded: [],
    singleExpand: true,
    isLoading: false,
    fullPage: true,
    defaultCourse: null,
    // userCourses: [],
    // userCourseLessons: [],
    userId: null,
    userProgress: [],
    groupId: null,
    courseHeaders: [
      { text: "Course", value: "Course" }
      // { text: "Completion", value: "Completion" },
      // { text: "Quiz Scores", value: "Quiz Scores" },
      // { text: "Duration", value: "Duration" }
    ]
  }),
  computed: {
    // ...mapGetters({
    //   userCourses: "userCourses",
    // }),
    ...mapGetters(["userCoursesById"]),
    userCourses() {
      return this.$store.getters["userCoursesById"](this.userId);
      // return this.userCoursesById(this.userId).userCourses
    },
    ...mapGetters({
      userCourseLessons: "userCourseLessons"
    })
  },
  methods: {
    // getUserCoursesById(userId) {
    //   console.log('userCourses - User ' + userId + ':', this.$store.getters.userCoursesById(userId));
    //   return this.$store.getters.userCoursesById(userId).userCourses
    // },
    randomDate: function randomDate(start, end, startHour, endHour) {
      var date = new Date(+start + Math.random() * (end - start));
      var hour = (startHour + Math.random() * (endHour - startHour)) | 0;
      date.setHours(hour);
      return date.toDateString();
    },
    // async getGroupUsers(groupId) {
    //   try {
    //     this.isLoading = true;
    //     // const res = await this.$axios.get('v2/groups/' + this.$route.params.id + '/users')
    //     const res = await this.$axios.get("v2/groups/" + groupId + "/users");
    //     this.$store.dispatch("getGroupUsers", res.data);
    //     // console.log(res.data)
    //   } catch (e) {
    //     this.error(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // },
    async getUserCoursesById(userId) {
      try {
        this.isLoading = true; /// doesn't load here when triggering the vue-data-table opening
        const res = await this.$axios.get("v2/users/" + userId + "/courses");
        this.$store.dispatch("getUserCourses", {
          id: userId,
          courses: res.data
        });
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    },
    async getUserCourseLessons(courseId) {
      try {
        // this.userCourseLessons = [];
        this.isLoading = true;
        const res = await this.$axios.get("v2/sfwd-lessons/", {
          params: {
            course: courseId
          }
        });
        // this.userCourseLessons = res.data
        this.$store.dispatch("getUserCourseLessons", res.data);
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    },
    async getUserProgress(userId) {
      try {
        this.userProgress = [];
        this.isLoading = true;
        const res = await this.$axios.get(
          "v2/users/" + userId + "/course-progress"
        );
        this.userProgress = res.data;
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    }
    // prepareData() {
    //   for (const course of this.userCourses.courses.entries()) {
    //     console.log(course)
    //     this.getUserCourseLessons(course.id);
    //   }
    // }
  },
  mounted() {
    Bus.$on("loadUserDetails", userId => {
      alert("success");
      // this doesn't fire on first click!!!!!
      this.userId = userId;
      this.getUserCoursesById(userId);
    });
  },
  watch: {
    // userCourses: function(updatedProperty) {
    //   // if (userCourses) {
    //     console.log('userCourses Changed:', updatedProperty)
    //     this.prepareData();
    //   // }
    // }
    // userCourseLessons: function(userCourseLessons) {
    //   if (userCourseLessons) {
    //     console.log(userCourseLessons);
    //   }
    // }
  }
};
</script>

<style lang="scss">
$red: #cb080f;
$black: #000;
$white: #fff;
$grey: #707070;

.user-details {
  &__container {
    background-color: $red !important;
    border-radius: 0 !important;

    thead th {
      background-color: unset !important;
    }
  }
}

.v-data-table--fixed-header > .v-data-table__wrapper > table > thead > tr > th {
  background-color: $red !important;
}

.v-text-field.v-text-field--solo .v-input__control {
  min-height: 10px;
}

.v-input__slot {
  margin-bottom: 0;
}
</style>
