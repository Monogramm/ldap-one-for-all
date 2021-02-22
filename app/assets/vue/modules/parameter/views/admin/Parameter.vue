<template>
  <app-parameter
    :parameter="item"
    :types="types"
    :is-loading="isLoading"
    @updateParent="onChildPropsChanged"
    @submit="onSubmit"
  />
</template>

<script lang="ts">
import { mapGetters } from "vuex";
import { IParameter } from "../../interfaces/parameter";

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
      types: [] as string[]
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
        .dispatch("parameter/get", this.id);
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
        return this.editParameter(this.id, this.item);
      }

      return this.createParameter(this.item);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
