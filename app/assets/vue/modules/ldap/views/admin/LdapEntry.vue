<template>
  <app-ldap-entry
    v-if="entry !== null"
    :ldap-entry="entry"
    :is-loading="isLoading"
    :is-edit="isEdit"
    @submit="onSubmit"
  />
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import { ILdapEntry, LdapEntry, LdapEntryDefault } from "../../interfaces/entry";

import AppLdapEntry from "../../components/admin/AppLdapEntry/AppLdapEntry.vue";
import { AxiosError } from 'axios';

export default {
  name: "LdapEntry",
  components: { AppLdapEntry },
  props: {
    dn: {
      type: String,
      default: ""
    },
    clone: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      entry: null as LdapEntry | null
    };
  },
  computed: {
    // TODO Add types to ldapEntry getters
    ...mapGetters("ldapEntry", ["isLoading", "item", "hasError", "error"]),
    isEdit() {
      return !!this.dn && !this.clone;
    }
  },
  async created() {
    if (this.dn) {
      await this.$store
        .dispatch("ldapEntry/get", this.dn).then((result: ILdapEntry) => {
          this.entry = result;
        });
    } else {
      this.entry = LdapEntryDefault();
    }
  },
  methods: {
    async editLdapEntry(dn: string, ldapEntry: ILdapEntry) {
      // TODO Call a rename if dn different from ldapEntry.dn
      await this.$store
        .dispatch("ldapEntry/update", ldapEntry)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminLdapEntries" });
          } else {
            this.handleError(this.error)
          }
        })
    },
    async createLdapEntry(ldapEntry: ILdapEntry) {
      await this.$store
        .dispatch("ldapEntry/create", ldapEntry)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminLdapEntries" });
          } else {
            this.handleError(this.error)
          }
        })
    },
    onSubmit() {
      if (this.isEdit) {
        return this.editLdapEntry(this.dn, this.entry);
      }

      return this.createLdapEntry(this.entry);
    },
    handleError(error: AxiosError) {
      // TODO if possible, set error type
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
  }
};
</script>

<style lang="scss" scoped>
</style>