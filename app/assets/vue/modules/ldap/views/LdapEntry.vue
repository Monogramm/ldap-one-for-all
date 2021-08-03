<template>
  <div class="box m-2">
    <app-ldap-entry
      :dn="dn"
    />
  </div>
</template>

<script lang="ts">
import AppLdapEntry from "../components/AppLdapEntry/AppLdapEntry.vue";

export default {
  name: "LdapEntry",
  components: {
    AppLdapEntry
  },
  props: {
    dn: {
      type: String,
      default: ""
    }
  }
}
</script>

<style lang="scss">
</style>
