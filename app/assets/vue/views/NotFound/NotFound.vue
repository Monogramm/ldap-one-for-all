<template>
  <section class="section notFound">
    <div class="content box has-text-centered">
      <h1 class="title is-1">
        404 - {{ titleLabel }}
      </h1>
      <h3 class="title is-3">
        {{ subtitleLabel }}
      </h3>
    </div>
  </section>
</template>

<script lang="ts">
export default {
  name: 'NotFound',
  components: {},
  props: {},
  data(): any {
    return {};
  },
  computed: {
    titleLabel() {
      return this.$t("not-found.title");
    },
    subtitleLabel() {
      return this.$t("not-found.subtitle");
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
