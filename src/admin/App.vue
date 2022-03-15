<template>
  <div id="vue-admin-app">
    <v-container fluid>
      <h1>ConvertKit Tags</h1>
      <p>Manage ConvertKit tags for existing users.</p>
      <v-data-table
        :headers="headers"
        :items="subscribers"
        item-key="id"
        class="elevation-1"
      >
        <template v-slot:top>
          <!-- <v-text-field
            v-model="search"
            label="Search"
            class="mx-4"
          ></v-text-field> -->
        </template>
        <template v-slot:body>
          <tr v-for="(subscriber, i) in subscribers" :key="i">
            <td>{{ subscriber.ck_id }}</td>
            <td>{{ subscriber.first_name }}</td>
            <td>{{ subscriber.email_address }}</td>
            <td>{{ subscriber.state }}</td>
            <td>
              <v-row>
                <v-col cols="12">
                  <v-combobox
                    :value="getSubscriberTags(subscriber.ck_id)"
                    :item-text="tag => tag['name']"
                    :return-object="false"
                    label="Tags"
                    chips
                    deletable-chips
                    multiple
                    outlined
                    dense
                  >
                  </v-combobox>
                </v-col>
              </v-row>
            </td>
          </tr>
        </template>
      </v-data-table>
      <!-- <router-view /> -->
    </v-container>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  data: () => ({
    temp: []
  }),
  computed: {
    ...mapGetters({
      subscribers: "getSubscribers"
    }),
    ...mapGetters({
      tags: "getSubscriberTags"
    }),
    headers() {
      return [
        { text: "ID", value: "id" },
        { text: "First Name", value: "first_name" },
        { text: "Last Name", value: "last_name" },
        { text: "Email", value: "email_address" },
        { text: "State", value: "state" },
        { text: "Tags", value: "tags" }
      ];
    }
  },
  methods: {
    async getSubscriberTags(id) {
      const tags = this.$store.dispatch("getSubscriberTags", { id });
      console.log(tags);
    }
  },
  created() {
    this.$store.dispatch("getSubscribers");
    this.$store.dispatch("getSubscriberTags");
  }
};
</script>

<style lang="scss" scoped></style>
