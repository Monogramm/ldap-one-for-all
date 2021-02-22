<template>
  <section class="section">
    <div class="level">
      <div class="level-item">
        <figure class="image is-128x128">
          <img
            :src="require('../../images/app_logo.png')"
            alt="LDAP All-For-One Manager Logo"
          >
        </figure>
      </div>
      <div class="level-item">
        <div>
          <h1 class="title is-1">
            {{ $t("app.core.name") }}
          </h1>
          <h2 class="subtitle is-2">
            {{ $t("app.core.slogan") }}
          </h2>
        </div>
      </div>
    </div>

    <div class="content">
      <p>{{ $t("app.core.description") }}</p>
    </div>

    <!-- TODO -->
  </section>
</template>

<script lang="ts">
import { i18n } from "../plugins/i18n/i18n";

export default {
  name: "Index",
  components: {
  },
  data() {
    return {
      isFullPage: true,
      locale: i18n.locale,
      appUrl: process.env.APP_PUBLIC_URL,
      websiteUrl: process.env.WEBSITE_PUBLIC_URL
    };
  },
  computed: {
    nameLabel() {
      return this.$t("app.core.name");
    },
    sloganLabel() {
      return this.$t("app.core.slogan");
    },
    descriptionLabel() {
      return this.nameLabel + " - " + this.sloganLabel;
    },
    keywords() {
      return this.$t("app.core.keywords");
    }
  },
  metaInfo() {
    return {
      title: this.sloganLabel,
      meta: [
        {
          name: "description",
          content: this.descriptionLabel
        },
        {
          name: "robots",
          content: "INDEX,FOLLOW"
        },
        {
          name: "keywords",
          content: this.keywords
        },

        { name: "og:url", content: this.appUrl },
        { name: "og:site_name", content: "app" },
        { name: "og:type", content: "website" },
        { name: "og:locale", content: this.locale },
        {
          name: "og:title",
          content: this.descriptionLabel
        },
        {
          name: "og:description",
          content: this.nameLabel
        },
        {
          name: "og:image:url",
          content: this.appUrl + "/app_logo.png"
        },

        { name: "twitter:card", content: "summary" },
        { name: "twitter:site", content: "@appcloud" },
        { name: "twitter:creator", content: "@appcloud" },
        {
          name: "twitter:title",
          content: this.sloganLabel
        },
        { name: "twitter:url", content: this.appUrl },
        {
          name: "twitter:image",
          content: this.appUrl + "/app_logo.png"
        },
        {
          name: "twitter:description",
          content: this.descriptionLabel
        }
      ]
    };
  }
};
</script>

<style lang="scss" scoped>
</style>
