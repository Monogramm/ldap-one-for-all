<template>
  <div class="box">
    <b-table
      :data="users"
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
        field="username"
        :label="usernameLabel"
      >
        {{ props.row.username }}
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="email"
        :label="emailLabel"
      >
        {{ props.row.email }}
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="language"
        :label="languageLabel"
      >
        {{ props.row.language }}
      </b-table-column>

      <b-table-column
        v-slot="props"
        field="verified"
        :label="verifiedLabel"
      >
        <b-icon
          :icon="props.row.isVerified ? 'check' : 'times'"
          :type="props.row.isVerified ? 'is-success': 'is-danger'"
        />
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
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
