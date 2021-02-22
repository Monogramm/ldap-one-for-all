<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ $t("users.title") }}
      </h1>
    </div>

    <app-users
      :users="items"
      :is-loading="isLoading"
      :total="total"
      :per-page="pagination.size"
      @pageChanged="onPageChange"
      @edit="onEdit"
      @create="onCreate"
    />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { IPagination, Pagination } from "../../../../interfaces/pagination";

import AppUsers from "../../components/admin/AppUsers/AppUsers.vue";

export default {
  name: "AdminUsers",
  components: { AppUsers },
  data() {
    return {
      isFullPage: true,
      pagination: new Pagination(),
    };
  },
  computed: {
    ...mapGetters("user", ["items", "total", "isLoading"])
  },
  created() {
    this.$store.dispatch("user/getAll", this.pagination);
  },
  methods: {
    onPageChange(page: number) {
      this.pagination.page = page;
      this.$store.dispatch("user/getAll", this.pagination);
    },
    onEdit(id: string) {
      this.$router.push({ name: "UserEdit", params: { id: id } });
    },
    onCreate() {
      this.$router.push({ name: "UserCreate" });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
