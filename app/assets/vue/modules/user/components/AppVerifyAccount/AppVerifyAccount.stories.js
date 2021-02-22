import AppVerifyAccount from './AppVerifyAccount';
import VueI18n from "vue-i18n";
import {action} from "@storybook/addon-actions";

export default {
  title: 'Components/AppVerifyAccount',
  component: AppVerifyAccount,
};

export const Default = () => ({
  components: { AppVerifyAccount },
  template: '<app-verify-account @confirmVerificationCode="action" @resendVerificationCode="action"></app-verify-account>',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});

export const Error = () => ({
  components: { AppVerifyAccount },
  template: '<app-verify-account :error="error" @confirmVerificationCode="action" @resendVerificationCode="action"></app-verify-account>',
  methods: { action: action('clicked') },
  props: {
    error: {
      status: 400,
      message: 'Sample error message',
      code: ''
    }
  },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});

export const Loading = () => ({
  components: { AppVerifyAccount },
  template: '<app-verify-account :is-loading="true" @confirmVerificationCode="action" @resendVerificationCode="action"></app-verify-account>',
  methods: { action: action('clicked') },
  props: {
    isLoading: true
  },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});
