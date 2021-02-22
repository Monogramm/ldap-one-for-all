<template>
  <footer class="footer no-print">
    <div class="has-text-centered">
      <router-link to="terms-of-services">
        {{ $t("tos.title") }}
      </router-link>|
      <router-link to="privacy-policy">
        {{ $t("privacy.title") }}
      </router-link>|
      <router-link to="install">
        {{ $t("install.title") }}
      </router-link>
    </div>
    <div class="content has-text-centered">
      <p>
        <strong>{{ $t('app.core.name') }}</strong>
        {{ $t("common.by") }}
        <a href="https://www.monogramm.io">Monogramm</a>.
        <br>
        &copy; {{ $t('app.core.copyright').replace('%n', year) }}
      </p>
    </div>
  </footer>
</template>

<script lang="ts">
export default {
  name: "AppFooter",
  computed: {
    year() {
      return new Date().getFullYear();
    }
  }
};
</script>

<style lang="scss" scoped>
@import '../../../styles/design-system';

p {
  color: $grey-dark;
}

strong {
  color: $grey-darker;
}
</style>
