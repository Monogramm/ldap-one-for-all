<template>
  <form
    class="box"
    @submit.prevent
  >
    <b-field
      :label="$t('users.username')"
      :type="fieldType('username')"
      :message="fieldMessage('username')"
    >
      <b-input
        v-model="user.username"
        maxlength="255"
        required
        :disabled="isLoading"
        @change="updateParent('username', $event.target.value)"
      />
    </b-field>

    <b-field
      :label="$t('users.email')"
      :type="fieldType('email')"
      :message="fieldMessage('email')"
    >
      <b-input
        v-model="user.email"
        maxlength="255"
        required
        :disabled="isLoading"
        @change="updateParent('email', $event.target.value)"
      />
    </b-field>

    <b-field
      :label="$t('users.language')"
      :type="fieldType('language')"
      :message="fieldMessage('language')"
    >
      <b-input
        v-model="user.language"
        maxlength="2"
        required
        :disabled="isLoading"
        @change="updateParent('language', $event.target.value)"
      />
    </b-field>

    <b-field
      :label="$t('users.roles')"
      :type="fieldType('roles')"
      :message="fieldMessage('roles')"
    >
      <b-taginput
        v-model="user.roles"
        icon="angle-right"
        :placeholder="$t('users.roles-placeholder')"
        :disabled="isLoading"
        @change="updateParent('roles', $event.target.value)"
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
import { IUser, User } from "../../../interfaces/user";

export default {
  name: "AppUser",
  props: {
    user: {
      type: Object,
      default: () => new User()
    },
    error: {
      type: Object,
      default: () => {}
    },
    isLoading: {
      type: Boolean,
      default: false
    },
  },
  computed: {
    isEdit() {
      return !!this.user.id;
    },
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
