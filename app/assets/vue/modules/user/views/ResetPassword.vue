<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
    </div>

    <b-loading
      :is-full-page="isFullPage"
      :active.sync="isLoading"
    />
    <div class="box">
      <b-message
        type="is-success"
        :active="isSent"
      >
        {{ $t("password-reset.sent") }}
      </b-message>
      <b-field :label="$t('password-reset.email')">
        <b-input
          v-model="email"
          type="email"
          required
        />
      </b-field>
      <b-button
        type="is-primary"
        :disabled="isSent === true"
        expanded
        @click="sendEmailToResetPassword"
      >
        {{ $t("password-reset.reset") }}
      </b-button>
    </div>
  </section>
</template>

<script lang="ts">
import axios from "axios";

export default {
  name: "ResetPassword",
  data() {
    return {
      email: "",
      isSent: false,
      isLoading: false,
      isFullPage: true
    };
  },
  computed: {
    titleLabel() {
      return this.$t("password-reset.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  },
  methods: {
    sendEmailToResetPassword() {
      this.isLoading = true;
      axios.post("/api/password/reset", { email: this.email }).then(() => {
        this.isSent = true;
        this.isLoading = false;
        new Promise(r => setTimeout(r, 3000)).then(() => {
          this.$router.push("/login");
        });
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>