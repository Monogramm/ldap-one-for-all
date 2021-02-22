<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
    </div>

    <div class="box">
      <p>{{ $t("privacy.content") }}</p>
    </div>
  </section>
</template>

<script lang="ts">
export default {
  name: "Privacy",
  computed: {
    titleLabel() {
      return this.$t("privacy.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  }
};
</script>
