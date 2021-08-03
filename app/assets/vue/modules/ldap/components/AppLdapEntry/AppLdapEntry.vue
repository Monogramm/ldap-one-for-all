<template>
  <div v-if="attributes !== null">
    <div class="columns mt-4 mb-5 is-centered">
      <h2 class="is-size-3">
        {{ displayTitle() }}
      </h2>
    </div>
    <div
      v-if="attributes.hasOwnProperty('jpegPhoto')"
      class="columns is-centered"
    >
      <b-image
        class="column is-3 box"
        :src="formattedValue()"
      />
    </div>
    <template v-for="(attribute, index) in attributes">
      <template v-if="index !== 'jpegPhoto' && index !== 'userPassword'">
        <div
          :key="`divArrangement:${index}`"
          class="column"
        >
          <div
            :key="`divElemente:${index}`"
            class="columns ml-4 bb-1"
          >
            <div class="column is-5">
              <span
                :key="`spanEntryKey:${index}`"
                class="is-size-4-desktop is-offset-3 column"
              >
                {{ $t('ldap.attributes.'+index) }}
              </span>
            </div>
            <div class="column mt-4 mt-1 mb-2">
              <template v-for="value in attribute">
                <div
                  :key="`divEntryValue:${value}`"
                  :title="index"
                  class="is-flex is-justify-content-center mb-1"
                >
                  <span
                    :key="`spanEntryValue:${value}`"
                    class="is-centered"
                  > 
                    {{ value }} 
                  </span>
                </div>
              </template>
            </div>
          </div>
        </div>
      </template>
    </template>
  </div>
</template>


<script lang="ts">
import { PropType } from 'vue';

import { LdapEntry, ILdapAttributes, ILdapEntry} from "../../interfaces/entry";

export default {
  name: "AppLdapEntry",
  props: {
    dn: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      entry: null as LdapEntry | null,
      attributes: null as PropType<ILdapAttributes>
    };
  },
  async created() {
    if (this.dn) {
      await this.$store
        .dispatch("ldapEntry/get", this.dn).then((result: ILdapEntry) => {
          this.attributes = result.attributes;
        });
    } else {
      this.attributes = null;
    }
  },
  methods: {
    formattedValue(): string {
      let formattedValue =  'data:image/png;base64,' + this.attributes.jpegPhoto;

      return formattedValue;
    },
    displayTitle(): string {
      let title = null;

      if (this.attributes.hasOwnProperty('cn')) {
        title = this.attributes.cn.toString();
      } else if (this.attributes.hasOwnProperty('displayName')) {
        title = this.attributes.displayName.toString();
      } else {
        title = this.dn;
      }

      return title;
    }
  }
}
</script>

<style lang="scss">
@import '../../../../../styles/design-system';

.bb-1 {
  border-bottom: 1px solid $grey-darker;
}
</style>
