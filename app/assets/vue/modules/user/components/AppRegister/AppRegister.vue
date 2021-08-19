<template>
  <form
    class="section"
    @submit.prevent
  >
    <b-field
      :label="$t('common.email.label')"
      :type="emailFieldType"
      :message="emailErrorMessage"
    >
      <b-input
        v-model="email"
        type="email"
        :placeholder="$t('common.email.placeholder')"
        :pattern="emailRegex.source"
        required
      />
    </b-field>

    <b-field
      :label="$t('common.username.label')"
      :type="usernameFieldType"
      :message="usernameErrorMessage"
    >
      <b-input
        v-model="username"
        :placeholder="$t('common.username.placeholder')"
        required
        :pattern="usernameRegex.source"
      />
    </b-field>

    <b-field
      :label="$t('common.password.label')"
      :type="passwordFieldType"
      :message="passwordErrorMessage"
    >
      <b-input
        v-model="password"
        type="password"
        password-reveal
        :placeholder="$t('common.password.placeholder')"
        required
      />
    </b-field>

    <b-field
      :label="$t('common.password.confirm')"
      :type="confirmFieldType"
      :message="confirmErrorMessage"
    >
      <b-input
        v-model="confirmPassword"
        type="password"
        :placeholder="$t('common.password.placeholder')"
        required
      />
    </b-field>
    <div class="buttons">
      <b-checkbox
        v-model="tos"
        required
      >
        {{ $t("signup.tos.label") }}
        <router-link to="terms-of-services">
          {{ $t("signup.tos.link") }}
        </router-link>
      </b-checkbox>
    </div>

    <div class="container">
      <div class="buttons">
        <b-button
          type="is-primary"
          native-type="submit"
          expanded
          class="button is-fullwidth is-large"
          :loading="isLoading"
          :disabled="!isValid()"
          @click="register"
        >
          {{ $t("signup.register") }}
        </b-button>
      </div>
    </div>
  </form>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

interface IRegisterData {
  username: string;
  email: string;
  password: string;
  confirmPassword: string;
  tos: Boolean;
  success: Boolean;
  emailRegex: RegExp,
  usernameRegex: RegExp,
}

const RegisterDataDefault = (): IRegisterData => {
  return {
    username: "",
    email: "",
    password: "",
    confirmPassword: "",
    tos: false,
    success: false,
    emailRegex: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
    usernameRegex: /^[A-Za-z0-9.-]+$/,
  };
};

export default {
  name: "AppRegister",
  data() {
    return RegisterDataDefault();
  },
  computed: {
    ...mapGetters("user", ["error", "hasError", "isLoading"]),
    validEmail() {
      return this.emailRegex.test(this.email);
    },
    emailErrorMessage() {
      return {
        [this.error.message]: this.error.code === 1001 || this.error.code === 1000,
        [this.$t("common.error.required-field-empty")]: !!!this.email,
        [this.$t("common.error.field-not-valid", {format: this.$t("common.email.format")})]: !!this.email && !this.validEmail,
      };
    },
    emailFieldType() {
      return {
        "is-danger":
          this.hasError && (this.error.code === 1001 ||this.error.code === 1000)
          || !!!this.email
          || !this.validEmail
          || this.error.status === 422
      };
    },
    validUsername() {
      return !!this.username
          && this.usernameRegex.test(this.username);
    },
    usernameErrorMessage() {
      return {
        [this.error.message]: this.error.code === 1002,
        [this.$t("common.error.required-field-empty")]: !!!this.username,
        [this.$t("common.error.field-not-valid", {format: this.$t("common.username.format")})]: !!this.username && !this.validUsername,
      };
    },
    usernameFieldType() {
      return {
        "is-danger":
            this.hasError && (this.error.code === 1002)
            || !!!this.username
            || !this.validUsername
            || this.error.status === 422
      };
    },
    isPasswordValid() {
      // TODO Check complexity
      return this.password.length >= 6;
    },  
    passwordFieldType() {
      return {
        "is-danger":
            this.hasError
            || !!!this.password
            || !this.isPasswordValid
            || this.error.status === 422
      };
    },
    passwordErrorMessage() {
      return {
        [this.$t("common.error.required-field-empty")]: !!!this.password,
        [this.$t("common.error.field-not-valid", {format: this.$t("common.password.format")})]: !!this.password && !this.isPasswordValid,
      };
    },
    isConfirmPasswordValid() {
      return this.password === this.confirmPassword;
    },
    confirmFieldType() {
      return {
        "is-danger":
            !!!this.confirmPassword ||
            !this.isConfirmPasswordValid
      };
    },
    confirmErrorMessage() {
      return {
        [this.$t("common.error.required-field-empty")]: !!!this.confirmPassword,
        [this.$t("common.password.confirm")]: !!this.confirmPassword && !this.isConfirmPasswordValid,
      };
    },
  },
  methods: {
    isValid() {
      return (
        this.validEmail &&
        this.validUsername &&
        this.isPasswordValid &&
        this.isConfirmPasswordValid &&
        this.tos === true
      );
    },
    register() {
      this.$emit(
        "register",
        this.email,
        this.username,
        this.password,
        this.confirmPassword,
        this.tos
      );
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
