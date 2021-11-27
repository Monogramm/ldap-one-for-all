<template>
  <section class="section">
    <h1 class="title is-1">
      {{ $t("parameters.list") }}
    </h1>

    <div class="box">
      <div class="buttons">
        <b-button
          type="is-primary"
          icon-left="plus"
          class="field"
          @click="onCreate"
        >
          {{ $t("common.create") }}
        </b-button>
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

      <app-parameters
        :is-loading="isLoading"
        :parameters="items"
        :per-page="pagination.size"
        :total="total"
        @edit="onEdit"
        @delete="onDelete"
        @pageChanged="onPageChange"
        @filtersChanged="onFiltersChange"
        @sortingChanged="onSortingChange"
      />
    </div>
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { Pagination } from "../../../../interfaces/pagination";
import { Criteria } from "../../../../interfaces/criteria";
import { Sort } from "../../../../interfaces/sort";

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
    this.load();
  },
  methods: {
    load() {
      this.$store.dispatch("parameter/getAll", this.pagination);
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
