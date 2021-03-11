<template>
  <section class="section">
    <h1 class="title is-1">
      {{ $t("medias.list") }}
    </h1>

    <div class="box">
      <b-button
        type="is-primary"
        class="field"
        @click="create"
      >
        {{ $t("common.create") }}
      </b-button>
      <b-table
        :data="medias"
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
          :label="nameColumnLabel"
        >
          {{ props.row.name }}
        </b-table-column>

        <b-table-column
          v-slot="props"
          field="filename"
          :label="fileColumnLabel"
        >
          {{ props.row.filename }}
        </b-table-column>

        <b-table-column
          v-slot="props"
          field="description"
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
              @click="edit(props.row.id)"
            >
              {{ $t("common.edit") }}
            </b-button>
            <b-button
              type="is-danger"
              @click="deleteMedia(props.row.id)"
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
    edit(id: string) {
      this.$emit("edit", id);
    },
    create() {
      this.$emit("create");
    },
    deleteMedia(id: string) {
      this.$emit("delete", id);
    },
    onPageChange(page: number) {
      this.$emit("pageChanged", page);
    }
  },
};
</script>

<style lang="scss" scoped></style>
