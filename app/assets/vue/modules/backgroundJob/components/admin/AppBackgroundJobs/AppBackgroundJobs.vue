<template>
  <div class="box">
    <b-table
      :data="jobs"
      :loading="isLoading"
      :total="total"
      :paginated="perPage > 0"
      :per-page="perPage"
      backend-pagination
      pagination-position="both"
      :backend-filtering="perPage > 0"
      :debounce-search="500"
      :backend-sorting="perPage > 0"
      :aria-next-label="nextPageLabel"
      :aria-previous-label="previousPageLabel"
      :aria-page-label="pageLabel"
      :aria-current-label="currentPageLabel"
      @page-change="onPageChange"
      @filters-change="onFiltersChange"
      @sort="onSortingChange"
    >
      <b-table-column
        v-slot="props"
        field="name"
        searchable
        sortable
        label="Name"
      >
        {{ props.row.name }}
      </b-table-column>

      <b-table-column
        field="lastExecution"
        sortable
        label="Last Execution"
      >
        <template v-slot="props">
          {{ props.row.lastExecution }}
        </template>
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="status"
        searchable
        sortable
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
      this.$emit("pageChanged", page);
    },
    onFiltersChange(filters: any) {
      this.$emit("filtersChanged", filters);
    },
    onSortingChange(field: string, order: string) {
      this.$emit("sortingChanged", field, order);
    },
  }
}
</script>

<style lang="scss" scoped>

</style>
