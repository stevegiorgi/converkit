<template>
  <v-snackbar
    v-model="snackbar.visible"
    :color="snackbar.color"
    :multi-line="snackbar.mode === 'multi-line'"
    :timeout="snackbar.timeout"
    :bottom="snackbar.position === 'bottom'"
    elevation="24"
  >
    <v-layout align-center pr-4>
      <v-icon class="pr-3" dark large>{{ snackbar.icon }}</v-icon>
      <v-layout row>
        <div>
          <strong>{{ snackbar.title }}</strong>
        </div>
        <div>{{ snackbar.text }}</div>
      </v-layout>
    </v-layout>
    <v-btn v-if="snackbar.timeout === 0" icon @click="snackbar.visible = false">
      <v-icon>clear</v-icon>
    </v-btn>
  </v-snackbar>
</template>

<script>
import { bus } from "../main";

export default {
  name: "Snackbar",
  data() {
    return {
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
    };
  },
  methods: {
    displaySnackbar(param) {
      if (!param.type) return;
      switch (param.type) {
        case "error":
          this.snackbar = {
            color: "error",
            icon: "error",
            mode: "multi-line",
            position: "top",
            timeout: 7500,
            title: "Error",
            text: param.message || "There was a problem completing your request.",
            visible: true,
          };
          break;
        case "info":
          this.snackbar = {
            color: "info",
            icon: "info",
            mode: "multi-line",
            position: "top",
            timeout: 7500,
            title: "Info",
            text: param.message || "No information",
            visible: true,
          };
          break;
        case "success":
          this.snackbar = {
            color: "success",
            icon: "check_circle",
            mode: "multi-line",
            position: "top",
            timeout: 7500,
            title: "Success",
            text: param.message || "Your request was completed successfully.",
            visible: true,
          };
      }
    },
  },
  created() {
    bus.$on("displaySnackbar", (param) => {
      this.displaySnackbar(param);
    });
  },
};
</script>

<style></style>
