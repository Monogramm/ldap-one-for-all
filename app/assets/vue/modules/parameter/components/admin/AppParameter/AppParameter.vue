<template>
  <form
    class="box"
    @submit.prevent
  >
    <b-field
      :label="$t('parameters.name')"
      :type="fieldType('name')"
      :message="fieldMessage('name')"
    >
      <b-input
        v-model="parameter.name"
        maxlength="255"
        required
        :disabled="isLoading"
        @change="updateParent('name', $event.target.value)"
      />
    </b-field>

    <b-field
      :label="$t('parameters.value')"
      :type="fieldType('value')"
      :message="fieldMessage('value')"
    >
      <b-input
        v-model="parameter.value"
        maxlength="4096"
        :disabled="isLoading"
        @change="updateParent('value', $event.target.value)"
      />
    </b-field>

    <b-field
      :label="$t('parameters.type')"
      :type="fieldType('type')"
      :message="fieldMessage('type')"
    >
      <b-select
        v-model="parameter.type"
        required
        :placeholder="$t('parameters.types')"
        @input.native.prevent="updateParent('type', $event.target.value)"
      >
        <option
          v-for="type in types"
          :key="type"
          :value="type"
        >
          {{ type }}
        </option>
      </b-select>
    </b-field>


    <b-field :label="$t('parameters.description')">
      <b-input
        v-model="parameter.description"
        type="textarea"
        required
        :disabled="isLoading"
        @change="updateParent('description', $event.target.value)"
      />
    </b-field>

    <b-button
      type="is-primary"
      icon-left="save"
      native-type="submit"
      :loading="isLoading"
      @click="submit"
    >
      {{ $t(isEdit ? 'common.update' : 'common.create') }}
    </b-button>
  </form>
</template>

<script lang="ts">
import { IParameter, Parameter } from "../../../interfaces/parameter";

export default {
  name: "AppParameter",
  props: {
    parameter: {
      type: Object,
      default: () => new Parameter()
    },
    error: {
      type: Object,
      default: () => {}
    },
    isLoading: {
      type: Boolean,
      default: false
    },
    types: {
      type: Array,
      default: [] as string[]
    }
  },
  computed: {
    isEdit() {
      return !!this.parameter.id;
    }
  },
  methods: {
    updateParent(property: string, value: string) {
      this.$emit("updateParent", property, value);
    },
    submit() {
      this.$emit("submit");
    },
    fieldMessage(field: string) {
      return {
        [this.error.errors[field]]: this.error.errors && !!this.error.errors[field],
      };
    },
    fieldType(field: string) {
      return {
        "is-danger":
            this.error.errors && !!this.error.errors[field]
      };
    },
  }
};
</script>

<style lang="scss" scoped>
</style>
