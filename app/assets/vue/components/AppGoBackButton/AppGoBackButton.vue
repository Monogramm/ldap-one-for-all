<template>
  <div class="back-button no-print">
    <b-button
      v-if="notExcluded"
      type="is-primary"
      icon-left="angle-left"
      @click="clicked"
    >
      {{ $t("common.back") }}
    </b-button>
  </div>
</template>

<script lang="ts">
export default {
  name: "AppGoBackButton",
  computed: {
    notExcluded() {
      let routeName = this.$route.name;

      return (
        routeName !== "Index" &&
        routeName !== "Home"
      );
    }
  },
  methods: {
    clicked() {
      this.$emit("clicked");
    }
  }
};
</script>

<style lang="scss" scoped>
@import '../../../styles/design-system';

.back-button {
  padding-left: 3%;
  padding-top: 2%;
}
</style>
