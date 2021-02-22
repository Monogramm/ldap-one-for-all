<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
      <h2 class="subtitle">
        {{ $t("install.subtitle") }}
      </h2>
    </div>

    <div class="box">
      <section class="section">
        <p>{{ $t("install.description") }}</p>
      </section>

      <section
        id="android"
        class="section"
      >
        <h2 class="title is-2">
          {{ $t("install.android.title") }}
        </h2>
        <p>{{ $t("install.android.description") }}</p>
        <b-button
          type="is-info is-hidden"
          icon-left="download"
          class="pwa-install-link"
        >
          <strong>{{ $t("install.link") }}</strong>
        </b-button>
      </section>

      <section
        id="desktop"
        class="section"
      >
        <h2 class="title is-2">
          {{ $t("install.chrome.title") }}
        </h2>
        <p>{{ $t("install.chrome.description") }}</p>
      </section>

      <section
        id="ios"
        class="section"
      >
        <h2 class="title is-2">
          {{ $t("install.ios.title") }}
        </h2>
        <p>{{ $t("install.ios.description") }}</p>
        <ol type="1">
          <li>
            {{ $t("install.ios.steps.share") }}
            <br>
            <img :src="require('../../images/ios_share.jpg')">
          </li>
          <li>
            {{ $t("install.ios.steps.home_screen") }}
            <br>
            <img :src="require('../../images/ios_a2hs_' + locale + '.jpg')">
          </li>
          <li>{{ $t("install.ios.steps.add") }}</li>
          <li>{{ $t("install.ios.steps.name") }}</li>
          <li>{{ $t("install.ios.steps.launch") }}</li>
        </ol>
      </section>
    </div>
  </section>
</template>

<script lang="ts">
import { i18n } from "../plugins/i18n/i18n";

export default {
  name: "Install",
  data(): any {
    return {
      locale: i18n.locale
    };
  },
  computed: {
    titleLabel() {
      return this.$t("install.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  }
};
</script>
