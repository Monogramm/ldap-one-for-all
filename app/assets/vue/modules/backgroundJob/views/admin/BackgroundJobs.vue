<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ $t("background-jobs.list") }}
      </h1>
    </div>

    <div class="box">
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

      <app-background-jobs
        :jobs="items"
        :is-loading="isLoading"
        :total="total"
        :per-page="pagination.size"
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

import AppBackgroundJobs from "../../components/admin/AppBackgroundJobs/AppBackgroundJobs.vue";

export default {
  name: "BackgroundJobs",
  components: { AppBackgroundJobs },
  data() {
    return {
      pagination: new Pagination(),
    }
  },
  computed: {
    ...mapGetters("backgroundJob", [
      "isLoading",
      "items",
      "total"
    ])
  },
  created() {
    this.load();
  },
  methods: {
    load() {
      this.$store.dispatch("backgroundJob/getAll", this.pagination);
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
  }
}
</script>

<style lang="scss" scoped>

</style>
