import { action } from '@storybook/addon-actions';
import VueI18n from 'vue-i18n';

import AppLogin from './AppLogin';

export default {
  title: 'Components/AppLogin',
  component: AppLogin
};

export const Case1 = () => ({
  components: { AppLogin },
  template: '<app-login @login="action" />',
  methods: {
    actionLogin: action('login clicked'),
  },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});
