<template>
  <section class="section error">
    <div class="content box has-text-centered">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
      <h3 class="title is-3">
        {{ subtitleLabel }}
      </h3>
    </div>
  </section>
</template>

<script lang="ts">
export default {
  name: 'Error',
  components: {},
  props: {},
  data(): any {
    return {};
  },
  computed: {
    titleLabel() {
      return this.$t("error.title");
    },
    subtitleLabel() {
      return this.$t("error.subtitle");
    }
  },
  metaInfo() {
    return {
      title: this.titleLabel,
      meta: [
        {
          name: 'robots',
          content: 'NOINDEX,NOFOLLOW',
        },
      ],
    };
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
</style>
