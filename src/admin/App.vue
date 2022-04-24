<template>
  <div data-vuetify>
    <v-app>
      <v-main>
        <div id="vue-admin-app" data-app>
          <v-container fluid>
            <h1>ConvertKit Tag Manager</h1>
            <p>Manage ConvertKit tags for existing subscribers.</p>
            <v-data-table
              :headers="headers"
              :items="filteredSubscribers"
							:items-per-page="50"
							:footer-props="{
								showFirstLastPage: true,
									'items-per-page-text':'Users per page',
									'items-per-page-options' : [10, 25, 50, 100, -1],
							}"
              item-key="ck_id"
              class="elevation-1 pa-6"
              filled
              chips
              multiple
              multi-sort
            >
              <template v-slot:top>
                <v-row class="search-fields">
                  <v-col cols="12" md="3">
                    <v-text-field
                      @change="searchName"
                      append-icon="search"
                      label="Search subscribers"
                      single-line
                      class="mx-4"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field
                      @change="searchEmail"
                      append-icon="search"
                      label="Search email"
                      single-line
                      class="mx-4"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field
                      @change="searchTags"
                      append-icon="search"
                      label="Search tags"
                      single-line
                      class="mx-4"
                    ></v-text-field>
                  </v-col>
									 <v-col cols="12" md="3">
                    <v-text-field
                      @change="searchCourses"
                      append-icon="search"
                      label="Search courses"
                      single-line
                      class="mx-4"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </template>
              <template v-slot:items="subscriber">
                <!-- <tr v-for="subscriber in subscribers" :key="subscriber.ck_id"> -->
                <td>{{ subscriber.ck_id }}</td>
                <td>{{ subscriber.first_name }}</td>
                <td>{{ subscriber.email_address }}</td>
                <td>{{ subscriber.state }}</td>
                <!-- </tr> -->
              </template>
              <template v-slot:item.tags="{ item }">
                <td>
                  <v-row>
                    <v-col cols="12">
                      <!-- is this being used? @change="getSubscriber(subscriber)" -->
											<v-combobox
                        v-model="item.tags"
                        :items="tags"
                        :item-text="(tag) => decodeHtml(tag.name)"
                        :item-value="(tag) => tag.id"
                        @change="addSubscriberTag(item, item.tags)"
                        label="Tags"
                        multiple
                        small-chips
                      >
											<!--
												Pulls tags 
                      <v-combobox
                        v-model="subscribersTags[item.ck_id]"
                        :items="tags"
                        :item-text="(tag) => decodeHtml(tag.name)"
                        :item-value="(tag) => tag.id"
                        @change="addSubscriberTag(item, subscribersTags[item.ck_id])"
                        label="Tags"
                        multiple
                        small-chips
                      >
											-->
                        <template slot="selection" slot-scope="data">
                          <v-chip
                            close
                            :key="data.item.id"
                            :input-value="data.selected"
                            @click:close="removeSubscriberTag(item, data.item)"
                          >
                            {{ getItemText(data.item.id) }}</v-chip
                          >
                        </template>
                      </v-combobox>
                    </v-col>
                  </v-row>
                </td>
              </template>
              <template v-slot:item.courses="{ item }">
                <td v-html="$options.filters.displayAll(item.courses)"></td>
              </template>
            </v-data-table>
            <!-- <router-view /> -->
          </v-container>
        </div>
        <Snackbar />
      </v-main>
    </v-app>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { bus } from "./main";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import Tags from "./components/Tags.vue";
import Snackbar from "./components/Snackbar.vue";

export default {
  data: () => ({
    currentSubscriber: [],
    currentSubscriberTags: [],
    isLoading: false,
    fullPage: true,
    filterQuery: {
      queryName: "",
      queryEmail: "",
      queryTag: "",
			queryCourse: "",
    },
    headers: [
      { text: "ID", value: "ck_id", width: "10%" },
      { text: "Name", value: "first_name", width: "10%" },
      { text: "Email", value: "email_address", width: "10%" },
      { text: "State", value: "state", width: "10%" },
      { text: "Tags", value: "tags", width: "35%" },
      { text: "Courses", value: "courses", width: "25%" },
    ],
		sortBy: 'first_name',
		sortDesc: false,
    buttons: [
      {
        color: "info",
        title: "Infomation",
        type: "info",
      },
      {
        color: "success",
        title: "Success",
        type: "success",
      },
      {
        color: "error",
        title: "Error",
        type: "error",
      },
    ],
    snackbar: {
      color: null,
      icon: null,
      mode: null,
      position: "top",
      text: null,
      timeout: 7500,
      title: null,
      visible: false,
    },
    timeout: 7500,
  }),
  components: {
    Loading,
    Snackbar,
    Tags,
  },
  computed: {
    ...mapGetters({
      subscribers: "getSubscribers",
    }),
    ...mapGetters({
      subscribersTags: "getSubscribersTags",
    }),
    ...mapGetters({
      tags: "getTags",
    }),
    filteredSubscribers() {
      return this.filter(this.subscribers, this.filterQuery);
    },
  },
  filters: {
    displayAll(value) {
      if (value === "No Data") return "No course data available.";
      return value.map((course) => course.course_title).join(", ");
    },
  },
  methods: {
    decodeHtml(input) {
      var e = document.createElement("div");
      e.innerHTML = input;
      return e.childNodes[0].nodeValue;
    },
		search(nameKey, searchArray) {
			for (var i = 0; i < searchArray.length; i++) {
				if(searchArray[i].name === nameKey) {
					return searchArray[i];
				}
			}
		},
    filter(array, query) {
      return array.filter((item) => {
        const first_name = item.first_name.toLowerCase().includes(query.queryName.toLowerCase());
        const email = item.email_address.toLowerCase().includes(query.queryEmail.toLowerCase());
				const tag = query.queryTag !== "" ? item.tags !== null && item.tags.find(t => t.name.toLowerCase().replace(/[^a-zA-Z ]/g, "").includes(query.queryTag.toLowerCase().replace(/[^a-zA-Z ]/g, ""))) : true;
				const course = query.queryCourse !== "" ? item.courses.constructor === Array && item.courses.find(c => c.course_title.toLowerCase().replace(/[^a-zA-Z ]/g, "").includes(query.queryCourse.toLowerCase().replace(/[^a-zA-Z ]/g, ""))) : true;
				if(item.first_name === 'Derek') {
				console.log("Tags", item);
				}
        return (first_name && email && tag && course);
      });
    },
    searchName(value) {
      this.filterQuery.queryName = value;
    },
    searchEmail(value) {
      this.filterQuery.queryEmail = value;
    },
    searchTags(value) {
      this.filterQuery.queryTag = value;
    },
		searchCourses(value) {
			this.filterQuery.queryCourse = value;
		},
    compareObjects(arr1, arr2) {
      return arr1.filter(({ id: id1 }) => !arr2.some(({ id: id2 }) => id2 === id1));
    },
    removeSubscriberTag(subscriber, tag) {
      const subId = subscriber.ck_id;
      const subEmail = subscriber.email_address;
      const tagId = tag.id;
      const tags = this.subscribersTags[subId];
      this.removeSubscriberTagByEmail(subEmail, tagId);
      this.subscribersTags[subId].splice(
        tags.findIndex((tag) => tag.id === tagId),
        1
      );
      bus.$emit("displaySnackbar", {
        type: "success",
        message: `Successfully removed tag: ${tag.name} from ${subscriber.first_name}.`,
      });
    },
    async addSubscriberTag(subscriber, currentlySelectedTags) {
      // const subscriber = previouslySelectedTags.filter((sub) => {
      //   return sub.ck_id === subscriber.ck_id;
      // });

      const subEmail = subscriber.email_address;

      // Compare newly selected tags within existing tags
      const tags = this.compareObjects(currentlySelectedTags, subscriber.tags);

      // If user has existing tags, return the difference
      const newTags = subscriber.tags.length > 0 ? tags : currentlySelectedTags;

      // this.addSubscriberTagByEmail(subEmail, newTags[0].id); // test

      // Have a look at this; we may not need to add multiples
      if (newTags.length <= 1) {
        this.addSubscriberTagByEmail(subEmail, newTags[0].id);

        bus.$emit("displaySnackbar", {
          type: "success",
          message: `Successfully added tag: ${newTags[0].name} to ${subscriber.first_name}.`,
        });
      } else {
        newTags.forEach((tag) => {
          this.addSubscriberTagByEmail(subEmail, tag.id);
        });

        bus.$emit("displaySnackbar", {
          type: "success",
          message: `Successfully added tags: ${newTags.map((tag) => tag.name).join(", ")} to ${
            subscriber.first_name
          }.`,
        });
      }
    },
    displaySnackbar(type, message) {
      bus.$emit("displaySnackbar", { type: type, message: message });
    },
    // getSubscriber(subscriber) {
    //   this.currentSubscriber = subscriber;
    //   console.log("Test", subscriber);
    // },
    // getSubscriberTags(value) {
    //   this.currentSubscriberTags = value;
    //   console.log("Tag Added:", value);
    // },
    getItemText(value) {
      const item = this.tags.find((tag) => tag.id === value);
      return item ? this.decodeHtml(item.name) : "";
    },

    // async removeTagSubscriber(subscriberId, tagId) {
    //   try {
    //     this.isLoading = true;
    //     const apiKey = "8xjZlAPwIpU62U7SQjjS-Q";
    //     const apiSecret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";
    //     const response = await axios.delete(
    //       `https://api.convertkit.com/v3/subscribers/${subscriberId}/tags/${tagId}?api_secret=${apiSecret}`
    //     );
    //     console.log("Response: ", response);
    //   } catch (error) {
    //     console.log("Delete Error: ", error);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // }

    async addSubscriberTagByEmail(subEmail, tagId) {
      try {
        this.isLoading = true;
        const apiSecret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";
        const response = await axios.post(
          `https://api.convertkit.com/v3/tags/${tagId}/subscribe?email=${subEmail}&api_secret=${apiSecret}`,
          {
            header: {
              "Content-Type": "application/json; charset=utf-8",
            },
          },
          {
            data: {
              api_secret: apiSecret,
              email: subEmail,
            },
          }
        );
        console.log("Adding Tag Response:", response);
      } catch (error) {
        console.log("Add Error: ", error);
      } finally {
        this.isLoading = false;
      }
    },

    async removeSubscriberTagByEmail(subEmail, tagId) {
      try {
        // alert(`Removing ${tagId} from user ${subEmail}`);
        this.isLoading = true;
        const apiKey = "8xjZlAPwIpU62U7SQjjS-Q";
        const apiSecret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";
        const response = await axios.post(
          `https://api.convertkit.com/v3/tags/${tagId}/unsubscribe?email=${subEmail}&api_secret=${apiSecret}`
        );
        console.log("Removing tag Response: ", response);
      } catch (error) {
        console.log("Delete Error: ", error);
      } finally {
        this.isLoading = false;
      }
    },

    // async getSubscriberTags(id) {
    //   this.$store.dispatch("getSubscriberTags", { id });
    // }
  },

  // watch: {
  //   currentSubscriberTags: value => {
  //     console.log("Current tags: ", value);
  //   }
  // },
  created() {
    this.$store.dispatch("getSubscribers");
    // this.$store.dispatch("getSubscribersTags");
    this.$store.dispatch("getTags");
  },
};
</script>

<style lang="scss">

@import "~vuetify/src/styles/settings/_variables.scss";

h1 {
	font-size: 30px !important;
	font-weight: 700 !important;
}

p {
	font-size: 18px !important;
}

th span {
	font-size: 18px !important;
	font-weight: 700 !important;
}

input {
    box-shadow: none !important;
    border-radius: unset !important;
    border: none !important;
    background-color: inherit !important;
    color: inherit !important;
}
</style>
