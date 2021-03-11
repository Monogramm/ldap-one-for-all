<template>
  <app-medias
    :is-loading="isLoading"
    :medias="items"
    :per-page="pagination.size"
    :total="total"
    @pageChanged="onPageChange"
    @create="onCreate"
    @edit="onEdit"
    @delete="onDelete"
  />
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { IPagination, Pagination } from "../../../../interfaces/pagination";

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
    this.$store.dispatch("media/getAll", this.pagination);
  },
  methods: {
    onPageChange(page: string) {
      this.pagination.page = page;
      this.$store.dispatch("media/getAll", this.pagination);
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
