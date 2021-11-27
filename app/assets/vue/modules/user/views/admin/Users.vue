<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ $t("users.list") }}
      </h1>
    </div>

    <div class="box cloudy dark">
      <div class="buttons">
        <b-button
          type="is-info"
          icon-left="redo"
          class="field"
          :loading="isLoading"
          @click="load"
        >
          {{ $t("common.refresh") }}
        </b-button>
      </div>

      <app-users
        :auth-user="authUser"
        :users="items"
        :is-loading="isLoading"
        :per-page="pagination.size"
        :total="total"
        @edit="onEdit"
        @pageChanged="onPageChange"
        @filtersChanged="onFiltersChange"
        @sortingChanged="onSortingChange"
        @enabled="onEnableChange"
      />
    </div>
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { Pagination } from "../../../../interfaces/pagination";
import { Criteria } from "../../../../interfaces/criteria";
import { Sort } from "../../../../interfaces/sort";

import { EnablePayload } from '../../actions';

import AppUsers from "../../components/admin/AppUsers/AppUsers.vue";

export default {
  name: "AdminUsers",
  components: { AppUsers },
  data() {
    return {
      pagination: new Pagination(),
    };
  },
  computed: {
    ...mapGetters("user", ["items", "total", "isLoading"]),
    ...mapGetters("auth", ["authUser"]),
  },
  created() {
    this.load();
  },
  methods: {
    load() {
      this.$store.dispatch("user/getAll", this.pagination);
    },
    onEdit(userId: string) {
      this.$router.push({ name: "UserEdit", params: { id: userId } });
    },
    onPageChange(page: string) {
      this.pagination.page = page;
      if (this.pagination.size > 0) {
        this.load();
      }
    },
    onFiltersChange(filters: any) {
      this.pagination.criteria = new Criteria(filters);
      if (this.pagination.size > 0) {
        this.load();
      }
    },
    onSortingChange(field: string, order: string) {
      this.pagination.orderBy = new Sort(field, order);
      if (this.pagination.size > 0) {
        this.load();
      }
    },
    onEnableChange(userId: string, enabled: boolean) {
      this.$buefy.dialog.confirm({
        message: this.$t(
          enabled ? "users.enable-message" : "users.disable-message"
        ),
        confirmText: this.$t("common.continue"),
        cancelText: this.$t("common.cancel"),
        type: "is-info",
        hasIcon: true,
        onConfirm: () => {
          const payload: EnablePayload = {
            userId: userId,
            enabled: enabled,
          };
          this.$store.dispatch("user/setEnable", payload).then(
            () => this.load()
          );
        },
      });
    },
  }
};
</script>

<style lang="scss" scoped>
</style>
