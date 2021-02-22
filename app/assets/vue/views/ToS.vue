<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ $t("tos.title") }}
      </h1>
    </div>

    <div class="box">
      <p>{{ $t("tos.content") }}</p>
    </div>
  </section>
</template>

<script lang="ts">
export default {
  name: "ToS",
  computed: {
    titleLabel() {
      return this.$t("tos.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  }
};
</script>
