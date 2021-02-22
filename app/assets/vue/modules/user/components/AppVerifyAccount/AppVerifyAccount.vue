<template>
  <form
    class="box"
    @submit.prevent
  >
    <b-field
      :label="$t('component.verification.code')"
      :message="codeMessage"
      :type="messageType"
    >
      <b-input
        v-model="code"
        required
      />
    </b-field>
    <b-button
      type="is-primary"
      native-type="submit"
      :loading="isLoading"
      @click="confirm(code)"
    >
      {{ $t('component.verification.confirm') }}
    </b-button>
    <b-button
      type="is-warning"
      :loading="isLoading"
      @click="resend()"
    >
      {{ $t('component.verification.resend') }}
    </b-button>
  </form>
</template>

<script lang="ts">
export default {
  name: "AppVerifyAccount",
  props: {
    error: {
      type: Object,
      default: () => {
        return {
          code: "",
          status: "",
          message: ""
        };
      }
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      code: ""
    };
  },
  computed: {
    codeMessage() {
      return {
        [this.error.message]: this.error.status !== null
      };
    },
    messageType() {
      return {
        "is-danger": this.error.status !== null
      };
    }
  },
  methods: {
    confirm(code: string) {
      this.$emit("confirmVerificationCode", code);
    },
    resend() {
      this.$emit("resendVerificationCode");
    }
  }
};
</script>

<style lang="scss" scoped>
</style>