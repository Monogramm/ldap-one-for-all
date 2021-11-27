<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
    </div>

    <div class="card">
      <div
        v-if="authUser"
        class="card-content"
      >
        <strong>{{ $t("common.username.label") }}:</strong>
        <span>{{ authUser.username }}</span>
        <hr>
        <strong>{{ $t("common.email.label") }}:</strong>
        <span>
          {{ authUser.email }}
          <b-icon
            v-if="authUser.isVerified"
            icon="check"
            size="is-small"
            type="is-success"
          />
          <b-button
            v-else
            tag="router-link"
            size="is-small"
            :to="{ name: 'VerifyAccount' }"
            type="is-primary"
          >{{ $t("profile.verification.title") }}</b-button>
        </span>
      </div>

      <div class="card-footer box profile-buttons buttons">
        <b-button
          type="is-link"
          @click="showPasswordChangeModal = true"
        >
          {{ $t("profile.password-change") }}
        </b-button>

        <b-button
          type="is-danger"
          @click="showAccountDeleteModal = true"
        >
          {{ $t("profile.account-delete.label") }}
        </b-button>
      </div>
    </div>

    <b-modal
      :active.sync="showPasswordChangeModal"
      has-modal-card
      trap-focus
      aria-role="dialog"
      aria-modal
    >
      <app-password-change-modal
        :has-error="hasError"
        :error="error"
        :loading="isLoading"
        @submit="onChangePassword"
      />
    </b-modal>

    <b-modal
      :active.sync="showAccountDeleteModal"
      has-modal-card
      trap-focus
      aria-role="dialog"
      aria-modal
    >
      <form @submit.prevent>
        <div
          class="modal-card"
          style="width: auto;"
        >
          <header class="modal-card-head">
            <p class="modal-card-title">
              {{ $t("profile.account-delete.label") }}
            </p>
          </header>
          <section class="modal-card-body">
            {{ $t("profile.account-delete.confirm") }}
          </section>
          <footer class="modal-card-foot">
            <b-button @click="showAccountDeleteModal = false">
              {{ $t("common.no") }}
            </b-button>
            <b-button
              type="is-danger"
              @click="disableAccount"
            >
              {{ $t("common.yes") }}
            </b-button>
          </footer>
        </div>
      </form>
    </b-modal>
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import { AxiosError } from "axios";

import { IError } from '../../../interfaces/error';

import { IUserPasswordChange } from "../interfaces";

import AppPasswordChangeModal from "../components/AppPasswordChangeModal/AppPasswordChangeModal.vue";

export default {
  name: "UserProfile",
  components: {
    AppPasswordChangeModal
  },
  data() {
    return {
      showPasswordChangeModal: false,
      showAccountDeleteModal: false,
      source: null as String,
      fullDn: null as String,
    };
  },
  computed: {
    ...mapGetters("auth", ["authUser"]),
    ...mapGetters("user", ["isLoading", "hasError", "error"]),
    titleLabel() {
      return this.$t("profile.welcome");
    },
  },
  async created() {
    this.source = this.authUser?.metadata?.auth?.source ?? "unknown";

    if (this.source === 'ldap') {
      this.fullDn = this.authUser.metadata.ldap.fullDn;
    }
  },
  metaInfo() {
    return { title: this.titleLabel };
  },
  methods: {
    async disableAccount() {
      this.$store.dispatch("user/disableAccount").then(() => {
        if (this.hasError === true) {
          this.handleSuccess();
          this.$store.dispatch("auth/logout");
          this.$router.push({ name: "Login" });
        } else {
          this.handleError(this.error);
        }
      });
    },
    async onChangePassword(data: IUserPasswordChange) {
      this.$store.dispatch("user/passwordChange", data).then(() => {
        if (!this.hasError === true) {
          this.handleSuccess();
          this.$store.dispatch("auth/logout");
          this.$router.push({ name: "Login" });
        } else {
          this.handleError(this.error);
        }
      });
    },
    handleError(error: AxiosError<IError>) {
      var message = null;
      const serverError = error?.response?.data;
      if (!!serverError && !!serverError.message) {
        message = serverError.message;
      } else {
        message = error.message;
      }
      if (!!!message) {
        message = this.$t("common.fatal.unexpected");
      }

      this.$buefy.snackbar.open(
        {
          message: message,
          type: "is-danger",
          indefinite: true,
        }
      );
    },
    handleSuccess() {
      this.$buefy.toast.open(
        {
          duration: 2500,
          message: this.$t("common.success"),
          type: "is-success"
        }
      );
    },
  }
};
</script>

<style lang="scss" scoped>
.profile-buttons {
  justify-content: space-evenly;
}
</style>
