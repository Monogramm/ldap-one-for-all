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
      <template
        v-for="(attribute, key) in attributes"
      >
        <template v-if="key !== 'jpegPhoto' && key !== 'userPassword'">
          <div
            :key="`divArrangement:${key}`"
            class="column"
          >
            <div
              :key="`divElemente:${key}`"
              class="columns ml-4 bb-1"
            >
              <div class="column is-6">
                <div class="columns mb-0 is-mobile">
                  <div class="column is-7">
                    <span
                      :key="`spanEntryKey:${key}`"
                      class="is-size-4-desktop "
                    >
                      {{ $t('ldap.attributes.'+key) }}
                    </span>
                  </div>
                  <div
                    v-if="isEdit === true"
                    class="column is-flex is-justify-content-center pl-0"
                  >
                    <!-- Section for the input create/delete-->
                    <div class="mr-1">
                      <b-button
                        icon-right="plus"
                        :disabled="isLoading"
                        @click="addValue(attribute)"
                      />
                    </div>
                    <div>
                      <b-button
                        type="is-danger"
                        icon-right="trash"
                        :disabled="isLoading"
                        @click="removeAttribute(key)"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="column mt-4 mt-1 mb-2"
              >
                <template v-for="(value, indexValue) in attribute">
                  <div
                    :key="`divInputValue`+indexValue"
                    :title="key"
                    class="is-flex is-justify-content-center mb-1"
                  >
                    <template v-if="isEdit !== true">
                      <span
                        :key="`spanEntryValue:${value}`"
                        class="is-centered"
                      > 
                        {{ value }} 
                      </span>
                    </template>

                    <template v-else>
                      <div
                        :key="`divColumns:${key}`"
                        class="columns mb-0 is-mobile"
                      >
                        <div
                          :key="`divValueInput:${key}`"
                          class="column"
                        >
                          <!-- Input value attribute-->
                          <b-input
                            :key="indexValue"
                            v-model="attribute[indexValue]"
                            type="text"
                            :disabled="isLoading"
                          />
                        </div>

                        <div
                          :key="`divDeleteButton:${key}`"
                          class="column is-2 pl-0"
                        >
                          <!-- Section for the input create/delete-->
                          <b-button
                            :key="`buttonRemoveValue:${key}`"
                            type="is-danger"
                            icon-right="trash"
                            :disabled="isLoading"
                            @click="removeValue(key,indexValue)"
                          />
                        </div>
                      </div>
                    </template>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </template>
      </template>
      <div
        v-if="isEdit !== false"
        class="column is-flex is-justify-content-center"
      >
        <div class="column is-one-third is-two-fifths">
          <b-field
            :label="$t('ldap.entries.new.attribute.key-label')" 
          >
            <b-input
              v-model="newAttributeKey"
              type="text"
            />
          </b-field>
          <div class="is-flex is-flex-direction-column is-justify-content-center">
            <b-button
              :disabled="isLoading"
              @click="addAttribute()"
            >
              {{ $t('ldap.entries.new.attribute.add') }}
            </b-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script lang="ts">
import { PropType } from 'vue';

import { ILdapAttributes, ILdapEntry } from "../../interfaces/entry";

export default {
  name: "AppLdapEntry",
  props: {
    dn: {
      type: String,
      default: ""
    },
    isEdit: {
      type: Boolean,
      default: false
    },
    entry: {
      type: Object as PropType<ILdapEntry>,
      default: {},
    },    
    isLoading: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      newAttributeKey: null as String,
      ldapEntry: this.entry as ILdapEntry,
      attributes: null as ILdapAttributes,
      fullDn: this.dn
    };
  },
  async created() {
    if (this.dn) {
      await this.$store
        .dispatch("ldapEntry/get", this.dn).then((result: ILdapEntry) => {
          this.attributes = result.attributes;
          this.ldapEntry.dn = this.fullDn;
          this.ldapEntry.attributes = this.attributes;
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
    addAttribute() {

      if(!this.attributes.hasOwnProperty(this.newAttributeKey)) {
        this.$set(this.attributes, this.newAttributeKey, ['']);
      } else {
        this.$buefy.toast.open(this.$t('common.error.already-exists'));
      }
    },
    removeAttribute(index: string) {
      this.$delete(this.attributes, index);
    },
    addValue(target: Array<String>) {
      target.push('');
    },
    removeValue(key: string, index: number) {
      this.attributes[key].splice(index, 1);
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
