<template>
  <b-table
    :data="medias"
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
      field="filename"
      sortable
      :label="fileColumnLabel"
    >
      {{ props.row.filename }}
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
          icon-left="edit"
          @click="onEdit(props.row.id)"
        >
          {{ $t("common.edit") }}
        </b-button>
        <b-button
          type="is-danger"
          icon-left="trash"
          @click="onDelete(props.row.id)"
        >
          {{ $t("common.delete") }}
        </b-button>
      </div>
    </b-table-column>
  </b-table>
</template>

<script lang="ts">
import { truncate } from "../../../../../common/helpers";

export default {
  name: "AppMedias",
  filters: {
    shorten(value: string | null, length: number) {
      return truncate(value, length);
    },
  },
  props: {
    medias: {
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
      return this.$t("medias.name");
    },
    fileColumnLabel() {
      return this.$t("medias.filename");
    },
    descriptionColumnLabel() {
      return this.$t("medias.description");
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
  },
};
</script>

<style lang="scss" scoped></style>
