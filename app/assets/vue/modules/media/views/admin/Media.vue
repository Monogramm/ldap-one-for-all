<template>
  <app-media
    v-if="media"
    :media="media"
    :is-loading="isLoading"
    @updateParent="onChildPropsChanged"
    @submit="onSubmit"
  />
</template>

<script lang="ts">
import { mapGetters } from "vuex";

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
      this.$store
        .dispatch("media/get", this.id)
        .then((response: IMedia) => {
          this.media = response;
        });
    } else {
      this.media = new Media();
    }
  },
  methods: {
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
            this.$router.replace({ name: "AdminMedias" });
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
            this.$router.replace({ name: "AdminMedias" });
          }
        });
    },
    onSubmit(file: any) {
      if (this.isEdit) {
        return this.editMedia(this.media, file);
      }

      return this.createMedia(this.media, file);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
