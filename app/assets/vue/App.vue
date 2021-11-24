<template>
  <div class="app-main">
    <app-top-bar
      :authenticated="isLoggedIn"
      :impersonator="isImpersonator"
      :signing-out="isLoading"
      @loggedOut="logout"
      @stopImpersonation="stopImpersonation"
    />
    <app-nav-bar
      :authenticated="isLoggedIn"
      :admin="isAdmin"
      :languages="locales"
      @languageChanged="localeSwitch"
    />
    <div class="container app-content">
      <app-go-back-button @clicked="goBack" />
      <router-view />
    </div>
    <app-footer class="app-footer" />
  </div>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { availableLocales, loadLocaleAsync, i18n } from "./plugins/i18n/i18n";

import "../styles/global.scss";

import AppTopBar from "./components/AppTopBar/AppTopBar.vue";
import AppNavBar from "./components/AppNavBar/AppNavBar.vue";
import AppFooter from "./components/AppFooter/AppFooter.vue";
import AppGoBackButton from "./components/AppGoBackButton/AppGoBackButton.vue";

export default {
  name: "App",
  components: {
    AppTopBar,
    AppNavBar,
    AppFooter,
    AppGoBackButton
  },
  data(): any {
    return {
      locales: availableLocales
    };
  },
  computed: {
    ...mapGetters("auth", ["isLoggedIn", "isLoading", "isAdmin", "isImpersonator", "authUser"]),
  },
  metaInfo: {
    title: "App",
    titleTemplate: "LDAP One-For-All - %s",
    htmlAttrs: {
      lang: i18n.locale
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    logout() {
      this.$store.dispatch("auth/logout").then(() => {
        this.$router.push("/");
      });
    },
    stopImpersonation() {
      this.$store.dispatch("auth/stopImpersonation").then(() => {
        this.$router.push("/");
      });
    },
    localeSwitch(locale: string) {
      loadLocaleAsync(locale).catch((error: any) => console.log(error)); // tslint:disable-line

      // TODO  Move to a i18n module?
      this.$store.dispatch("auth/changeLanguage", locale);
    }
  }
};
</script>

<style lang="scss" scoped>
@import '../styles/design-system';

.app-main {
  background-color: rgba(252, 252, 252, 0.75);

  .app-content {
    min-height: 75vh;
  }

  .app-footer {
    min-height: 25vh;
  }
}
</style>
