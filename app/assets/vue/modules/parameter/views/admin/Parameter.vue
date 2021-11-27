<template>
  <div class="section">
    <h1 class="title is-1">
      {{ $t(isEdit ? "parameters.edit" : "parameters.create") }}
    </h1>

    <b-loading
      :is-full-page="isFullPage"
      :active.sync="isLoading"
    />

    <app-parameter
      v-if="parameter"
      :parameter="parameter"
      :error="error"
      :types="types"
      :is-loading="isLoading"
      @updateParent="onChildPropsChanged"
      @submit="onSubmit"
    />
  </div>
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import { AxiosError } from 'axios';

import { IError } from '../../../../interfaces/error';

import { IParameter, Parameter } from "../../interfaces/parameter";

import AppParameter from "../../components/admin/AppParameter/AppParameter.vue";

export default {
  name: "Parameter",
  components: { AppParameter },
  props: {
    id: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      isFullPage: true,
      types: [] as string[],
      parameter: null as IParameter,
    };
  },
  computed: {
    // TODO Add types to parameter getters
    ...mapGetters("parameter", ["isLoading", "item", "hasError", "error"]),
    isEdit() {
      return !!this.id;
    }
  },
  created() {
    this.$store
      .dispatch("parameter/getTypes")
      .then((response: string[]) => {
        this.types = [...response];
      });
    if (this.id) {
      this.load();
    } else {
      this.parameter = new Parameter();
    }
  },
  methods: {
    load() {
      this.$store
        .dispatch("parameter/get", this.id)
        .then((response: IParameter) => {
          this.parameter = response;
        });
    },
    onChildPropsChanged(property: string, value: string) {
      this.item[property] = value;
    },
    async editParameter(id: string, parameter: IParameter) {
      await this.$store
        .dispatch("parameter/update", parameter)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminParameters" });
          } else {
            this.handleError(this.error);
          }
        });
    },
    async createParameter(parameter: IParameter) {
      await this.$store
        .dispatch("parameter/create", parameter)
        .then(() => {
          if (!this.hasError) {
            this.handleSuccess();
            this.$router.replace({ name: "AdminParameters" });
          } else {
            this.handleError(this.error);
          }
        });
    },
    onSubmit() {
      if (this.isEdit) {
        return this.editParameter(this.id, this.parameter);
      }

      return this.createParameter(this.parameter);
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
