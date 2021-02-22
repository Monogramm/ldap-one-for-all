<template>
  <div class="box">
    <b-table
      :data="jobs"
      :loading="isLoading"
      :total="total"
      :per-page="perPage"
      backend-pagination
      paginated
      :aria-next-label="nextPageLabel"
      :aria-previous-label="previousPageLabel"
      :aria-page-label="pageLabel"
      :aria-current-label="currentPageLabel"
      @page-change="onPageChange"
    >
      <b-table-column
        v-slot="props"
        field="name"
        label="Name"
      >
        {{ props.row.name }}
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="last_execution"
        label="Last Execution"
      >
        {{ props.row.lastExecution }}
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="status"
        label="Status"
      >
        {{ props.row.status }}
      </b-table-column>
    </b-table>
  </div>
</template>

<script lang="ts">
import { BackgroundJob } from "../../../../backgroundJob/interfaces";

export default {
  name: "AppBackgroundJobs",
  props: {
    isLoading: {
      type: Boolean,
      default: false
    },
    jobs: {
      type: Array,
      default: function (): Array<BackgroundJob> {
        return []
      }
    },
    perPage: {
      type: Number,
      default: 20
    },
    total: {
      type: Number,
      default: 0
    }
  },
  computed: {
    nextPageLabel() {
      return this.$t("table.next-page");
    },
    previousPageLabel() {
      return this.$t("table.previous-page");
    },
    pageLabel() {
      return this.$t("table.page");
    },
    currentPageLabel() {
      return this.$t("table.current-page");
    }
  },
  methods: {
    onPageChange(page: number) {
      this.$emit('pageChanged', page);
    },
  }
}
</script>

<style lang="scss" scoped>

</style>
