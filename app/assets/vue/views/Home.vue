<template>
  <section>
    <div class="columns">
      <div class="column is-8">
        <section class="section box">
          <div class="mb-2 is-flex is-flex-direction-column is-justify-content-center">
            <div class="mb-1">
              <h1 class="title is-2">
                {{ titleLabel }}
              </h1>
            </div>

            <div>
              <h2 class="subtitle">
                {{ $t("home.welcome") }}
              </h2>
            </div>
          </div>

          <div
            v-if="getAuthUserSource() === 'ldap'"
          >
            <app-ldap-entry
              :dn="fullDn"
            />
          </div>
          <div
            v-else
            class="card-content"
          >
            <span>{{ $t("home.not-ldap-user") }}</span>
          </div>
        </section>
      </div>

      <div class="column">
        <section class="section box is-centered">
          <div class="has-text-centered">
            <h2 class="subtitle">
              {{ $t("home.profile.manage") }}
            </h2>
            <p>{{ $t("home.profile.description") }}</p>
          </div>

          <div class="has-text-centered">
            <b-button
              type="is-primary"
              icon-left="magic"
              class="profile-actions"
              tag="router-link"
              :to="{ name: 'UserProfile' }"
            >
              {{ $t("home.account") }}
            </b-button>
          </div>

          <div class="has-text-centered">
            <b-button
              :title="$t('common.coming-soon')"
              disabled="true"
              type="is-link"
              icon-left="edit"
              class="profile-actions"
              tag="router-link"
            >
              {{ $t("home.modify-secret") }}
            </b-button>
          </div>

          <div class="has-text-centered">
            <b-button
              icon-left="envelope"
              type="is-info"
              class="profile-actions contact"
              tag="router-link"
              :to="{ name: 'ContactSupport' }"
            >
              {{ $t("home.contact") }}
            </b-button>
          </div>
        </section>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import AppLdapEntry from "../modules/ldap/components/AppLdapEntry/AppLdapEntry.vue";

export default {
  name: "Home",
  components: {
    AppLdapEntry
  },
  data() {
    return {
      fullDn: null as string
    };
  },
  computed: {
    ...mapGetters("auth", ["authUser"]),
    titleLabel() {
      return this.$t("home.title");
    }
  },
  methods: {
    getAuthUserSource() {
      let source = this.authUser.metadata.auth.source;
      if (source === 'ldap') {
        this.fullDn = this.authUser.metadata.ldap.fullDn;
      }
      return source;
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  }
};
</script>

<style lang="scss" scoped>
@import '../../styles/design-system';

.profile-actions {
  margin: 0.25em 0;
  width: 100%;
}

.contact {
  margin-top: 15%;
}

</style>
