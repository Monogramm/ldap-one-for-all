import { action } from '@storybook/addon-actions';
import VueI18n from 'vue-i18n';

import AppTopBar from './AppTopBar';

export default {
  title: 'Components/AppTopBar',
  component: AppTopBar,
};

export const Anonymous = () => ({
  components: { AppTopBar },
  template: '<app-nav-bar :languages="[]" @languageChanged="action" :authenticated="false" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});

export const Authenticated = () => ({
  components: { AppTopBar },
  template: '<app-nav-bar :languages="[]" @languageChanged="action" :authenticated="true" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});

export const SigningOut = () => ({
  components: { AppTopBar },
  template: '<app-nav-bar :languages="[]" @languageChanged="action" :authenticated="true" :signing-out="true" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});
