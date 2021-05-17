<template>
  <section class="section">
    <h1 class="title is-1">
      {{ $t("parameters.list") }}
    </h1>

    <div class="box">
      <b-button
        type="is-primary"
        class="field"
        @click="onCreate"
      >
        {{ $t("common.create") }}
      </b-button>
      <b-table
        :data="parameters"
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
          :label="nameColumnLabel"
        >
          {{ props.row.name }}
        </b-table-column>

        <b-table-column
          v-slot="props"
          field="value"
          searchable
          sortable
          :label="valueColumnLabel"
        >
          {{ formatValue(props.row.value, props.row.type) }}
        </b-table-column>

        <b-table-column
          v-slot="props"
          field="description"
          searchable
          sortable
          :label="descriptionColumnLabel"
        >
          {{ props.row.description | shorten(60) }}
        </b-table-column>

        <b-table-column
          v-slot="props"
          field="buttons"
        >
          <div class="buttons">
            <b-button
              type="is-warning"
              @click="onEdit(props.row.id)"
            >
              {{ $t("common.edit") }}
            </b-button>
            <b-button
              type="is-danger"
              @click="onDelete(props.row.id)"
            >
              {{ $t("common.delete") }}
            </b-button>
          </div>
        </b-table-column>
      </b-table>
    </div>
  </section>
</template>

<script lang="ts">
import { truncate } from "../../../../../common/helpers";

export default {
  name: "AppParameters",
  filters: {
    shorten(value: string | null, length: number) {
      return truncate(value, length);
    },
  },
  props: {
    parameters: {
      type: Array,
      default: (): [] => [],
    },
    total: {
      type: Number,
      default: 0,
    },
    perPage: {
      type: Number,
      default: 50,
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    nameColumnLabel() {
      return this.$t("parameters.name");
    },
    valueColumnLabel() {
      return this.$t("parameters.value");
    },
    descriptionColumnLabel() {
      return this.$t("parameters.description");
    },
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
    },
  },
  methods: {
    onEdit(id: string) {
      this.$emit("edit", id);
    },
    onCreate() {
      this.$emit("create");
    },
    onDelete(id: string) {
      this.$emit("delete", id);
    },
    onPageChange(page: number) {
      this.$emit("pageChanged", page);
    },
    onFiltersChange(filters: any) {
      this.$emit("filtersChanged", filters);
    },
    onSortingChange(field: string, order: string) {
      this.$emit("sortingChanged", field, order);
    },
    formatValue(value: string, type: string) {
      if (type === 'secret') {
        return '••••••••'
      }

      if (type === 'number') {
        return Number(value)
      }

      return value;
    }
  },
};
</script>

<style lang="scss" scoped></style>
