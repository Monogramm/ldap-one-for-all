<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
    </div>

    <div class="columns">
      <div class="column is-8">
        <section class="section box">
          <h2 class="subtitle">
            {{ $t("home.welcome") }}
          </h2>

          <!-- TODO -->
        </section>
      </div>

      <div class="column">
        <section class="section box is-centered">
          <div class="has-text-centered">
            <h2 class="subtitle">
              {{ $t("home.profile.manage") }}
            </h2>
            <p>{{ $t("home.profile.description") }}</p>
          </div>

          <div class="has-text-centered">
            <b-button
              type="is-primary"
              icon-left="magic"
              class="profile-actions"
              tag="router-link"
              to="/profile"
            >
              {{ $t("home.account") }}
            </b-button>
          </div>

          <div class="has-text-centered">
            <b-button
              icon-left="envelope"
              type="is-info"
              class="profile-actions contact"
              tag="router-link"
              to="/contact"
            >
              {{ $t("home.contact") }}
            </b-button>
          </div>
        </section>
      </div>
    </div>
  </section>
</template>

<script lang="ts">

export default {
  name: "Home",
  components: {
  },
  computed: {
    titleLabel() {
      return this.$t("home.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  }
};
</script>

<style lang="scss" scoped>
@import '../../styles/design-system';

.profile-actions {
  margin: 0.25em 0;
  width: 100%;
}

.contact {
  margin-top: 15%;
}
</style>
