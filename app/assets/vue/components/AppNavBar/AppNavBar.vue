<template>
  <b-navbar
    fixed-top
    shadow
    type="is-dark"
    class="app-nav-bar no-print"
  >
    <template slot="brand">
      <b-navbar-item
        tag="a"
        :href="websiteUrl"
      >
        <img
          :src="require('../../../images/app_logo.png')"
          :alt="$t('app.core.name')"
        >
        {{ $t("app.core.name") }}
      </b-navbar-item>
    </template>

    <template slot="end">
      <b-navbar-item
        v-if="authenticated"
        tag="router-link"
        icon-left="home"
        :to="{ name: 'Home' }"
      >
        {{ $t("home.title") }}
      </b-navbar-item>
      <b-navbar-item
        v-if="authenticated && admin"
        tag="router-link"
        icon-left="wrench"
        :to="{ name: 'AdminDashboard' }"
      >
        {{ $t("admin.dashboard") }}
      </b-navbar-item>
      <b-navbar-dropdown :label="$t('common.languages')">
        <b-navbar-item
          v-for="(option, idx) in languages"
          :key="idx"
          :value="option.value"
          @click="selectLanguage(option.value)"
        >
          {{ option.label }}
        </b-navbar-item>
      </b-navbar-dropdown>
      <div class="buttons is-centered">
        <b-button
          id="pwa-install-link"
          type="is-info is-hidden"
          icon-left="download"
          class="pwa-install-link"
        >
          <strong>{{ $t("install.link") }}</strong>
        </b-button>
      </div>
    </template>
  </b-navbar>
</template>

<script lang="ts">
export default {
  name: "AppNavBar",
  props: {
    signingIn: {
      type: Boolean,
      default() {
        return false;
      }
    },
    signingUp: {
      type: Boolean,
      default() {
        return false;
      }
    },
    signingOut: {
      type: Boolean,
      default() {
        return false;
      }
    },
    authenticated: {
      type: Boolean,
      default() {
        return false;
      }
    },
    admin: {
      type: Boolean,
      default() {
        return false;
      }
    },
    languages: {
      type: Array,
      default(): Array<any> {
        return [];
      }
    }
  },
  data() {
    return {
      websiteUrl: process.env.WEBSITE_PUBLIC_URL
    }
  },
  methods: {
    logout() {
      this.$emit("loggedOut");
    },
    selectLanguage(value: string) {
      this.$emit("languageChanged", value);
    }
  }
};
</script>

<style lang="scss" scoped>
@import '../../../styles/design-system';

</style>
