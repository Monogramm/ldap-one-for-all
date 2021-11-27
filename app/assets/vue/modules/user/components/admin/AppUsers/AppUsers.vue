<template>
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
      <span>
        <b-icon
          pack="fas"
          :icon="roleIcon(props.row.roles)"
          :title="$t(roleTitle(props.row.roles))"
        />
        {{ props.row.username }}
      </span>
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

    <b-table-column
      field="enabled"
      searchable
      sortable
      :label="enabledLabel"
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
        <b-button
          :type="props.row.enabled ? 'is-success': 'is-danger'"
          :icon-left="props.row.enabled ? 'check' : 'times'"
          :loading="isLoading"
          @click="onSetEnabled(props.row.id, !props.row.enabled)"
        >
          {{ $t(props.row.enabled ? "common.yes" : "common.no") }}
        </b-button>
      </template>
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
      </div>
    </b-table-column>
  </b-table>
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
    authUser: {
      type: Object,
      default: null
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
    },
    enabledLabel() {
      return this.$t("users.enabled");
    },
  },
  methods: {
    onEdit(id: string) {
      this.$emit("edit", id);
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
    onImpersonate(username: string) {
      this.$emit("impersonate", username);
    },
    onSetEnabled(userId: string, enabled: boolean) {
      this.$emit("enabled", userId, enabled);
    },
    roleIcon(roles: Array<string>): string {
      if (roles.includes('ROLE_SUPER_ADMIN')) {
        return 'user-secret';
      } else if (roles.includes('ROLE_ADMIN')) {
        return 'user-tie';
      } else if (roles.includes('ROLE_MONITORING')) {
        return 'user-clock';
      } else if (roles.includes('ROLE_VERIFIED_USER')) {
        return 'user-check';
      } else if (roles.includes('ROLE_USER')) {
        return 'user';
      } else {
        return 'question';
      }
    },
    roleTitle(roles: Array<string>): string {
      if (roles.includes('ROLE_SUPER_ADMIN')) {
        return 'users.role.super-admin';
      } else if (roles.includes('ROLE_ADMIN')) {
        return 'users.role.admin';
      } else if (roles.includes('ROLE_MONITORING')) {
        return 'users.role.monitoring';
      } else if (roles.includes('ROLE_VERIFIED_USER')) {
        return 'users.role.verified-user';
      } else if (roles.includes('ROLE_USER')) {
        return 'users.role.user';
      } else {
        return 'users.role.unknown';
      }
    },
  }
};
</script>

<style lang="scss" scoped>
</style>
