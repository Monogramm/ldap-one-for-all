<template>
  <section class="section">
    <div class="conteneur_0">
      <!-- CONTENEUR USER INFORMATION -->
      <div
        class="conteneur_1"
        shadow
      > 
        <div class="div_titre">
          <div class="content">
            <h1 class="title is-1 center">
              {{ titleLabel }}
            </h1>
          </div>
        </div>
        <div class="conteneur_1_0">
          <!-- BOUCLE DE LISTING DES INFO UTILISATEUR-->
          <div class="block_user_info">
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
              v-for="(information, index) in authUser"
              :key="index"
            >
              <div style="display: flex;">
                <strong>Key : </strong>
                <p :data-userElem="index">
                  {{ information }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- CONTENEUR USER COMMAND --> 
      <div class="conteneur_2 center">
        <div class="conteneur_2_0">
          <div class="div_titre"> 
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
      return true;
    }
  }
};
</script>

<style lang="scss" scoped>
.conteneur_0
{
  display: flex;
  width: 100%;
  min-height: 50vh;
}
.conteneur_1
{
  height: auto;
  width: 85vh;
}

.conteneur_1_0
{
  flex-direction: column;
}

.block_user_info
{
  overflow-y: scroll;
}

.conteneur_2
{
  height: auto;
  align-items: flex-start;
}

.conteneur_2_0
{
  display: flex;
  flex-direction: column;
}

.conteneur_2_0 button
{
  border-radius: 0;
}

.div_titre
{
  width: 100%;
  border: 1px solid grey;
  color: black;
}
.profile-buttons {
  justify-content: space-evenly;
}
</style>
