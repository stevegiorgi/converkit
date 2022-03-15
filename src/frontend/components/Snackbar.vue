<template>
  <v-snackbar v-model="snackbar" :timeout="5000">
    {{ message }}

    <template v-slot:action="{ attrs }">
      <v-btn color="red" text v-bind="attrs" @click="snackbar = false">
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
    computed: {
        message() {
            return this.$store.state.message;
        }
    },
    watch: {
        message(oldVal, newVal) {
            this.snackbar = true;
        }
    },
    data() {
        return {
            snackbar: false
        }
    }
}
</script>
