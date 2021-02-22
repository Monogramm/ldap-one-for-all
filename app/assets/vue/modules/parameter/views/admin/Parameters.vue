<template>
  <app-parameters
    :is-loading="isLoading"
    :parameters="items"
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

import AppParameters from "../../components/admin/AppParameters/AppParameters.vue";

export default {
  name: "Parameters",
  components: { AppParameters },
  data() {
    return {
      pagination: new Pagination(),
    };
  },
  computed: {
    ...mapGetters("parameter", ["items", "isLoading", "total"])
  },
  created() {
    this.$store.dispatch("parameter/getAll", this.pagination);
  },
  methods: {
    onPageChange(page: string) {
      this.pagination.page = page;
      this.$store.dispatch("parameter/getAll", this.pagination);
    },
    onEdit(paramId: string) {
      this.$router.push({ name: "ParameterEdit", params: { id: paramId } });
    },
    onCreate() {
      this.$router.push({ name: "ParameterCreate" });
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
          this.$store.dispatch("parameter/delete", id);
          this.$buefy.toast.open(this.$t("common.confirmation.deleted"));
        }
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
