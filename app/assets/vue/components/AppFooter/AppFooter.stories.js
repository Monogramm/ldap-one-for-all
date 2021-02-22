import { action } from '@storybook/addon-actions';
import VueI18n from 'vue-i18n';

import AppFooter from './AppFooter';

export default {
  title: 'Components/AppFooter',
  component: AppFooter
};

export const Case1 = () => ({
  components: { AppFooter },
  template: '<app-footer @click="action" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});
