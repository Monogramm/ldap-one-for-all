import AppGoBackButton from './AppGoBackButton';
import {action} from "@storybook/addon-actions";
import VueI18n from "vue-i18n";

export default {
  title: 'Components/AppGoBackButton',
  component: AppGoBackButton,
};

export const Default = () => ({
  components: { AppGoBackButton },
  template: '<app-go-back-button @click="action" />',
  methods: { action: action('clicked') },
  i18n: new VueI18n({
    locale: 'en',
    messages: {}
  }),
});
