<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
      <h2 class="subtitle">
        {{ $t("login.subtitle") }}
      </h2>
    </div>

    <b-message
      type="is-danger"
      :active="hasError"
      auto-close
      :duration="duration"
    >
      {{ $t("login.auth.failure") }}
    </b-message>

    <b-message
      type="is-success"
      :active="passwordResetSuccess"
      auto-close
      :duration="duration"
    >
      {{ $t("password-reset.success") }}
    </b-message>

    <app-login
      class=""
      @login="doLogin"
      @registration="goToRegistration"
      @passswordReset="goToPasswordReset"
    />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { ILogin, Login } from "../../../modules/auth/interfaces";

import AppLogin from "../components/AppLogin/AppLogin.vue";

export default {
  name: "Login",
  components: {
    AppLogin
  },
  data() {
    return {
      passwordResetSuccess: false,
      duration: 4000
    };
  },
  computed: {
    ...mapGetters("auth", [
      "error",
      "hasError"
    ]),
    titleLabel() {
      return this.$t("login.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  },
  methods: {
    async doLogin(username: string, password: string) {
      const credentials: ILogin = new Login(username, password);
      await this.$store.dispatch("auth/login", credentials);
      if (!this.hasError) {
        this.$router.go(-1);
      }
    },
    goToRegistration() {
      this.$router.replace("/registration");
    },
    goToPasswordReset() {
      this.$router.push("/password/reset");
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
