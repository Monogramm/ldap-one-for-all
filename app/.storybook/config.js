import Vue from 'vue';

// VueI18n
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);

// Buefy
import Buefy from 'buefy';
import 'bulma/css/bulma.css';
import 'buefy/dist/buefy.css';
Vue.use(Buefy);

// Storybook
import { addParameters, configure, addDecorator } from '@storybook/vue';
import { checkA11y } from '@storybook/addon-a11y';
import '@storybook/addon-actions';
import '@storybook/addon-links';
import '@storybook/addon-console';

import '../assets/styles/_design-system.scss';

const req = require.context('../assets/vue', true, /.stories.[tj]s$/);

const loadStories = () => {
  req.keys().forEach((filename) => req(filename));
};

addParameters(checkA11y);

addDecorator(require('storybook-vue-router').default());

configure(loadStories, module);
