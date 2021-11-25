<template>
  <form @submit.prevent>
    <div
      class="modal-card"
      style="width: auto;"
    >
      <header class="modal-card-head">
        <p class="modal-card-title">
          {{ $t("component.password-change.title") }}
        </p>
      </header>

      <section class="modal-card-body">
        <b-field
          v-if="showOldPassword"
          :label="$t('component.password-change.old.label')"
          :type="{ 'is-danger': !!!payload.oldPassword || (hasError && error.code === 1003) }"
          :message="(hasError && error.code === 1003) ? error.message : ''"
        >
          <b-input
            v-model="payload.oldPassword"
            type="password"
            required
            password-reveal
            :placeholder="$t('component.password-change.old.placeholder')"
          />
        </b-field>

        <b-field
          :label="$t('component.password-change.new.label')"
          :type="{ 'is-danger': !!!payload.newPassword || (hasError && error.code === 1004) }"
          :message="(hasError && error.code === 1004) ? error.message : ''"
        >
          <b-input
            v-model="payload.newPassword"
            type="password"
            required
            password-reveal
            :placeholder="$t('component.password-change.new.placeholder')"
          />
        </b-field>
        <b-field
          :label="$t('component.password-change.confirm.label')"
          :type="{ 'is-danger': !!!payload.confirmPassword || (hasError && error.code === 1005) }"
          :message="(hasError && error.code === 1005) ? error.message : ''"
        >
          <b-input
            v-model="payload.confirmPassword"
            type="password"
            required
            :placeholder="$t('component.password-change.confirm.placeholder')"
            :message="confirmErrorMessage"
          />
        </b-field>
      </section>
      <footer class="modal-card-foot">
        <b-button @click="$parent.close()">
          {{ $t("common.cancel") }}
        </b-button>
        <b-button
          type="is-primary"
          native-type="submit"
          :loading="loading"
          :disabled="!payload.isValid()"
          @click="submit()"
        >
          {{ $t("common.validate") }}
        </b-button>
      </footer>
    </div>
  </form>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import { Error } from '../../../../interfaces/error';
import { UserPasswordChangeDefault } from '../../interfaces';

export default {
  name: "AppPasswordChangeModal",
  props: {
    showOldPassword: {
      type: Boolean,
      default: true
    },
    hasError: {
      type: Boolean,
      default: false
    },
    error: {
      type: Error,
      default: null
    },
    loading: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      payload: UserPasswordChangeDefault()
    };
  },
  computed: {
    confirmErrorMessage() {
      return {
        [this.$t("common.password.empty")]:
          this.payload.newPassword !== "" && this.payload.confirmPassword !== "",
        [this.$t("common.error.field-not-valid", {format: this.$t("common.password.format")})]:
          !!this.payload.newPassword && !this.payload.isPasswordValid,
        [this.$t("common.password.confirm")]:
          this.payload.newPassword !== this.payload.confirmPassword
      };
    },
    isPasswordValid() {
      // TODO Check complexity
      return this.payload.newPassword.length >= 6;
    },
  },
  created() {
    if (this.showOldPassword === false) {
      this.payload.oldPassword = null;
    }
  },
  methods: {
    submit() {
      if (!this.payload.isValid()) {
        return;
      }

      this.$emit("submit", this.payload);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
