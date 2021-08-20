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
    <div>
      <template v-for="(attribute, key) in attributes">
        <template v-if="key !== 'jpegPhoto' && key !== 'userPassword'">
          <div
            :key="`divArrangement:${key}`"
            class="column"
          >
            <div
              :key="`divElemente:${key}`"
              class="columns ml-4 bb-1"
            >
              <div class="column is-5">
                <div class="columns mb-0 is-mobile is-centered">
                  <div class="column is-7 has-text-centered">
                    <span
                      :key="`spanEntryKey:${key}`"
                      :title="key"
                      class="is-size-4-desktop is-size-4-mobile is-size-5-tablet"
                    >
                      {{ $t("ldap.attributes." + key) }}
                    </span>
                  </div>
                </div>
              </div>

              <div class="column is-7">
                <template v-for="(value, indexValue) in attribute">
                  <div
                    :key="`divInputValue${indexValue}`"
                    class="mb-1"
                  >
                    <template>
                      <span
                        :key="`spanEntryValue:${value}`"
                        class="column is-full is-centered p-0 has-text-centered break-word"
                        :class="{'hidden-overflow': attributesHiddenState[key]}"
                        @click="displayValueHidden(key)"
                      >
                        {{ value }}
                      </span>
                    </template>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </template>
      </template>
    </div>
  </div>
</template>


<script lang="ts">
import { ILdapAttributes, ILdapEntry } from "../../interfaces/entry";

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
      attributes: null as ILdapAttributes,
      attributesHiddenState: {} as ILdapAttributes,
    };
  },
  async created() {
    if (this.dn) {
      await this.$store
        .dispatch("ldapEntry/get", this.dn)
        .then((result: ILdapEntry) => {
          this.attributes = result.attributes;
          Object.keys(this.attributes).forEach(key => {
            this.$set(this.attributesHiddenState, key, true);
          });
        })
    } else {
      this.attributes = null;
    }
  },
  methods: {
    formattedValue(): string {
      let formattedValue = this.attributes?.jpegPhoto.toString() ?? "";

      if (!formattedValue.startsWith("data:")) {
        formattedValue = "data:image/png;base64," + formattedValue;
      }

      return formattedValue;
    },
    displayTitle(): string {
      let title = null;

      if (this.attributes.hasOwnProperty("cn")) {
        title = this.attributes.cn.toString();
      } else if (this.attributes.hasOwnProperty("displayName")) {
        title = this.attributes.displayName.toString();
      } else {
        title = this.dn;
      }

      return title;
    },
    displayValueHidden(key: string): void {
      this.attributesHiddenState[key] = !this.attributesHiddenState[key]; 
    },
  }
};
</script>

<style lang="scss">
@import '../../../../../styles/design-system';

.bb-1 {
  border-bottom: 1px solid $grey-darker;
}

.break-word {
  cursor: pointer;
  word-wrap: break-word;
}

.hidden-overflow {
  cursor: pointer;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>
