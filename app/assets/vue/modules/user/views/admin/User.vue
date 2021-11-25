<template>
  <div class="section">
    <h1 class="title is-1">
      {{ $t(isEdit ? "users.edit" : "users.create") }}
    </h1>

    <b-loading
      :is-full-page="isFullPage"
      :active.sync="isLoading"
    />

    <div
      v-if="user && isEdit"
      class="buttons box cloudy"
    >
      <b-button
        v-if="user && isEdit"
        :disabled="!!!user.enabled"
        :loading="isLoading"
        @click="showPasswordChangeModal = true"
      >
        {{ $t("profile.password-change") }}
      </b-button>
      <b-button
        v-if="user && isEdit"
        :type="user.enabled ? 'is-danger': 'is-warning'"
        :loading="isLoading"
        @click="onSetEnabled(user.id, !user.enabled)"
      >
        {{ $t(user.enabled ? "users.disable" : "users.enable") }}
      </b-button>
      <b-button
        v-if="user && isEdit && canImpersonate(user.username)"
        type="is-info"
        :disabled="!user.enabled"
        @click="onImpersonate(user.username)"
      >
        {{ $t("impersonation.start") }}
      </b-button>
    </div>

    <app-user
      v-if="user"
      :auth-user="authUser"
      :user="user"
      :error="error"
      :is-loading="isLoading"
      @updateParent="onChildPropsChanged"
      @submit="onSubmit"
    />

    <b-modal
      :active.sync="showPasswordChangeModal"
      has-modal-card
      trap-focus
      aria-role="dialog"
      aria-modal
    >
      <app-password-change-modal
        :show-old-password="false"
        :has-error="hasError"
        :error="error"
        :loading="isLoading"
        @submit="onChangePassword"
      />
    </b-modal>
  </div>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import { AxiosError } from 'axios';

import { IError } from '../../../../interfaces/error';

import { IUser, User } from "../../interfaces/user";

import { EnablePayload, SetPasswordPayload } from '../../actions';
import { IUserPasswordChange } from '../../interfaces';

import AppUser from "../../components/admin/AppUser/AppUser.vue";

import AppPasswordChangeModal from "../../components/AppPasswordChangeModal/AppPasswordChangeModal.vue";

export default {
  name: "User",
  components: { AppUser, AppPasswordChangeModal, },
  props: {
    id: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      isFullPage: true,
      showPasswordChangeModal: false,
      user: null as IUser,
    };
  },
  computed: {
    ...mapGetters("user", ["isLoading", "item", "hasError", "error"]),
    ...mapGetters("auth", ["authUser"]),
    isEdit() {
      return !!this.id;
    }
  },
  created() {
    if (this.id) {
      this.load();
    } else {
      this.user = new User();
    }
  },
  methods: {
    load() {
      this.$store
        .dispatch("user/get", this.id)
        .then((response: IUser) => {
          this.user = response;
        });
    },
    onChildPropsChanged(property: string, value: string) {
      this.item[property] = value;
    },
    async editUser(id: string, user: IUser) {
      await this.$store
        .dispatch("user/update", user)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminUsers" });
          } else {
            this.handleError(this.error);
          }
        });
    },
    async createUser(user: IUser) {
      await this.$store
        .dispatch("user/create", user)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminUsers" });
          } else {
            this.handleError(this.error);
          }
        });
    },
    onSubmit() {
      if (this.isEdit) {
        return this.editUser(this.id, this.user);
      }

      return this.createUser(this.user);
    },
    canImpersonate(username: string): boolean {
      return !!this.authUser
        && this.authUser.roles.includes('ROLE_SUPER_ADMIN')
        && this.authUser.username !== username;
    },
    async onChangePassword(data: IUserPasswordChange) {
      const payload: SetPasswordPayload = {
        userId: this.id,
        data: data,
      };
      this.$store.dispatch("user/setPassword", payload).then(() => {
        if (!this.hasError === true) {
          this.handleSuccess();
          this.showPasswordChangeModal = false;
        } else {
          this.handleError(this.error);
        }
      });
    },
    onSetEnabled(userId: string, enabled: boolean) {
      this.$buefy.dialog.confirm({
        message: this.$t(
          enabled ? "users.enable-message" : "users.disable-message"
        ),
        confirmText: this.$t("common.continue"),
        cancelText: this.$t("common.cancel"),
        type: "is-info",
        hasIcon: true,
        onConfirm: () => {
          const payload: EnablePayload = {
            userId: userId,
            enabled: enabled,
          };
          this.$store.dispatch("user/setEnable", payload).then(
            () => this.load()
          );
        },
      });
    },
    onImpersonate(username: string) {
      this.$store.dispatch("auth/startImpersonation", username).then(
        () => this.$router.push("/")
      );
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
</style>
