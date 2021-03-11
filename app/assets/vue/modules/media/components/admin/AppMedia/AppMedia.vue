<template>
  <div class="section">
    <h1 class="title is-1">
      {{ $t(isEdit ? "medias.edit" : "medias.create") }}
    </h1>

    <form
      class="box"
      @submit.prevent
    >
      <b-field :label="$t('medias.name')">
        <b-input
          v-model="media.name"
          maxlength="254"
          required
          :disabled="isLoading"
          @change="updateParent('name', $event.target.value)"
        />
      </b-field>

      <div class="column is-3">
        <figure
          v-if="media.filename"
          class="image is-medium"
        >
          <img :src="media.filename">
        </figure>
      </div>

      <b-field class="file">
        <b-upload
          v-model="file"
          class="file-label"
          :disabled="isLoading"
        >
          <a class="button is-light">
            <b-icon icon="upload" />
            <span>{{ $t("common.upload-file") }}</span>
          </a>
        </b-upload>
        <span
          v-if="file"
          class="file-name"
        >{{ file.name }}</span>
      </b-field>


      <b-field :label="$t('medias.description')">
        <b-input
          v-model="media.description"
          type="textarea"
          required
          :disabled="isLoading"
          @change="updateParent('description', $event.target.value)"
        />
      </b-field>

      <b-button
        type="is-primary"
        native-type="submit"
        :loading="isLoading"
        @click="submit"
      >
        {{ $t(isEdit ? 'common.edit' : 'common.create') }}
      </b-button>
    </form>
  </div>
</template>

<script lang="ts">
import { IMedia, Media } from "../../../interfaces/media";

export default {
  name: "AppMedia",
  props: {
    media: {
      type: Object,
      default: () => new Media()
    },
    isLoading: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      file: {},
    };
  },
  computed: {
    isEdit() {
      return !!this.media.id;
    }
  },
  methods: {
    updateParent(property: string, value: string) {
      this.$emit("updateParent", property, value);
    },
    submit() {
      this.$emit("submit", this.file);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
