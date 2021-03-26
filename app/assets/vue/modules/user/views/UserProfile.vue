<template>
  <section class="section">
    <div class="columns is-desktop">
      <!-- CONTENEUR USER INFORMATION -->
      <div
        class="column is-three-quarters"
        shadow
      > 
        <div class="">
          <div class="content">
            <h1 class="title is-1">
              {{ titleLabel }}
            </h1>
          </div>
        </div>
        <div class="">
          <!-- BOUCLE DE LISTING DES INFO UTILISATEUR-->
          <div class="">
            <div>
              <strong>{{ $t("common.username.label") }}:</strong>
              <span>{{ authUser.username }}</span>
            </div>
            <div>
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
                  to="/verify"
                  type="is-primary"
                >{{ $t("profile.verification.title") }}</b-button>
              </span>
            </div>
            <div
              id="data_user"
              class="container"
            >
              <div
                v-for="(information, index) in authUser"
                :key="index"
              >
                <div>
                  <strong>Key : </strong>
                  <p :data-userElem="index">
                    {{ information }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- CONTENEUR USER COMMAND --> 
      <div class="column">
        <div class="column">
          <div class=""> 
            Gestion du profile
          </div>
          <b-button
            type="is-link"
            @click="showPasswordChangeModal = true"
          >
            {{ $t("profile.password-change") }}
          </b-button>
          <b-button
            type="is-link"
            @click="modifyAccount"
          >
            Modifier le compte
          </b-button>
          <b-button
            type="is-danger"
            @click="showAccountDeleteModal = true"
          >
            {{ $t("profile.account-delete.label") }}
          </b-button>
        </div>
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
      //TABLEAUX DE TEST POUR AFFICHAGE : UNE IDEE
      user: [
        {key:"nom",
          value:"Jean"},
        {key:"age",
          value:"48"},
        {key:"role",
          value:"lambda"}
      ]
    };
  },
  computed: {
    ...mapGetters("auth", ["authUser"]),
    titleLabel() {
      return this.$t("profile.welcome");
    }
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
    },
    modifyAccount(): boolean
    {
      let element_p = document.getElementById("data_user").querySelectorAll("div > p");
      return true;
    }
  }
};
</script>

<style lang="scss" scoped>
.profile-buttons {
  justify-content: space-evenly;
}
</style>
