<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
    </div>

    <app-contact-support
      :subject="subject"
      :message="message"
      :disabled="disabled"
      :is-loading="isLoading"
      @updateSubject="onSubjectUpdate"
      @updateMessage="onMessageUpdate"
      @send="onSend"
    />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

import AppContactSupport from "../components/AppContactSupport/AppContactSupport.vue";

export default {
  name: "ContactSupport",
  components: { AppContactSupport },
  props: {
    subject: {
      type: String,
      default: ""
    },
    message: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      disabled: false,
      secondsBeforeRedirect: 3
    };
  },
  computed: {
    ...mapGetters("support", ["isLoading"]),
    titleLabel() {
      return this.$t("support.contact.title");
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  },
  methods: {
    onSubjectUpdate(value: string) {
      this.subject = value;
    },
    onMessageUpdate(value: string) {
      this.message = value;
    },
    onSend() {
      this.$store
        .dispatch("support/sendRequestEmail", {
          subject: this.subject,
          message: this.message
        })
        .then(() => {
          this.$buefy.snackbar.open({
            message: this.$t("support.contact.email-sent-success", {
              seconds: this.secondsBeforeRedirect
            }),
            position: "is-top",
            type: "is-success"
          });
          this.disabled = true;
        })
        .then(() => {
          setTimeout(
            this.redirectToHomePage,
            this.secondsBeforeRedirect * 1000
          );
        })
        .catch(() => {
          this.$buefy.snackbar.open({
            message: this.$t("support.contact.email-sent-fail"),
            type: "is-danger"
          });
        });
    },
    redirectToHomePage() {
      this.$router.push({ name: "Home" });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
