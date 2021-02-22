<template>
  <div class="section">
    <h1 class="title is-1">
      {{ $t(isEdit ? "parameters.edit" : "parameters.create") }}
    </h1>

    <form
      class="box"
      @submit.prevent
    >
      <b-field :label="$t('parameters.name')">
        <b-input
          v-model="parameter.name"
          maxlength="254"
          required
          :disabled="isLoading"
          @change="updateParent('name', $event.target.value)"
        />
      </b-field>

      <b-field :label="$t('parameters.value')">
        <b-input
          v-model="parameter.value"
          maxlength="254"
          required
          :disabled="isLoading"
          @change="updateParent('value', $event.target.value)"
        />
      </b-field>

      <b-field :label="$t('parameters.type')">
        <b-select
          v-model="parameter.type"
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
        native-type="submit"
        :loading="isLoading"
        @click="submit"
      >
        {{ $t(isEdit ? 'common.edit' : 'common.create') }}
      </b-button>
    </form>
  </div>
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
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
