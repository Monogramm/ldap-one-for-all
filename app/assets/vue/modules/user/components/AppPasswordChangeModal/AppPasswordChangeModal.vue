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
          :label="$t('component.password-change.old.label')"
          :type="{ 'is-danger': !!!oldPassword || (hasError && error.code === 1003) }"
          :message="error.message"
        >
          <b-input
            v-model="oldPassword"
            type="password"
            required
            password-reveal
            :placeholder="$t('component.password-change.old.placeholder')"
          />
        </b-field>
        <b-field :label="$t('component.password-change.new.label')">
          <b-input
            v-model="newPassword"
            type="password"
            required
            password-reveal
            :placeholder="$t('component.password-change.new.placeholder')"
          />
        </b-field>
        <b-field :label="$t('component.password-change.confirm.label')">
          <b-input
            v-model="confirmPassword"
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
          :loading="isLoading"
          :disabled="!isValid()"
          @click="changePassword()"
        >
          {{ $t("common.validate") }}
        </b-button>
      </footer>
    </div>
  </form>
</template>

<script lang="ts">
import { mapGetters } from "vuex";

export default {
  name: "AppPasswordChangeModal",
  data() {
    return {
      oldPassword: "",
      newPassword: "",
      confirmPassword: "",
      duration: 2000
    };
  },
  computed: {
    ...mapGetters("user", ["isLoading", "hasError", "error"]),
    confirmErrorMessage: {
      get(): any {
        return {
          [this.$t("common.password.empty")]:
            this.newPassword !== "" && this.confirmPassword !== "",
          [this.$t("common.password.confirm")]:
            this.newPassword !== this.confirmPassword
        };
      },
      set(value: any) {
        return value;
      }
    }
  },
  methods: {
    isValid() {
      return (
        !!this.oldPassword &&
        !!this.newPassword &&
        !!this.confirmPassword &&
        this.newPassword === this.confirmPassword
      );
    },
    async changePassword() {
      if (this.isValid()) {
        const data = {
          oldPassword: this.oldPassword,
          newPassword: this.newPassword,
          confirmPassword: this.confirmPassword
        };
        this.$store.dispatch("user/passwordChange", data).then(() => {
          if (!this.hasError) {
            this.$store.dispatch("auth/logout");
            this.$router.push({ name: "Login" });
          }
        });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
</style>