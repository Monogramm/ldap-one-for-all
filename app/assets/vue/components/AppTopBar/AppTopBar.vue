<template>
  <b-navbar
    fixed-bottom
    :is-active="true"
    type="is-secondary"
    class="app-top-bar no-print"
  >
    <template slot="brand">
      <b-navbar-item class="is-uppercase has-text-weight-bold">
        {{ $t("app.core.start-here") }}
        <b-icon
          icon="angle-right"
          size="is-medium"
        />
      </b-navbar-item>
    </template>

    <template slot="end">
      <b-navbar-item tag="div" />
    </template>
  </b-navbar>
</template>

<script lang="ts">
export default {
  name: "AppTopBar",
  props: {
    
  },
  methods: {
    selectLanguage(value: string) {
      this.$emit("languageChanged", value);
    }
  }
};
</script>

<style lang="scss" scoped>
@import '../../../styles/design-system';

</style>
