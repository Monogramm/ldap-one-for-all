<template>
  <div>
    <div
      v-if="values.hasOwnProperty('jpegPhoto')"
      class="columns is-centered"
    >
      <div
        class="column is-three-fifths"
      >
        <!-- Display current image -->
        <div
          v-if="attributes.hasOwnProperty('jpegPhoto')"
          class="columns is-centered"
        >
          <b-image
            class="column is-5 box"
            :src="formattedJpegPhotoValue()"
          />
        </div>
        <div class="mt-4 columns mt-1 is-centered">
          <div class="column is-flex is-justify-content-center">
            <b-field>
              <b-upload
                drag-drop
                :disabled="isLoading"
                @input="droppedFile($event)"
              >
                <section class="section">
                  <div class="content has-text-centered">
                    <p>
                      <b-icon
                        icon="upload"
                        size="is-large"
                      />
                    </p>
                    <p>{{ $t("common.drag-and-drop") }}</p>
                  </div>
                </section>
              </b-upload>
            </b-field>
          </div>
        </div>
      </div>
    </div>
    <template
      v-for="(row, index) in attributes"
      class="container"
    >
      <template
        v-if="getAttributeType(index) != 'image'"
      > 
        <div
          :key="`divContainer:${index}`"
          class="column is-flex is-justify-content-center"
        >
          <div 
            class="column bb-1"
          >
            <div class="columns is-centered">
              <div class="column is-flex is-justify-content-center pl-0">
                <div class="column is-5 is-flex is-justify-content-flex-end">
                  <!-- Name of the table-->
                  <span class="is-size-4-desktop is-size-4-mobile is-size-5-tablet">
                    {{ index }}
                  </span>
                </div>

                <div class="column is-3">
                  <!-- Button for retrieving input-->
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
                class="column"
                :value="row"
                :is-loading="isLoading"
                :type="getAttributeType(index)"
              />
            </div>
          </div>
        </div>
      </template>
    </template>

    <div class="columns mx-4 mt-4 is mobile">
      <div class="column is-flex is-justify-content-center">
        <div class="column is-4">
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
          <div class="column is-flex is-justify-content-center pt-0">
            <b-button
              :title="$t('ldap.entries.new.attribute.add')"
              :disabled="isLoading || newAttributeKey===''"
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
    async droppedFile(file: File) {
      // TODO Control that file.type matches attribute type

      await this.toBase64(file,
        (reader: FileReader, result: string | ArrayBuffer) => {
          if(typeof(result)=="string") {
            if (result.startsWith('data:')) {
              result = result.substring(result.indexOf(',')+1);
            }
          }

          // Save base64 into attribute values
          this.values.jpegPhoto = [result];
        },
        (reader: FileReader, ev: ProgressEvent<FileReader>) => {
          reader.onerror = ()=> {
            this.$buefy.toast.open(
              {
                message:this.$t('common.error.upload-failure',{error: reader.error}),
                type: "is-danger",
                indefinite: true,
              }
            );
          };
        }
      );
    },
    toBase64(file: File, resolve: (reader: FileReader, result: string | ArrayBuffer) => any, reject: (reader: FileReader, ev: ProgressEvent<FileReader>) => any) {
      const reader = new FileReader();
      reader.readAsDataURL(file);

      if (!!resolve) {
        reader.onload = () => resolve(reader, reader.result);
      }
      if (!!reject) {
        reader.onerror = (error) => reject(reader, error);
      }
    },
    formattedJpegPhotoValue(): string {
      let formattedValue = this.values?.jpegPhoto.toString() ?? '';

      if (! formattedValue.startsWith('data:')) {
        formattedValue = 'data:image/png;base64,' + formattedValue;
      }

      return formattedValue;
    },
  }
};
</script>

<style lang="scss" scoped>
@import '../../../../../../styles/design-system';

.bb-1 {
  border-bottom: 1px solid $grey-darker;
}
</style>
