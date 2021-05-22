<template>
  <div class="box">
    <b-table
      :data="users"
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
        field="username"
        searchable
        sortable
        :label="usernameLabel"
      >
        {{ props.row.username }}
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="email"
        searchable
        sortable
        :label="emailLabel"
      >
        {{ props.row.email }}
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="language"
        searchable
        sortable
        :label="languageLabel"
      >
        {{ props.row.language }}
      </b-table-column>

      <b-table-column
        field="isVerified"
        searchable
        sortable
        :label="verifiedLabel"
      >
        <template #searchable="props">
          <b-select v-model="props.filters[props.column.field]">
            <option
              key=""
              value=""
            />
            <option
              key="1"
              :value="true"
            >
              {{ $t("common.yes") }}
            </option>
            <option
              key="0"
              :value="false"
            >
              {{ $t("common.no") }}
            </option>
          </b-select>
        </template>
        <template v-slot="props">
          <b-icon
            :icon="props.row.isVerified ? 'check' : 'times'"
            :type="props.row.isVerified ? 'is-success': 'is-danger'"
          />
        </template>
      </b-table-column>
    </b-table>
  </div>
</template>

<script lang="ts">
import { IUser } from "../../../interfaces/user";

export default {
  name: "AppUsers",
  props: {
    isLoading: {
      type: Boolean,
      default: false
    },
    users: {
      type: Array,
      default: function(): Array<IUser> {
        return [];
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
    },
    usernameLabel() {
      return this.$t("users.username");
    },
    emailLabel() {
      return this.$t("users.email");
    },
    languageLabel() {
      return this.$t("users.language");
    },
    verifiedLabel() {
      return this.$t("users.verified");
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
};
</script>

<style lang="scss" scoped>
</style>
