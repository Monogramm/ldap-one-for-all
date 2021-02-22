import { action } from '@storybook/addon-actions';
import VueI18n from 'vue-i18n';

import AppNavBar from './AppNavBar';

export default {
  title: 'Components/AppNavBar',
  component: AppNavBar,
};

export const Anonymous = () => ({
  components: { AppNavBar },
  template: '<app-nav-bar :languages="[]" @languageChanged="action" :authenticated="false" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});

export const Authenticated = () => ({
  components: { AppNavBar },
  template: '<app-nav-bar :languages="[]" @languageChanged="action" :authenticated="true" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});

export const SigningOut = () => ({
  components: { AppNavBar },
  template: '<app-nav-bar :languages="[]" @languageChanged="action" :authenticated="true" :signing-out="true" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});
