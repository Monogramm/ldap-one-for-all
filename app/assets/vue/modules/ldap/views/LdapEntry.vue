<template>
  <div
    v-if="entry !== null"
    class="box m-2"
  >
    <div class="columns mt-4 mb-5 is-centered">
      <h2 class="is-size-3">
        {{ displayTitle() }}
      </h2>
    </div>
    <div
      v-if="entry.attributes.hasOwnProperty('jpegPhoto')"
      class="columns is-centered"
    >
      <b-image
        class="column is-3 box"
        :src="formattedValue()"
      />
    </div>
    <template v-for="(attribute, index) in entry.attributes">
      <template v-if="index !== 'jpegPhoto' && index !== 'userPassword'">
        <div
          :key="`divArrangement:${index}`"
          class="column is-two-thirds is-offset-2"
        >
          <div
            :key="`divElemente:${index}`"
            class="columns ml-4 bb-1"
          >
            <div class="column is-5">
              <span
                :key="`spanEntryKey:${index}`"
                class="is-size-4 is-offset-3 column"
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
import { mapGetters } from "vuex";
import { ILdapEntry, LdapEntry, LdapEntryDefault } from "../interfaces/entry";

export default {
  name: "LdapEntry",
  props: {
    dn: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      entry: null as LdapEntry | null
    };
  },
  computed: {
    ...mapGetters("ldapEntry", ["hasError", "error"]),
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
    formattedValue(): string {
      let formattedValue =  'data:image/png;base64,' + this.entry.attributes.jpegPhoto;

      return formattedValue;
    },
    displayTitle(): string {
      let title = null;

      if (this.entry.attributes.hasOwnProperty('cn')) {
        title = this.entry.attributes.cn.toString();
      } else if (this.entry.attributes.hasOwnProperty('displayName')) {
        title = this.entry.attributes.displayName.toString();
      } else {
        title = this.dn;
      }

      return title;
    }
  }
}
</script>

<style lang="scss">
@import '../../../../styles/design-system';

.bb-1 {
  border-bottom: 1px solid $grey-darker;
}
</style>
