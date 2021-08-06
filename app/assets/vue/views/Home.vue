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
            v-if="source === 'ldap' && edit === false"
          >
            <app-ldap-entry
              :dn="fullDn"
            />
          </div>
          <div v-else-if="edit === true">
            <app-ldap-entry-admin
              v-if="entry !== null"
              :ldap-entry="entry"
              :is-edit="true"
              :is-loading="isLoading"
              @submit="onSubmit"
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

          <template v-if="edit === false">
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
                type="is-link"
                icon-left="edit"
                class="profile-actions"
                @click="onEdit()"
              >
                {{ $t("home.modify-account") }}
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
          </template>
          <div v-else>
            <div class="has-text-centered">
              <b-button
                type="is-primary"
                icon-left="save"
                class="profile-actions contact"
                :loading="isLoading"
                @click="onSubmit()"
              >
                {{ $t("common.update") }}
              </b-button>
            </div>
            <div class="has-text-centered">
              <b-button
                type="is-danger"
                icon-left="exclamation-triangle"
                class="profile-actions contact mt-0"
                :loading="isLoading"
                @click="cancel()"
              >
                {{ $t("common.cancel") }}
              </b-button>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import AppLdapEntry from "../modules/ldap/components/AppLdapEntry/AppLdapEntry.vue";
import { LdapEntryDefault, ILdapEntry, LdapEntry } from '../modules/ldap/interfaces';
import { AxiosError } from 'axios';
import AppLdapEntryAdmin from '../modules/ldap/components/admin/AppLdapEntry/AppLdapEntry.vue';

export default {
  name: "Home",
  components: {
    AppLdapEntry,
    AppLdapEntryAdmin,
  },
  data() {
    return {
      fullDn: null as string,
      edit: false as boolean,
      entry: null as LdapEntry | null,
      source: null as String
    };
  },
  computed: {
    ...mapGetters("auth", ["authUser"]),
    ...mapGetters("ldapEntry", ["isLoading", "hasError", "error"]),
    titleLabel() {
      return this.$t("home.title");
    }
  },
  async created() {
    this.source = this.authUser?.metadata?.auth?.source ?? "unknown";

    if (this.source === 'ldap') {
      this.fullDn = this.authUser.metadata.ldap.fullDn;
    }

    if (this.fullDn) {
      await this.$store
        .dispatch("ldapEntry/get", this.fullDn).then((result: ILdapEntry) => {
          this.entry = result;
        });
    } else {
      this.entry = LdapEntryDefault();
    }
  },
  methods: {
    onEdit() {
      this.edit = true;
    },
    cancel() {
      this.edit = false;
    },
    async onSubmit() {
      await this.$store
        .dispatch("ldapEntry/update", this.entry)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.edit = false;
          } else {
            this.handleError(this.error)
          }
        })
    },
    handleError(error: AxiosError<string | number>) {
      this.$buefy.snackbar.open(
        {
          message: error.message,
          type: "is-danger",
          indefinite: true,
        }
      );
    },
    handleSuccess() {
      this.$buefy.toast.open(
        {
          duration: 2500,
          message:this.$t('common.success'),
          type: 'is-success'
        }
      );
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
