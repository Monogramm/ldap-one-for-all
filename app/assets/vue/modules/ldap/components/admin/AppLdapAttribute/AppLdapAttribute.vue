<!-- Component responsible for the creation of inputs-->
<template>
  <div>
    <template
      v-for="(row, index) in values"
    >
      <div
        :key="`divColumns:${index}`"
        class="columns mb-0 is-mobile"
      >
        <div
          :key="`divValueInput:${index}`"
          class="column"
        >
          <!-- Input value attribute-->
          <b-input
            :key="`inputValue:${index}`"
            v-model="values[index]"
            :value="row"
            :type="type"
            :title="$t('ldap.entries.new.value.value-title')"
            :placeholder="$t('ldap.entries.new.value.value-placeholder')"
            :disabled="isLoading"
          />
        </div>

        <div
          :key="`divDeleteButton:${index}`"
          class="column is-2 pl-0"
        >
          <!-- Section for the input create/delete-->
          <b-button
            :key="`buttonRemoveValue:${index}`"
            type="is-danger"
            icon-right="trash"
            :title="$t('ldap.entries.new.value.del-title')"
            :disabled="isLoading"
            @click="removeValue(index)"
          />
        </div>
      </div>
    </template>
    <div
      class="columns mb-0 is-mobile"
    >
      <div class="column" />
      <div class="column is-2 pl-0">
        <b-button
          icon-right="plus"
          :title="$t('ldap.entries.new.value.add-title')"
          :disabled="isLoading"
          @click="addValue()"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts">

export default {
  name: "AppLdapAttribute",
  props: {
    value : {
      type: Array,
      default: () => Array<String>(),
    },
    type : {
      type: String,
      default: () => 'text',
    },
    isLoading: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      /**
       * The LDAP attribute values.
       */
      values: this.value as Array<String>,
    };
  },
  methods: {
    addValue() {
      this.values.push('');
    },
    removeValue(index: number) {
      this.values.splice(index, 1);
    },
    getLastItem() {
      return this.values.length-1;
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
