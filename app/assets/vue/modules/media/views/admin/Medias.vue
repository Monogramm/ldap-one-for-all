<template>
  <section class="section">
    <h1 class="title is-1">
      {{ $t("medias.list") }}
    </h1>

    <app-medias
      :medias="items"
      :is-loading="isLoading"
      :per-page="pagination.size"
      :total="total"
      @create="onCreate"
      @edit="onEdit"
      @delete="onDelete"
      @pageChanged="onPageChange"
      @filtersChanged="onFiltersChange"
      @sortingChanged="onSortingChange"
    />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { Pagination } from "../../../../interfaces/pagination";
import { Criteria } from "../../../../interfaces/criteria";
import { Sort } from "../../../../interfaces/sort";

import AppMedias from "../../components/admin/AppMedias/AppMedias.vue";

export default {
  name: "Medias",
  components: { AppMedias },
  data() {
    return {
      pagination: new Pagination(),
    };
  },
  computed: {
    ...mapGetters("media", ["items", "isLoading", "total"])
  },
  created() {
    this.load();
  },
  methods: {
    load() {
      this.$store.dispatch("media/getAll", this.pagination);
    },
    onPageChange(page: number) {
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
    onEdit(paramId: string) {
      this.$router.push({ name: "MediaEdit", params: { id: paramId } });
    },
    onCreate() {
      this.$router.push({ name: "MediaCreate" });
    },
    onDelete(id: string) {
      this.$buefy.dialog.confirm({
        title: this.$t("common.confirmation.delete"),
        message: this.$t("common.confirmation.delete-message"),
        cancelText: this.$t("common.cancel"),
        confirmText: this.$t("common.delete"),
        type: "is-danger",
        hasIcon: true,
        onConfirm: () => {
          this.$store.dispatch("media/delete", id);
          this.$buefy.toast.open(this.$t("common.confirmation.deleted"));
        }
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
