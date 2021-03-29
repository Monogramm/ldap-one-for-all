<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
      <h2 class="subtitle">
        {{ $t("signup.subtitle") }}
      </h2>
    </div>

    <b-message
      type="is-success"
      :active="isSuccess"
    >
      {{ $t("signup.success") }}
    </b-message>

    <app-register
      class=""
      @register="doRegister"
    />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import AppRegister from "../components/AppRegister/AppRegister.vue";

export default {
  name: "Registration",
  components: {
    AppRegister
  },
  data() {
    return {
      success: false,
      duration: 4000
    };
  },
  computed: {
    ...mapGetters("user", ["error", "hasError", "isLoading", "language"]),
    isSuccess: {
      get(): boolean {
        return this.success;
      },
      set(value: boolean) {
        this.success = value;
      }
    },
    titleLabel() {
      return this.$t("signup.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  },
  methods: {
    async doRegister(
      email: String,
      username: String,
      password: String,
      confirmPassword: String,
      tos: Boolean
    ) {
      let user = {
        email: email,
        username: username,
        password: password,
        confirmPassword: confirmPassword,
        tos: tos,
        language: this.language
      };
      await this.$store.dispatch("user/register", user);
      if (!this.hasError) {
        this.isSuccess = true;
        await new Promise(r => setTimeout(r, this.duration));
        this.$router.replace("/login");
      }
    },
    isValid() {
      return (
        !!this.email &&
        !!this.username &&
        !!this.password &&
        this.password === this.confirmPassword &&
        this.tos === true
      );
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
