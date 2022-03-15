<template>
  <v-container fluid class="user-details__panel">
    <v-row>
      <v-col cols="12" md="12" class="px-0">Test 
        <!-- <v-data-table
          :headers="courseHeaders"
          :items="filteredCourses"
          item-key="id"
          :hide-default-footer="true"
          class="user-details__table"
          :sort-by.sync="sortByCourse"
          :sort-desc.sync="sortDescCourse"
        >
            <tr>
              <td
                class="data__column d-block d-sm-table-cell"
                v-html="item.course_title"
              ></td>
              <td class="data__column d-block d-sm-table-cell">
                {{ item.course_status | status }}
              </td>
              <td class="data__column d-block d-sm-table-cell">
                <v-chip
                  :color="
                    getColor(
                      completionPercentage(
                        item.course_progress.completed,
                        item.course_progress.total
                      )
                    )
                  "
                  dark
                >
                  {{
                    completionPercentage(
                      item.course_progress.completed,
                      item.course_progress.total
                    ) + "%"
                  }}
                </v-chip>
              </td>
              <td
                class="data__column d-block d-sm-table-cell"
                v-if="item.date_started"
              >
                {{ item.date_started }}
              </td>
              <td class="data__column d-block d-sm-table-cell" v-else>
                ––
              </td>
              <td
                class="data__column d-block d-sm-table-cell"
                v-if="item.date_completed"
              >
                {{ item.date_completed }}
              </td>
              <td class="data__column d-block d-sm-table-cell" v-else>
                ––
              </td>
            </tr>
          </template>
        </v-data-table> -->
      </v-col>

    </v-row>
  </v-container>
</template>

<script>
export default {};
</script>

<style></style>
