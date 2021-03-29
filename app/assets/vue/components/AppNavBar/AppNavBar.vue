<template>
  <b-navbar
    fixed-top
    shadow
    type="is-black"
    class="app-nav-bar no-print"
  >
    <template slot="brand">
      <b-navbar-item
        tag="router-link"
        :to="{ name: 'Index' }"
      >
        <img
          :src="require('../../../images/app_logo.png')"
          :alt="$t('app.core.name')"
        >
        {{ $t("app.core.name") }}
      </b-navbar-item>
    </template>

    <template slot="start">
      <!--                          LINK PAGE                       -->
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
      <!--SELECT LANGUAGE DROPDOWN -->
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
    </template>

    <template slot="end">
      <!--                          INPUT SEARCH LDAP USER                       -->
      <b-navbar-item tag="div">
        <div class="is-centered">
          <b-field
            position="is-centered"
            class="serch_input"
          >
            <b-input 
              placeholder="Search..."
            />
            <div
              class="cont_separation"
            >
              <div />
            </div>
            <b-button
              type="is-white"
              icon-left="search"
              class="button_search"
            />
          </b-field>
        </div>
        <!--                          USER SHOW INFORMATION                      -->
        <b-navbar-item tag="div">
          <div
            v-if="authenticated"
            class="buttons is-centered"
          >
            <div class="user_info">
              <h1>{{ authUser.username }}</h1>
            </div>
          </div>
        </b-navbar-item>
        <!--                          INPUT REGISTRATION/LOGIN/LOGOUT                       -->
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
              type="is-link"
              :disabled="signingUp"
              tag="router-link"
              to="/registration"
            >
              <strong>{{ $t("signup.title") }}</strong>
            </b-button>
            <b-button 
              type="is-success"
              icon-left="user"
              :disabled="signingIn"
              tag="router-link"
              to="/login"
            >
              {{ $t("login.title") }}
            </b-button>
          </div>
        </b-navbar-item>
        <!--                          PWA INSTALL BUTTON                       -->
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
      </b-navbar-item>
    </template>
  </b-navbar>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
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
  computed: {
    ...mapGetters("auth", ["authUser"]),
    titleLabel() {
      return this.$t("profile.welcome");
    }
  },
  methods: {
    logout() {
      console.log("deco triger")
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
.user_info
{
  color: white;
}
.serch_input
{
  background-color: white;
  border-radius: 0;
}
.cont_separation
{
  width: 1%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  justify-items: center;
}
.cont_sepration div
{
  height: 90%;
  box-shadow: 0px 0px 23px -7px rgba(0,0,0,0.75);
-webkit-box-shadow: 0px 0px 23px -7px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 0px 23px -7px rgba(0,0,0,0.75);
}
</style>
