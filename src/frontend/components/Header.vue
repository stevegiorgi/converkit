<template>
  <v-container fluid class="header__container">
    <v-row class="group__select" align="center">
      <v-col sm="12" md="6">
        <div class="toggle__container">
          <span class="toggle__text">Dark/Light Mode</span>

          <!-- <v-tooltip v-if="!$vuetify.theme.dark" bottom>
          <template v-slot:activator="{ on }">
            <v-switch v-on="on" @click="darkMode" inset></v-switch>
          </template>
          <span style="color: #000;">Dark Mode On</span>
        </v-tooltip>

        <v-tooltip v-else bottom>
          <template v-slot:activator="{ on }">
            <v-switch v-on="on" @click="darkMode" inset></v-switch>
          </template>
          <span>Dark Mode Off</span>
        </v-tooltip> -->

          <v-tooltip v-if="!$vuetify.theme.dark" bottom>
            <template v-slot:activator="{ on }">
              <div v-on="on" small fab @click="darkMode">
                <v-switch inset></v-switch>
              </div>
            </template>
            <span style="color: #000;">Dark Mode On</span>
          </v-tooltip>
          <v-tooltip v-else bottom>
            <template v-slot:activator="{ on }">
              <div v-on="on" small fab @click="darkMode">
                <v-switch inset></v-switch>
              </div>
            </template>
            <span>Dark Mode Off</span>
          </v-tooltip>
        </div>
        <span class="header__small">(Beta v.2.1)</span>
        <span class="header__label theme--light">Reporting for:</span>
        <h1 v-if="selectedGroups.length >= 2" class="header__title">
          <span>{{ selectedGroups.map(group => group.name).join(", ") }}</span>
        </h1>
        <h1 v-else-if="selectedGroups.length === 1" class="header__title">
          <span v-for="group in selectedGroups" :key="group.id">
            {{ group.name }}
          </span>
        </h1>
        <h1 v-else class="header__title">
          <span>No Group(s) Selected</span>
        </h1>
      </v-col>

      <v-col sm="6" md="3" class="d-flex justify-end"> </v-col>

      <v-col sm="6" md="3">
        <template>
          <v-card class="mx-auto" tile outline elevation="0">
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title
                  >Group Leaders:
                  {{ this.multipleGroupLeaders.length }}</v-list-item-title
                >
              </v-list-item-content>
            </v-list-item>

            <v-list-item>
              <v-list-item-content>
                <v-list-item-title
                  >Unique Users:
                  {{ this.uniqueUsers.length }}</v-list-item-title
                >
              </v-list-item-content>
            </v-list-item>
          </v-card>
        </template>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";
import { Bus } from "@/frontend/mixins/bus.js";
import App from "../../admin/App.vue";

export default {
  components: { App },
  props: {
    items: {
      type: Array,
      required: true
    }
  },
  methods: {
    darkMode() {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
    }
    // async getGroupData(groupId) {
    //   try {
    //     this.isLoading = true;
    //     const res = await this.$axios.get("ldlms/v2/groups/" + groupId);
    //     this.group = res.data;
    //     console.log(this.group);
    //   } catch (e) {
    //     this.error(e.response ? e.response.data.message : e.message);
    //   } finally {
    //     this.isLoading = false;
    //   }
    // }
  },
  computed: {
    ...mapGetters({
      groups: "groups"
    }),
    ...mapGetters({
      uniqueUsers: "uniqueUsers"
    }),
    ...mapGetters({
      multipleGroupLeaders: "multipleGroupLeaders"
    }),
    ...mapGetters({
      selectedGroups: "selectedGroups"
    })
  },
  created() {
    // this.getGroupData(this.$store.state.filters.selectedGroup);
    // console.log('getGroupData', this.groups);
    // Bus.$on("selectGroup", selectedGroup => {
    //   console.log("Selected Group:", selectedGroup);
    //   this.getGroupData(selectedGroup);
    // });
  }
};
</script>

<style lang="scss" scoped>
$red: #cb080f;
$black: #000;
$white: #fff;
$grey: #707070;

.theme--dark .v-application .primary--text {
  color: $white !important;
}

.theme--light {
  .toggle__text {
    color: $black !important;
  }
}

.header {
  &__label {
    font-size: 20px;
    text-transform: uppercase;
    color: $white;
  }

  &__small {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: $red;
  }

  &__title {
    font-family: "Oswald", sans-serif;
    font-size: 50px;
    font-weight: 600;
    line-height: 1.2;
    text-transform: uppercase;
    color: $red;

    span {
      color: $red !important;
    }
  }
}

.toggle {
  &__container {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  &__text {
    font-size: 12px;
  }
}
</style>
