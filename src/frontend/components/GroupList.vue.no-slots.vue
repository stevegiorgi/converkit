<template>
  <v-container fluid class="header__container">

    <loading
      :active.sync="isLoading"
      color="#fff"
      background-color="#000"
      :can-cancel="true"
      :is-full-page="true"
    ></loading>

    <v-data-table
      :headers="headers"
      :items="groupUsers"
      :item-class="dataRow"
      :items-per-page="10"
      :expanded.sync="expanded"
      :single-expand="singleExpand"
      :sort-by.sync="sortByGroup"
      :sort-desc.sync="sortDescGroup"
      @click:row="(user, row) => selectUser(user, row)"
    >
      <!-- <template v-slot:item="{ item, isExpanded, expand }"> -->
        <!-- <tr
          class="data__row"
          :key="item.id"
          :class="item.id === selectedRow ? 'custom-highlight-row' : ''"
          @click="selectUser(item.id), expand(!isExpanded), rowSelect(item.id)"
        > -->
      <template>
        <tr class="data__row">
          <td class="data__column avatar">
            <v-avatar color="primary">
              <img :src="`${avatar}`">
            </v-avatar>
          </td>
          <td class="data__column name">
            <span class="user__name">{{ display_name }}</span>
          </td>
          <td class="data__column email">{{ email_address }}</td>
          <td class="data__column latest-activity">
            {{ latest_activity }}
          </td>
          <td class="data__column lessons-completed">
            {{ lessons_completed }}
          </td>
          <td class="data__column last-login">
            {{ last_login }}
          </td>
        </tr>
      </template>

      <template v-slot:expanded-item="{ headers }">
          <td
            class="data__column user-details__container p-0"
            :colspan="headers.length"
          >
            <div class="user-details__inner-container restrict-height">
              <v-container fluid class="user-details__panel">
                <v-row>
                  <v-col sm="12" md="8">
                    <v-data-table
                      :headers="courseHeaders"
                      :items="userDetails.courses"                      
                      :hide-default-footer="true"
                      class="user-details__table"
                      :sort-by.sync="sortByCourse"
                      :sort-desc.sync="sortDescCourse"
                    >

                      <template v-slot:item="{ item }">


                      <!-- <template v-slot:item="{ item, courseHeaders, isMobile }"> -->
                        <!-- <template v-for="course in userCourses"> -->
                          <!-- <template v-if="item.course === course.id"> -->

                            <!-- <tr v-if="!isMobile">
                              <td v-for="(header,i) in headers" :key="`${header.value}-${i}`">
                                {{item[header.value]}}
                              </td>
                            </tr>
                            <tr v-else class="v-data-table__mobile-table-row">
                              <td
                                v-for="(header,i) in headers"
                                :key="`${header.value}-${i}`"
                                class="v-data-table__mobile-row"
                              >
                                <div class="v-data-table__mobile-row__header">{{header.text}}</div>
                              </td>
                            </tr> -->




                            <tr>
                              <td class="data_column d-block d-sm-table-cell" v-html="item.course_title"></td>
                              <td>{{ item.course_status | status }}</td>
                              <td class="data_column d-block d-sm-table-cell">
                                <v-chip
                                  :color="getColor(completionPercentage(item.course_progress.completed, item.course_progress.total))"
                                  dark
                                >
                                  {{ completionPercentage(item.course_progress.completed, item.course_progress.total) + "%" }}
                                </v-chip>
                              </td>
                              <td class="data_column d-block d-sm-table-cell" v-if="item.date_started">
                                {{ item.date_started | moment("calendar") }}
                              </td>
                              <td class="data_column d-block d-sm-table-cell" v-else>––</td>
                              <td class="data_column d-block d-sm-table-cell" v-if="item.date_completed">
                                {{ item.date_completed | moment("calendar") }}
                              </td>
                              <td class="data_column d-block d-sm-table-cell" v-else>––</td>
                            </tr>




                            
                          <!-- </template> -->
                        <!-- </template> -->

                        <!-- <Lessons :courseId="item.id" /> -->

                      </template>
                    </v-data-table>
                  </v-col>
                  <v-col sm="12" md="4" class="progress-chart__container">
                    <h4 class="progress-chart__title">Overall Progress</h4>
                    <vue-apex-charts
                      type="radialBar"
                      :options="chartOptions"
                      :series="series"
                    >
                    </vue-apex-charts>
                  </v-col>
                </v-row>
              </v-container>

              <v-container fluid class="user-details__panel">
                <v-row>
                  <v-col cols="12">

                    <!-- Start Quizzes -->

                    <v-data-table
                      :headers="quizHeaders"
                      :items="userDetails.quizzes"
                      item-key="id"
                      :hide-default-footer="true"
                      class="user-details__table"
                    >
                      <template v-slot:item="{ item }">
                        <!-- <template v-for="course in userCourses"> -->
                          <!-- <template v-if="item.course === course.id"> -->
                            <tr>
                              <td class="data_column" v-html="item.quiz_title"></td>
                              <td class="data_column">{{ item.pass | passed }}</td>
                              <td>
                                <v-chip :color="getColor(item.quiz_score)" dark>
                                  {{ item.quiz_score + "%" }}
                                </v-chip>
                              </td>
                              <td class="data_column">{{ item.timespent }}</td>
                              <td class="data_column" v-if="item.data">
                                {{ item.data | moment("calendar") }}
                              </td>
                              <td class="data_column" v-else>{{ item.date }}</td>
                            </tr>
                          <!-- </template> -->
                        <!-- </template> -->
                      </template>
                    </v-data-table>

                    <!-- End Quizzes -->

                  </v-col>
                </v-row>
              </v-container>
            </div>
          </td>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
import moment from "moment";
import { mapGetters } from "vuex";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Bus } from "@/frontend/mixins/bus.js";
import VueApexCharts from "vue-apexcharts";
// import vueCustomScrollbar from 'vue-custom-scrollbar'
// import UserDetails from "@/frontend/components/UserDetails.vue";
// import Lessons from "@/frontend/components/Lessons.vue";

export default {
  name: "GroupList",
  components: {
    Loading,
    moment,
    VueApexCharts
    // UserDetails,
    // Lessons,
  },
  data: () => ({
    series: [54, 72, 89],
    chartOptions: {
      chart: {
        foreColor: "fff",
        type: "radialBar",
        height: 300,
        offsetX: 0
      },
      colors: ["#ff9800", "#4caf50", "#fff"],
      plotOptions: {
        radialBar: {
          inverseOrder: false,
          hollow: {
            margin: 5,
            size: "48%",
            background: "transparent"
          },
          dataLabels: {
            showOn: "always",
            name: {
              offsetY: -15,
              fontSize: "18px",
              color: "#fff"
            },
            value: {
              offsetY: -0,
              fontSize: "30px",
              fontWeight: 700,
              show: true,
              color: "#fff"
            }
          },
          track: {
            show: true,
            background: "#fff",
            strokeWidth: "98%",
            opacity: 0.3,
            margin: 3 // margin is in pixels
          }
        }
      },
      series: [71, 63],
      labels: ["Courses", "Quizzes", "Lessons"],
      stroke: {
        lineCap: "round"
      },
      legend: {
        show: true,
        position: "right",
        offsetX: -20,
        offsetY: 0,
        formatter: function(val, opts) {
          return val + " - " + opts.w.globals.series[opts.seriesIndex] + "%";
        }
      }
      // fill: {
      //   type: "gradient",
      //   gradient: {
      //     shade: "dark",
      //     type: "radial",
      //     shadeIntensity: 0.1,
      //     inverseColors: false,
      //     opacityFrom: 1,
      //     opacityTo: 1,
      //     stops: [0, 100]
      //   }
      // }
    },
    expand: [],
    expanded: [],
    isExpanded: null,
    singleExpand: true,
    isLoading: false,
    fullPage: true,
    groupId: null,
    userId: null,
    selectedRow: null,
    headers: [
      { value: "avatar" },
      { text: "Name", value: "display_name" },
      { text: "Email Address", value: "email_address" },
      { text: "Latest Activity", value: "latest_activity" },
      { text: "Lessons Completed", value: "lessons_completed" },
      { text: "Last Login", value: "last_login" }
    ],
    courseHeaders: [
      { text: "Course", value: "course_title" },
      { text: "Status", value: "course_status" },
      { text: "Progress", value: "course_progress" },
      { text: "Date Started", value: "date_started" },
      { text: "Date Completed", value: "date_completed" }
    ],
    quizHeaders: [
      { text: "Course / Quiz", value: "quiz_title" },
      { text: "Passed", value: "pass" },
      { text: "Score", value: "score" },
      { text: "Duration (minutes)", value: "timespent" },
      { text: "Date Completed", value: "date_completed" }
    ],
    sortByGroup: "name",
    sortDescGroup: false,
    sortByCourse: "title.rendered",
    sortDescCourse: false
  }),
  filters: {
    getAvatar: function(url) {
      return url;
    },
    status: function(value) {
      if (value == "not-started") {
        return "Not Started";
      } else if (value == "in-progress") {
        return "In Progress";
      } else if (value == "completed") {
        return "Completed";
      } else {
        return value;
      }
    },
    passed: function(value) {
      if (value == 1) {
        return "Passed";
      } else {
        return "Not Passed";
      }
    },
    fancyTimeFormat: function(duration) {
      let hrs = ~~(duration / 3600);
      let mins = ~~((duration % 3600) / 60);
      let secs = ~~duration % 60;
      let ret = "";

      if (hrs > 0) {
        ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
      }

      ret += "" + mins + ":" + (secs < 10 ? "0" : "");
      ret += "" + secs;
      return ret;
    }
  },
  computed: {
    randomData: function() {
      return Math.floor(Math.random() * 40 + 40);
    },
    ...mapGetters({
      groupUsers: "groupUsers"
    }),
    ...mapGetters({
      userCourses: "userCourses"
    }),
    ...mapGetters({
      userCourseProgress: "userCourseProgress"
    }),
    ...mapGetters({
      userQuizProgress: "userQuizProgress"
    }),
	  ...mapGetters({
	    userDetails: "userDetails"
	  }),

    // ...mapGetters(["userCoursesById"]),
    // userCourses() {
    //   console.log(
    //     "userCourses:",
    //     this.$store.getters["userCoursesById"](this.userId)
    //   );
    //   return this.$store.getters["userCoursesById"](this.userId);
    //   // return this.userCoursesById(this.userId).userCourses
    // },
    // userCourseLessons: function() {
    //     return this.$store.getters["courseLessons"];
    // },
  },
  methods: {
    // loadUserDetails({ item }) {
    //   this.userId = item.id;
    //   // Bus.$emit("loadUserDetails", item.id);
    //   this.$refs.userDetails.getUserCoursesById(this.userId);
    // },
    getColor(percentage) {
      if (percentage <= 30) return "#555";
      else if (percentage <= 75) return "orange";
      else return "green";
    },
    completionPercentage(completed, total) {
      return ((completed / total) * 100).toFixed(2);
    },
    
    async getGroupUsers(groupId) {
      try {
        this.isLoading = true;
        // const res = await this.$axios.get('v2/groups/' + this.$route.params.id + '/users')
        const res = await this.$axios.get("ldd/v2/group-users/" + groupId);
        console.log('Group Users', res.data);
        this.$store.dispatch("getGroupUsers", res.data);
        // console.log(res.data)
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    },



    // randomNumber: function() {
    //   return Math.floor(Math.random() * 30 + 10);
    // },
    // randomDate: function randomDate(start, end, startHour, endHour) {
    //   var date = new Date(+start + Math.random() * (end - start));
    //   var hour = (startHour + Math.random() * (endHour - startHour)) | 0;
    //   date.setHours(hour);
    //   return date.toDateString();
    // },
    async getUserCoursesById(userId) {
      try {
        this.isLoading = true; /// doesn't load here when triggering the vue-data-table opening
        const res = await this.$axios.get("ldlms/v2/users/" + userId + "/courses");
        this.$store.dispatch("getUserCourses", res.data);
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    },
    async getUserCourseProgress(userId) {
      try {
        this.userProgress = [];
        this.isLoading = true;
        const res = await this.$axios.get("ldlms/v2/users/" + userId + "/course-progress");
        this.$store.dispatch("getUserCourseProgress", res.data);
        console.log("Course Progress:", res.data);
        // this.userProgress = res.data;
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    },
    async getUserQuizProgress(userId) {
      try {
        this.userProgress = [];
        this.isLoading = true;
        const res = await this.$axios.get("ldlms/v2/users/" + userId + "/quiz-progress");
        this.$store.dispatch("getUserQuizProgress", res.data);
        console.log("Quiz Progress:", res.data);
        // this.userProgress = res.data;
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    },

    async getUserDetails(userId) {
      try {
        // this.userDetails = [];
        this.isLoading = true;
        const res = await this.$axios.get("ldd/v2/users/" + userId);
        this.$store.dispatch("getUserDetails", res.data);
        console.log("User Details:", res.data);
        // this.userProgress = res.data;
      } catch (e) {
        this.error(e.response ? e.response.data.message : e.message);
      } finally {
        this.isLoading = false;
      }
    },

    // async getUserCourseLessons(courseId) {
    //   try {
    //     // this.userCourseLessons = [];
    //     this.isLoading = true;
    //     const res = await this.$axios.get("v2/sfwd-lessons/", {
    //       params: {
    //         course: courseId
    //       }
    //     });
    //     // this.userCourseLessons = res.data
    //     this.$store.dispatch("getUserCourseLessons", res.data);
    //   } catch (e) {
    //     console.log(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // },
    // async viewCourseByUser(id) {
    //     try {
    //         this.userCourses = []
    //         this.isLoading = true
    //         const res = await this.$axios.get('v2/sfwd-courses/' + id + '/users')
    //         this.userCourses = res.data
    //     } catch (e) {
    //         this.error(e.response ? e.response.data.message : e.message)
    //     } finally {
    //         this.isLoading = false
    //     }
    // },
    // async getUserCourses(id) {
    //     try {
    //         this.userCourses = []
    //         this.isLoading = true
    //         const res = await this.$axios.get('v2/users/' + id + '/courses')
    //         this.userCourses = res.data

    //         this.courseLessons = []
    //         for(const [index, course] of this.userCourses.entries()) {
    //           // this.getUserCourseLessons(course.id)
    //           this.isLoading = true
    //           let res = await this.$axios.get('v2/sfwd-lessons/', {
    //             params: {
    //               course: course.id
    //             }
    //           })
    //           this.courseLessons = res.data

    //           // this.$store.dispatch('getUserCourseLessons', res.data)
    //         }
    //     } catch (e) {
    //         this.error(e.response ? e.response.data.message : e.message)
    //     } finally {
    //         this.isLoading = false
    //     }
    // },
    // async getUserCourseLessons(id) {
    //   try {
    //     this.courseLessons = [];
    //     this.isLoading = true;
    // const res = await this.$axios.get("v2/sfwd-lessons/", {
    //   params: {
    //     course: id
    //   }
    // });
    //     this.$store.dispatch("getUserCourseLessons", res.data);
    //   } catch (e) {
    //     this.error(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // },
    // async getUserProgress(userId) {
    //   try {
    //     this.userProgress = [];
    //     this.isLoading = true;
    //     const res = await this.$axios.get(
    //       "v2/users/" + userId + "/course-progress"
    //     );
    //     this.userProgress = res.data;
    //   } catch (e) {
    //     this.error(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // },
    // goBack() {
    //   this.$router.go(-1);
    // }
    selectUser: function(user, row) {

      this.userId = user.id;
      // Bus.$emit("selectUser", userId);
      this.getUserCoursesById(user.id);
      this.getUserCourseProgress(user.id);
      this.getUserQuizProgress(user.id);
      this.getUserDetails(user.id);

      this.rowSelect(row)

    },
    dataRow: function() {
      return 'data__row'
    },
    rowSelect(row) {
      row.expand(!this.isExpanded);
      this.selectedRow = row.index;
      // rowexpand(!row.isExpanded).;
    },    
    // prepareData(updatedCourses) {
    //   for (const course of updatedCourses.entries()) {
    //     console.log(course)
    //     this.getUserCourseLessons(course.id);
    //   }
    // }
  },
  created() {
    Bus.$on("selectGroup", selectedGroup => {
      this.groupId = selectedGroup;
      this.getGroupUsers(selectedGroup);
    });
  },
  watch: {
    // userCourses: function(updatedCourses) {
    //   console.log('userCourses Changed:', updatedCourses)
    //   this.prepareData(updatedCourses);
    // }
  }
};
</script>

<style lang="scss">
$red: #cb080f;
$black: #000;
$white: #fff;
$grey: #707070;

@import '~vuetify/src/styles/settings/_variables.scss';

body {
  background-color: $black;
}

thead {
  th {
    background-color: $black;
    vertical-align: middle;

    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase !important;
  }
}

.data__row {
  font-size: 16px;
  font-weight: 700;
}

.data {
  &__row {
    height: 100px;
    background-color: $black;

    @media #{map-get($display-breakpoints, 'sm-and-down')} {
      height: initial;
    }

    &:nth-of-type(odd) {
      border-bottom: 1px solid $grey;
      background: #000
        linear-gradient(0deg, rgb(0, 0, 0, 1) 0%, rgb(112, 112, 112, 0.3) 100%);
    }
  }

  &__column {
    vertical-align: middle;
  }
}

.user-details {
  &__wrapper {
    background-color: $red !important;
  }

  &__table {
    background-color: $red !important;
  }

  &__container {
    background-color: $red !important;
    border-radius: 0;
    padding-top: 20px !important;
    padding-bottom: 20px !important;

    thead {
      th {
        position: sticky !important;
        top: 0 !important;
        background-color: $red !important;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase !important;
        z-index: 2;
      }
    }
  }
}

.progress-chart {
  &__container {
    font-weight: 700;
    color: #000;
    background: #cb080f;
    border-radius: 10px;
  }

  &__title {
    font-size: 20px;
    font-weight: 600;
    text-align: left;
    color: #fff;
    padding-left: 20px;
  }
}

.custom-highlight-row {
  background: none !important;
  background-color: $red !important;
  border-bottom: none !important;
}

.theme--dark.v-application {
  background: #000 !important;
  color: #fff !important;
}

.theme--dark.v-data-table
  > .v-data-table__wrapper
  > table
  > tbody
  > tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
  background: #777;
  transition: background 0.5s ease-in-out;
  cursor: pointer;
}

.v-data-table__wrapper {
  overflow: unset !important;
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

::-webkit-scrollbar {
  width: 15px;
}

::-webkit-scrollbar-track {
  background: #202020;
  border-left: 1px solid #2c2c2c;
}

::-webkit-scrollbar-thumb {
  background: #3e3e3e;
  border-radius: 7px;
}

::-webkit-scrollbar-thumb:hover {
  background: white;
}

.user-details__inner-container::-webkit-scrollbar {
  width: 15px !important;
  margin-left: 20px;
}

.user-details__inner-container::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.3);
  border-left: none !important;
  border-radius: 10px !important;
}

.user-details__inner-container::-webkit-scrollbar-thumb {
  background: #fff !important;
  border: solid 3px #fff !important;
  border-radius: 7px !important;
}

.user-details__inner-container::-webkit-scrollbar-thumb:hover {
  background: white !important;
}

.user-details__container {
  .theme--dark.v-data-table
    > .v-data-table__wrapper
    > table
    > tbody
    > tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
    background: $red !important;
    transition: background 0.5s ease-in-out !important;
    cursor: pointer;
  }
}

.restrict-height {
  height: 500px;
  max-height: 100vh;
  overflow: auto;
}

.apexcharts-legend-text {
  color: #fff;
  font-weight: 700;
}

.apexcharts-xaxistooltip {
  color: #fff;
}

.v-data-table__expanded__row {
  background: $red !important;
}


// @media screen and (max-width: 768px) {
//   .mobile table.v-table tr {
//     max-width: 100%;
//     position: relative;
//     display: block;
//   }

//   .mobile table.v-table tr:nth-child(odd) {
//     border-left: 6px solid deeppink;
//   }

//   .mobile table.v-table tr:nth-child(even) {
//     border-left: 6px solid cyan;
//   }

//   .mobile table.v-table tr td {
//     display: flex;
//     width: 100%;
//     border-bottom: 1px solid #f5f5f5;
//     height: auto;
//     padding: 10px;
//   }

//   .mobile table.v-table tr td ul li:before {
//     content: attr(data-label);
//     padding-right: .5em;
//     text-align: left;
//     display: block;
//     color: #999;

//   }

//   .v-datatable__actions__select
//   {
//     width: 50%;
//     margin: 0px;
//     justify-content: flex-start;
//   }

//   .mobile .theme--light.v-table tbody tr:hover:not(.v-datatable__expand-row) {
//     background: transparent;
//   }
// }
</style>
