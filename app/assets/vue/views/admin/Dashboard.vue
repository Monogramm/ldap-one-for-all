<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
    </div>

    <section class="section box modules">
      <b-button
        tag="router-link"
        to="/admin/users"
      >
        {{ $t("admin.users") }}
      </b-button>
      <b-button
        tag="router-link"
        to="/admin/parameters"
      >
        {{ $t("admin.parameters") }}
      </b-button>
      <b-button
        tag="router-link"
        to="/admin/medias"
      >
        {{ $t("admin.medias") }}
      </b-button>
      <b-button
        tag="router-link"
        to="/admin/background-jobs"
      >
        {{ $t("admin.background-jobs") }}
      </b-button>
    </section>
  </section>
</template>

<script lang="ts">
export default {
  name: "AdminDashboard",
  computed: {
    titleLabel() {
      return this.$t("admin.dashboard");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  }
};
</script>
