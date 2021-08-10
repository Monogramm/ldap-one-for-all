<template>
  <app-parameter
    v-if="parameter"
    :parameter="parameter"
    :types="types"
    :is-loading="isLoading"
    @updateParent="onChildPropsChanged"
    @submit="onSubmit"
  />
</template>

<script lang="ts">
import { mapGetters } from "vuex";
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
      this.$store
        .dispatch("parameter/get", this.id)
        .then((response: IParameter) => {
          this.parameter = response;
        });
    } else {
      this.parameter = new Parameter();
    }
  },
  methods: {
    onChildPropsChanged(property: string, value: string) {
      this.item[property] = value;
    },
    async editParameter(id: string, parameter: IParameter) {
      await this.$store
        .dispatch("parameter/update", parameter)
        .then(() => {
          if (!this.hasError) {
            this.$router.replace({ name: "AdminParameters" });
          }
        });
    },
    async createParameter(parameter: IParameter) {
      await this.$store
        .dispatch("parameter/create", parameter)
        .then(() => {
          if (!this.hasError) {
            this.$router.replace({ name: "AdminParameters" });
          }
        });
    },
    onSubmit() {
      if (this.isEdit) {
        return this.editParameter(this.id, this.parameter);
      }

      return this.createParameter(this.parameter);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
