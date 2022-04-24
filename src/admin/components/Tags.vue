<template v-slot:item.tags>
  <td>
    <v-row>
      <v-col cols="12">
        <!-- is this being used? @change="getSubscriber(subscriber)" -->
        <v-combobox
          v-model="subscribersTags[subscriber.ck_id]"
          :items="tags"
          :item-text="(tag) => tag.name"
          :item-value="(tag) => tag.id"
          @change="addSubscriberTag(subscribers, subscriber, subscribersTags[subscriber.ck_id])"
          label="Tags"
          multiple
          small-chips
        >
          <template slot="selection" slot-scope="data">
            <v-chip
              close
              :key="data.item.id"
              :input-value="data.selected"
              @click:close="removeSubscriberTag(subscriber, data.item)"
            >
              {{ getItemText(data.item.id) }}</v-chip
            >
          </template>
        </v-combobox>
      </v-col>
    </v-row>
  </td>
</template>

<script>
export default {};
</script>

<style></style>
