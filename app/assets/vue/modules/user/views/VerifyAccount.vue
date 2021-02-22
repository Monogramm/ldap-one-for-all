<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
      <h2 class="subtitle">
        {{ $t("profile.verification.subtitle") }}
      </h2>
    </div>

    <app-verify-account
      :error="error"
      :is-loading="isLoading"
      @resendVerificationCode="resend"
      @confirmVerificationCode="confirm"
    />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import AppVerifyAccount from "../components/AppVerifyAccount/AppVerifyAccount.vue";

export default {
  name: "VerifyAccount",
  components: {
    AppVerifyAccount
  },
  computed: {
    ...mapGetters("user", ["isLoading", "error", "hasError"]),
    titleLabel() {
      return this.$t("profile.verification.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  },
  methods: {
    confirm(code: string) {
      this.$store.dispatch("user/confirmVerificationCode", code).then(() => {
        if (!this.hasError) {
          // Update authenticated user to get the verified status from backend
          this.$store.dispatch("auth/getAuthUser");
          this.$router.replace("/home");
        }
      });
    },
    resend() {
      this.$store.dispatch("user/requestVerificationCode");
    }
  }
};
</script>

<style lang="scss" scoped>
</style>