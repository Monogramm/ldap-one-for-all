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
      <b-navbar-item tag="div">
        <div
          v-if="authenticated"
          class="buttons is-centered"
        >
          <b-button
            type="is-primary"
            icon-left="stop-circle"
            :loading="signingOut"
            @click="logout"
          >
            <strong>{{ $t("logout.title") }}</strong>
          </b-button>
        </div>
        <div
          v-else
          class="buttons is-centered"
        >
          <b-button
            type="is-primary"
            icon-left="sign-in-alt"
            :disabled="signingUp"
            tag="router-link"
            to="/registration"
          >
            <strong>{{ $t("signup.title") }}</strong>
          </b-button>
          <b-button
            type="is-light"
            icon-left="user"
            :disabled="signingIn"
            tag="router-link"
            to="/login"
          >
            {{ $t("login.title") }}
          </b-button>
        </div>
      </b-navbar-item>
    </template>
  </b-navbar>
</template>

<script lang="ts">
export default {
  name: "AppTopBar",
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
