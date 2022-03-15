<template>
  <v-container>
    <loading :active.sync="isLoading"
        color="#fff"
        background-color="#000"
        :can-cancel="true"
        :is-full-page="true"></loading>
	  <v-data-table
        :headers="lessonHeaders"
        :items="userCourseLessons"
        item-key="lessons"
        :hide-default-footer="true"
      >
        <template v-slot:item="{ item }">
          <tr v-if="item.course === courseId">
            <td v-html="item.title.rendered"></td>
          </tr>

            <!-- <tr v-for="course in userCourses" :key="course.id">
          <td v-if="course.id == item[index].course">Course: {{ course.title.rendered }}</td>
        </tr>
        <tr v-for="lesson in item" :key="lesson.id">
          <td v-html="lesson.title.rendered"></td>
        </tr> -->

            <!-- <tr>
          <td class="data__column name">
            <span class="course__label">{{ lessons }}
              Course: <span class="course__title" v-html="lesson.title.rendered"></span>
            </span>
          </td>
          <td>{{ randomDate(new Date(2021, 0, 1), new Date(), 0, 24) }}</td>
          <td>{{ item.id }}</td>
          <td></td>
        </tr> -->
        </template>
    </v-data-table>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Bus } from "@/frontend/mixins/bus.js";

export default {
  name: "Lessons",
  components: {
    Loading,
  },
  props: {
    courseId: null,
  },
    data: () => ({
    expand: [],
    expanded: [],
    isExpanded: [],
    singleExpand: true,
    isLoading: false,
    fullPage: true,
    lessonHeaders: [
      { text: "Lesson", value: "Lesson" },
      { text: "Completion", value: "Completion" },
    //   { text: "Quiz Scores", value: "Quiz Scores" },
      { text: "Duration", value: "Duration" }
    ]
  }),
  computed: {
    ...mapGetters({
      userCourseLessons: "userCourseLessons"
    })
  },
}
</script>

<style lang="scss" scoped>
$red: #cb080f;
$black: #000;
$white: #fff;
$grey: #707070;

.theme--dark.v-data-table {
  background-color: $red;
}
.theme--dark.v-data-table > .v-data-table__wrapper > table > tbody > tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
  background: unset;
}
</style>