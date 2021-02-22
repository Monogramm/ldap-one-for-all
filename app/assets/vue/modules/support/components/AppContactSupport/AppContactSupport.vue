<template>
  <div class="box">
    <b-field :label="$t('support.contact.subject')">
      <b-input
        :value="subject"
        :disabled="disabled"
        @input.native.stop="updateSubject($event)"
      />
    </b-field>

    <b-field :label="$t('support.contact.message')">
      <b-input
        :value="message"
        type="textarea"
        :disabled="disabled"
        @input.native.stop="updateMessage($event)"
      />
    </b-field>

    <b-button
      :label="$t('common.send')"
      :loading="isLoading"
      type="is-primary"
      :disabled="disabled"
      @click="send"
    />
  </div>
</template>

<script lang="ts">
export default {
  name: "AppContactSupport",
  props: {
    subject: {
      type: String,
      default: ""
    },
    message: {
      type: String,
      default: ""
    },
    isLoading: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  computed: {},
  methods: {
    updateSubject(event: any) {
      this.$emit("updateSubject", event.target.value);
    },
    updateMessage(event: any) {
      // eslint-disable-next-line no-console
      console.log(event);
      this.$emit("updateMessage", event.target.value);
    },
    send() {
      this.$emit("send");
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
