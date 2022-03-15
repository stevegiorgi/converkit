<template>
  <v-container fluid class="filter__container">
    <v-row class="flex-column flex-md-row group__select">
      <v-col sm="12" md="2">
        <v-autocomplete
          :items="groups"
          v-model="selectedGroup"
          auto-select-first
          item-text="name"
          item-value="id"
          label="Filter by group"
          placeholder="Type to search..."
          multiple
          dense
          filled
          return-object
          @change="selectGroup()"
        >
          <template v-slot:prepend-item>
            <!-- <v-list-item>
              <v-text-field label="Search" @input="searchGroups" />
            </v-list-item> -->
            <v-list-item ripple @click="toggle">
              <v-list-item-action>
                <v-icon :color="selectedGroup.length > 0 ? 'red' : ''">{{
                  icon
                }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Select All</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider class="mt-2"></v-divider>
          </template>

          <template slot="selection" slot-scope="{ item, index }">
            <span class="black--text" v-if="!index">{{ item.name }}</span>
            <span class="grey--text caption" v-else-if="index === 1">
              &nbsp;(+{{ selectedGroup.length - 1 }} others)
            </span>
          </template>
        </v-autocomplete>
      </v-col>
      <v-col sm="12" md="2" class="course__select">
        <v-select
          :items="courses"
          v-model="selectedCourse"
          item-value="id"
          label="Filter by course"
          return-object
          dense
          filled
          auto
          @change="selectCourse()"
        ></v-select>
      </v-col>
      <v-col
        sm="12"
        md="5"
        class="filter__date-range"
        justify="center"
        align="center"
        fill-height
      >
        <!-- <div class="date-range">Selected Date Range: {{ dateRangeText }}</div> -->
        <v-text-field
          v-model="displayText"
          label="Currently displaying"
          readonly
          dense
          filled
        ></v-text-field>
      </v-col>
      <v-col sm="12" md="3" class="filter__date-picker">
        <v-menu
          ref="menu"
          v-model="menu"
          :close-on-content-click="false"
          :return-value.sync="dateRange"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="dateRangeText"
              label="Filter by date range"
              append-icon="mdi-calendar"
              readonly
              v-bind="attrs"
              v-on="on"
              dense
              filled
            >
            </v-text-field>
          </template>

          <template style="display: flex;">
            <div class="calendar__selectors d-flex flex-column justify-center">
              <span class="d-block mb-2">Select Range</span>
              <v-btn
                class="calendar__button"
                @click="$refs.menu.save(getWeek())"
                >Week</v-btn
              >
              <v-btn
                class="calendar__button"
                @click="$refs.menu.save(getMonth())"
                >Month</v-btn
              >
              <v-btn
                class="calendar__button"
                @click="$refs.menu.save(getYear())"
                >Year</v-btn
              >
              <v-btn
                class="calendar__button"
                @click="$refs.menu.save(getLastYear())"
                >Last Year</v-btn
              >
            </div>
            <v-date-picker
              v-model="dateRange"
              min="2001-01-01"
              range
              scrollable
              auto
            >
              <v-spacer></v-spacer>
              <!-- <v-btn text color="primary" @click="menu = false">
                  Cancel
                </v-btn> -->
              <v-btn text color="primary" @click="$refs.menu.save([])">
                Clear
              </v-btn>
              <v-btn text color="primary" @click="$refs.menu.save(dateRange)">
                OK
              </v-btn>
            </v-date-picker>
          </template>
        </v-menu>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";
import { Bus } from "@/frontend/mixins/bus.js";
import App from "../../admin/App.vue";
import { format, parseISO } from "date-fns";
import { mapFields } from "vuex-map-fields";
// import VCalendar from '@/frontend/components/VCalendar.vue';

export default {
  components: {
    App
  },
  props: {
    items: {
      type: Array,
      required: true
    }
  },
  data: () => ({
    format: null,
    show_range: true,
    menu: false,
    selectedCourse: [],
    group: "",
    searchKey: ""
  }),
  computed: {
    ...mapGetters({
      groups: "groups"
    }),
    ...mapGetters({
      courses: "courses"
    }),
    ...mapFields({
      selectedGroup: "filters.selectedGroup"
    }),
    ...mapFields({
      dateRange: "filters.dateRange"
    }),
    dateRangeText() {
      this.dateRange.join(" – ");
    },
    displayText() {
      // let group = this.selectedGroup.name;
      let course = this.selectedCourse;
      let date = this.dateRange.join(" – ");
      // return `${group} – ${course} – ${date}`;
      return `${course} – ${date}`;
    },

    selectAllGroups() {
      return this.selectedGroup.length === this.groups.length;
    },

    selectGroups() {
      return this.selectedGroup.length > 0 && !this.selectAllGroups;
    },

    icon() {
      // if (this.selectAllGroups) return "v-checkbox";
      if (this.selectedGroups) return "v-checkbox";
      return "v-checkbox";
    }
  },
  filters: {
    dateFormat(date) {
      return moment(String(date)).format("DD-MM-YYYY");
    }
  },
  methods: {
    toggle() {
      this.$nextTick(() => {
        if (this.selectAllGroups) {
          // this.$store.dispatch("resetMultipleGroupUsers");
          this.selectedGroup = [];
          this.selectGroup();
        } else {
          // this.$store.dispatch("resetMultipleGroupUsers");
          this.selectedGroup = this.groups.slice();
          this.selectGroup();
        }
      });
    },
    getWeek() {
      let date = new Date();
      let first = date.getDate() - date.getDay();
      let last = first + 6;
      let firstDay = new Date(date.setDate(first)).getDate();
      let lastDay = new Date(date.setDate(last)).getDate();
      let currentMonth = date.getMonth() + 1;
      let currentYear = date.getFullYear();
      return [
        currentYear + "-" + currentMonth + "-" + firstDay,
        currentYear + "-" + currentMonth + "-" + lastDay
      ];
    },
    getMonth() {
      let date = new Date();
      let firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDate();
      let lastDay = new Date(
        date.getFullYear(),
        date.getMonth() + 1,
        0
      ).getDate();
      let currentMonth = date.getMonth() + 1;
      let currentYear = date.getFullYear();
      return [
        currentYear + "-" + currentMonth + "-" + firstDay,
        currentYear + "-" + currentMonth + "-" + lastDay
      ];
    },
    getYear() {
      let date = new Date();
      let currentYear = date.getFullYear();
      return [currentYear + "-01-01", currentYear + "-12-31"];
    },
    getLastYear() {
      let date = new Date();
      let currentYear = date.getFullYear();
      return [currentYear - 1 + "-01-01", currentYear - 1 + "-12-31"];
    },
    selectDateRange() {
      this.$refs.menu.save(this.dateRange);
      this.selectFilters();
    },
    // filterByDate(list, fieldName, fieldValue, format) {
    //   format = format || this.dateFilterFormat;
    //   return list.filter(item => {
    //     if (item[fieldName] !== undefined) {
    //       return moment(item[fieldName]).isSame(
    //         moment(fieldValue, format),
    //         "day"
    //       );
    //     } else {
    //       return true;
    //     }
    //   });
    // },
    // filterByDateGreater(list, fieldName, fieldValue, format) {
    //   format = format || this.dateFilterFormat;
    //   return list.filter(item => {
    //     if (item[fieldName] !== undefined) {
    //       return moment(item[fieldName]).isAfter(
    //         moment(fieldValue, format),
    //         "day"
    //       );
    //     } else {
    //       return true;
    //     }
    //   });
    // },
    // filterByDateLess(list, fieldName, fieldValue, format) {
    //   format = format || this.dateFilterFormat;
    //   return list.filter(item => {
    //     if (item[fieldName] !== undefined) {
    //       return moment(item[fieldName]).isBefore(
    //         moment(fieldValue, format),
    //         "day"
    //       );
    //     } else {
    //       return true;
    //     }
    //   });
    // },
    // filterByDateBetween(list, fieldName, fieldValue1, fieldValue2, format) {
    //   format = format || this.dateFilterFormat;
    //   return list.filter(item => {
    //     if (item[fieldName] !== undefined) {
    //       return moment(item[fieldName]).isBetween(
    //         moment(fieldValue1, format),
    //         moment(fieldValue2, format),
    //         "day",
    //         "[]"
    //       );
    //     } else {
    //       return true;
    //     }
    //   });
    // },
    selectGroup: function() {
      // console.log("selectedgroup", this.selectedGroup);
      // Bus.$emit("selectGroup", this.selectedGroup[0].id);

      this.$store.dispatch("resetMultipleGroupUsers");
      this.$store.dispatch("getMultipleGroupUsers");
    },
    selectCourse: function() {
      Bus.$emit("selectCourse", this.selectedCourse.id);
    },
    formatDate: function(date) {
      format(parseISO(new Date(date).toISOString()), "yyyy-MM-dd");
    }
  },
  mounted() {
    // setTimeout(() => {
    //   Bus.$emit("selectGroup", this.groups[0].id);
    //   this.selectedGroup = this.groups[0];
    // }, 2000);
    setTimeout(() => {
      this.selectedGroup = this.groups.slice();
      this.selectGroup();
    }, 5000);
  }
};
</script>

<style lang="scss" scoped>
$red: #cb080f;
$black: #000;
$white: #fff;
$grey: #707070;

.calendar {
  // &__container {
  //   margin-left: -115px;
  //   border-bottom-left-radius: 4px;
  //   border-top-left-radius: 4px;
  // }

  &__selectors {
    background: #424242;
    padding: 0 1rem 2rem;
    border-radius: 4px;
    color: white;
    font-weight: 500;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  &__button {
    width: 100px;
    margin-bottom: 0.5rem;
  }
}

.v-menu__content {
  overflow-x: visible !important;
  overflow-y: visible !important;
  contain: unset !important;
  display: flex;
}

.v-card {
  border: none;
}

.v-application .accent {
  color: $red;
}

.v-text-field > .v-input__control > .v-input__slot:before,
.v-text-field > .v-input__control > .v-input__slot:after {
  display: none !important;
}

.theme--light {
  .v-btn {
    .v-btn__content {
      color: $black !important;
    }
  }

  .v-simple-checkbox {
    &.v-icon {
      color: $black !important;
    }
  }

  .v-date-picker-table {
    th {
      color: $white !important;
    }
  }

  .v-application .primary {
    background-color: #424242 !important;
  }
}
</style>
