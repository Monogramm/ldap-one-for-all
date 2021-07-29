<template>
  <section class="">
    <div class="columns">
      <div class="column is-8">
        <section class="section box">
          <div class="mb-1 is-flex is-flex-direction-column is-justify-content-center">
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

          <div class="ldap-entry-div">
            <ldap-entry
              dn="cn=Hubert J. Farnsworth,ou=people,dc=planetexpress,dc=com"
            />
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
              type="iis-link"
              icon-left="edit"
              class="profile-actions"
              tag="router-link"
              :to="{ name: '' }"
            >
              {{ $t("home.modify-account") }}
            </b-button>
          </div>

          <div class="has-text-centered">
            <b-button
              :title="$t('common.new-feature')"
              disabled="true"
              type="iis-link"
              icon-left="edit"
              class="profile-actions"
              tag="router-link"
              :to="{ name: '' }"
            >
              {{ $t("home.modify-phrase") }}
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
import LdapEntry from "../modules/ldap/views/LdapEntry.vue";

export default {
  name: "Home",
  components: {
    LdapEntry
  },
  data() {
    return {
      data: [1,2,3,4],
    };
  },
  computed: {
    titleLabel() {
      return this.$t("home.title");
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

.ldap-entry-div {
  height: 50vh;
  overflow-y: scroll;
}
</style>
