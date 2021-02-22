<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ $t("background-jobs.title") }}
      </h1>
    </div>

    <app-background-jobs
      :per-page="pagination.size"
      :is-loading="isLoading"
      :total="total"
      :jobs="items"
      @pageChanged="onPageChange"
    />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { IPagination, Pagination } from "../../../../interfaces/pagination";

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
    ...mapGetters('backgroundJob', [
      'isLoading',
      'items',
      'total'
    ])
  },
  created() {
    this.$store.dispatch('backgroundJob/getAll', this.pagination)
  },
  methods: {
    onPageChange(page: number) {
      this.pagination.page = page;
      this.$store.dispatch('backgroundJob/getAll', this.pagination)
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
