<template>
  <section class="section">
    <div class="content">
      <h1 class="title is-1">
        {{ titleLabel }}
      </h1>
    </div>

    <div class="card">
      <div class="card-content">
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
          v-if="source === 'local'"
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

    <section class="section">
      <b-modal
        :active.sync="showPasswordChangeModal"
        has-modal-card
        trap-focus
        aria-role="dialog"
        aria-modal
      >
        <app-password-change-modal />
      </b-modal>
    </section>

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
    <section />
  </section>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
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
    };
  },
  computed: {
    ...mapGetters("auth", ["authUser"]),
    titleLabel() {
      return this.$t("profile.welcome");
    }
  },
  async created() {
    this.source = this.authUser?.metadata?.auth?.source ?? "unknown";
  },
  metaInfo() {
    return { title: this.titleLabel };
  },
  methods: {
    async disableAccount() {
      this.$store.dispatch("user/disableAccount").then(() => {
        if (!this.hasError) {
          this.$store.dispatch("auth/logout");
          this.$router.push({ name: "Login" });
        }
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.profile-buttons {
  justify-content: space-evenly;
}
</style>
