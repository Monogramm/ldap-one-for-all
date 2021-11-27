<template>
  <div class="section">
    <h1 class="title is-1">
      {{ $t(isEdit ? "medias.edit" : "medias.create") }}
    </h1>

    <b-loading
      :is-full-page="isFullPage"
      :active.sync="isLoading"
    />

    <app-media
      v-if="media"
      :media="media"
      :error="error"
      :is-loading="isLoading"
      @updateParent="onChildPropsChanged"
      @submit="onSubmit"
    />
  </div>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import { AxiosError } from 'axios';

import { IError } from '../../../../interfaces/error';

import { IMediaPayload } from '../../actions';
import { IMedia, Media } from '../../interfaces/media';

import AppMedia from "../../components/admin/AppMedia/AppMedia.vue";

export default {
  name: "Media",
  components: { AppMedia },
  props: {
    id: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      isFullPage: true,
      types: [] as string[],
      media: null as IMedia,
    };
  },
  computed: {
    // TODO Add types to media getters
    ...mapGetters("media", ["isLoading", "item", "hasError", "error"]),
    isEdit() {
      return !!this.id;
    }
  },
  created() {
    if (this.id) {
      this.load();
    } else {
      this.media = new Media();
    }
  },
  methods: {
    load() {
      this.$store
        .dispatch("media/get", this.id)
        .then((response: IMedia) => {
          this.media = response;
        });
    },
    onChildPropsChanged(property: string, value: string) {
      this.item[property] = value;
    },
    async editMedia(media: IMedia, file: any) {
      let payload: IMediaPayload = {
        media: media,
        file: file,
      };
      await this.$store
        .dispatch("media/updateMedia", payload)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminMedias" });
          } else {
            this.handleError(this.error);
          }
        });
    },
    async createMedia(media: IMedia, file: any) {
      let payload: IMediaPayload = {
        media: media,
        file: file,
      };
      await this.$store
        .dispatch("media/createMedia", payload)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminMedias" });
          } else {
            this.handleError(this.error);
          }
        });
    },
    onSubmit(file: any) {
      if (this.isEdit) {
        return this.editMedia(this.media, file);
      }

      return this.createMedia(this.media, file);
    },
    handleError(error: AxiosError<IError>) {
      var message = null;
      const serverError = error?.response?.data;
      if (!!serverError && !!serverError.message) {
        message = serverError.message;
      } else {
        message = error.message;
      }
      if (!!!message) {
        message = this.$t("common.fatal.unexpected");
      }

      this.$buefy.snackbar.open(
        {
          message: message,
          type: "is-danger",
          indefinite: true,
        }
      );
    },
    handleSuccess() {
      this.$buefy.toast.open(
        {
          duration: 2500,
          message: this.$t("common.success"),
          type: "is-success"
        }
      );
    },
  }
};
</script>

<style lang="scss" scoped>
</style>
