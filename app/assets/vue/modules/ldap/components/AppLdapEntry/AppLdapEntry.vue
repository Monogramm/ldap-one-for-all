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
                    <div class="columns mb-0 is-mobile">
                      <div class="column is-9">
                        <span
                          :key="`spanEntryValue:${value}`"
                          class="column is-full is-centered p-0 has-text-centered break-word"
                        >
                          {{ value | shorten(attributesHiddenState[key] ? shortenSize : -1) }}
                        </span>
                      </div>
                      <div class="column is-3 p-0 is-flex is-justify-content-center">
                        <b-button
                          v-if="!!value && value.length > shortenSize"
                          size="is-small"
                          :icon-left="!!attributesHiddenState[key] ? 'eye-slash' : 'eye'"
                          class="is-cursor-pointer"
                          @click="displayValueHidden(key)"
                        >
                          {{ $t("common.view") }}
                        </b-button>
                      </div>
                    </div>
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
import { truncate } from "../../../../common/helpers";
import { ILdapAttributes, ILdapEntry } from "../../interfaces/entry";

interface ILdapAttributesHiddenState {
  [attribute: string]: boolean;
}

export default {
  name: "AppLdapEntry",
  filters: {
    shorten(value: string | null, length: number = 20) {
      return length > 0 ? truncate(value, length) : value;
    },
  },
  props: {
    dn: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      shortenSize: 15,
      attributes: null as ILdapAttributes,
      attributesHiddenState: {} as ILdapAttributesHiddenState,
    };
  },
  async created() {
    if (this.dn) {
      await this.$store
        .dispatch("ldapEntry/get", this.dn)
        .then((result: ILdapEntry) => {
          Object.keys(result.attributes).forEach(key => {
            this.$set(this.attributesHiddenState, key, true);
          });
          this.attributes = result.attributes;
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

.icon.is-cursor-pointer {
  cursor: pointer;
}

.break-word {
  word-wrap: break-word;
}
</style>
