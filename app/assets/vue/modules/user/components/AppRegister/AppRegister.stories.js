import { action } from '@storybook/addon-actions';
import VueI18n from 'vue-i18n';

import AppRegister from './AppRegister';

export default {
  title: 'Components/AppRegister',
  component: AppRegister
};

export const Case1 = () => ({
  components: { AppRegister },
  template: '<app-register @click="action" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});
