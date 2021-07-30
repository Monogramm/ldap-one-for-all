<template>
  <div>
    <template
      v-for="(row, index) in attributes"
      class="container"
    >
      <div
        :key="`divAttributesLoop:${index}`"
        class="box m-1 p-1"
      >
        <!-- Button for retrieving input-->
        <div class="columns mb-1 mt-1 is-mobile">
          <!-- Name of the table-->
          <div class="ml-5 is-align-items-center is-flex">
            <h1 class="title is-4 has-text-centered is-size-4-mobile">
              {{ index }}
            </h1>
          </div>
          <div class="column is-1">
            <b-button
              type="is-danger"
              icon-right="trash"
              :title="$t('ldap.entries.new.attribute.del-title')"
              :disabled="isLoading"
              @click="removeAttribute(index)"
            />
          </div>
        </div>
        <!-- Call AppLdapAttribute Component-->
        <app-ldap-attribute
          :key="`componentLdapattribute:${index}`"
          :value="row"
          :is-loading="isLoading"
          :type="getAttributeType(index)"
        />
      </div>
    </template>

    <div class="columns mx-4 mt-4 is mobile">
      <div class="column is-one-third is-two-fifths is-offset-one-quarter">
        <b-field
          :label="$t('ldap.entries.new.attribute.key-label')" 
        >
          <b-input
            v-model="newAttributeKey"
            :title="$t('ldap.entries.new.attribute.title-input')"
            :placeholder="$t('ldap.entries.new.attribute.key-placeholder')"
            :disabled="isLoading"
            type="text"
          />
        </b-field>

        <b-button
          :title="$t('ldap.entries.new.attribute.add')"
          :disabled="isLoading"
          @click="addAttribute()"
        >
          {{ $t('ldap.entries.new.attribute.add') }}
        </b-button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { PropType } from 'vue';

import { ILdapAttributes } from "../../../interfaces/entry";

import AppLdapAttribute from "../../admin/AppLdapAttribute/AppLdapAttribute.vue";

export default {
  name: "AppLdapAttributes",
  components: { AppLdapAttribute },
  props: {
    values : {
      type: Object as PropType<ILdapAttributes>,
      default: {},
    },
    isLoading: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      newAttributeKey: '',
      attributes: this.values,
    };
  },
  methods: {
    addAttribute() {
      // XXX Remove default empty string as first value when LDAP schema config available.
      if(!this.attributes.hasOwnProperty(this.newAttributeKey))
      {
        this.$set(this.attributes, this.newAttributeKey, ['']);
      } else {
        this.$buefy.toast.open(this.$t('common.error.already-exists'));
      }
    },
    removeAttribute(index: string) {
      this.$delete(this.attributes, index);
    },
    getAttributeType(key: string) {
      if (!!!key) {
        return null;
      }

      let type;
      const lowerKey = key.toLowerCase();
      switch (lowerKey) {
      case 'jpegphoto':
        type = 'image';
        break;

      case 'userpassword':
        type = 'password';
        break;

      default:
        type = 'text';
        break;
      }

      return type;
    },
  }
};
</script>

<style lang="scss" scoped>
</style>