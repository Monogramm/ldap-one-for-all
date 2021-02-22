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
      />
    </b-field>

    <b-field
      :label="$t('common.password.label')"
      :type="passwordFieldType"
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
    >
      <b-input
        v-model="confirmPassword"
        type="password"
        :placeholder="$t('common.password.placeholder')"
        :message="confirmErrorMessage"
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
}

const RegisterDataDefault = (): IRegisterData => {
  return {
    username: "",
    email: "",
    password: "",
    confirmPassword: "",
    tos: false,
    success: false
  };
};

export default {
  name: "AppRegister",
  data() {
    return RegisterDataDefault();
  },
  computed: {
    ...mapGetters("user", ["error", "hasError", "isLoading"]),
    emailErrorMessage: {
      get(): any {
        return {
          [this.error.message]: this.error.code === 1001,
          ["Email is incorrect"]:
            this.username && this.password && this.error.status === 422
        };
      },
      set(value: any) {
        return value;
      }
    },
    emailFieldType: {
      get(): any {
        return {
          "is-danger":
            this.hasError &&
            (this.error.code === 1001 ||
              (!this.validEmail(this.email) && this.error.status === 422))
        };
      },
      set(value: any) {
        return value;
      }
    },
    usernameErrorMessage: {
      get(): any {
        return {
          [this.error.message]: this.error.code === 1002
        };
      },
      set(value: any) {
        return value;
      }
    },
    usernameFieldType: {
      get(): any {
        return {
          "is-danger":
            this.hasError &&
            (this.error.code === 1002 ||
              (!this.username && this.error.status === 422))
        };
      },
      set(value: any) {
        return value;
      }
    },
    passwordFieldType: {
      get(): any {
        return {
          "is-danger":
            this.hasError && !this.password && this.error.status === 422
        };
      },
      set(value: any) {
        return value;
      }
    },
    confirmFieldType: {
      get(): any {
        return {
          "is-danger":
            !this.isConfirmPasswordValid() ||
            (this.hasError && !this.password && this.error.status === 422)
        };
      },
      set(value: any) {
        return value;
      }
    },
    confirmErrorMessage: {
      get(): any {
        return {
          [this.$t("common.password.confirm")]:
            !this.isConfirmPasswordValid() ||
            (this.hasError && !this.password && this.error.status === 422)
        };
      },
      set(value: any) {
        return value;
      }
    }
  },
  methods: {
    isConfirmPasswordValid() {
      return this.password === this.confirmPassword;
    },
    validEmail(email: string) {
      const re = new RegExp(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
      return re.test(email);
    },
    isValid() {
      return (
        !!this.email &&
        !!this.username &&
        !!this.password &&
        this.isConfirmPasswordValid() &&
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
