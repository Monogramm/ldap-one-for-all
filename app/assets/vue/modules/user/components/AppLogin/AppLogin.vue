<template>
  <form
    class="section box"
    @submit.prevent
  >
    <div class="inputs">
      <b-field
        :label="$t('login.id.label')"
        :type="{ 'is-danger': hasError }"
      >
        <b-input
          v-model="username"
          :placeholder="$t('login.id.placeholder')"
          required
        />
      </b-field>

      <b-field
        :label="$t('login.password.label')"
        :type="{ 'is-danger': hasError }"
      >
        <b-input
          v-model="password"
          type="password"
          password-reveal
          :placeholder="$t('login.password.placeholder')"
          required
        />
      </b-field>
      <a
        class="has-text-warning"
        @click="forgotPassword()"
      >{{ $t("login.forgot-pass") }}</a>
    </div>

    <div class="buttons">
      <b-button
        type="is-primary"
        native-type="submit"
        expanded
        class="button is-fullwidth is-large"
        :loading="isLoading"
        :disabled="!isValid()"
        @click="login()"
      >
        {{ $t("login.auth.label") }}
      </b-button>
    </div>
    <div class="buttons">
      <b-field :label="$t('login.no_account')" />
      <b-button
        expanded
        class="button is-fullwidth is-large"
        @click="registration()"
      >
        {{ $t("login.signup") }}
      </b-button>
    </div>
  </form>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import { ILogin, LoginDefault } from "../../../../modules/auth/interfaces";

export default {
  name: "AppLogin",
  data() {
    return LoginDefault();
  },
  computed: {
    ...mapGetters("auth", ["error", "hasError", "isLoading"])
  },
  methods: {
    login() {
      this.$emit("login", this.username, this.password);
    },
    forgotPassword() {
      this.$emit("passswordReset");
    },
    registration() {
      this.$emit("registration");
    },
    isValid() {
      return !!this.username && !!this.password;
    }
  }
};
</script>

<style lang="scss" scoped>
.inputs {
  margin-bottom: 0.5rem;
}
</style>
