import Vue from "vue";
import VueI18n from "vue-i18n";
import axios from "axios";

Vue.use(VueI18n);

const dateTimeFormats: VueI18n.DateTimeFormats = {
  en: {
    short: {
      year: "numeric",
      month: "short",
      day: "numeric",
    },
    long: {
      year: "numeric",
      month: "short",
      day: "numeric",
      weekday: "short",
      hour: "numeric",
      minute: "numeric",
      hour12: true,
    },
  },
  fr: {
    short: {
      year: "numeric",
      month: "short",
      day: "numeric",
    },
    long: {
      year: "numeric",
      month: "short",
      day: "numeric",
      weekday: "short",
      hour: "numeric",
      minute: "numeric",
      hour12: false,
    },
  },
};

const numberFormats: VueI18n.NumberFormats = {
  en: {
    currency: {
      style: "currency",
      currency: "USD",
    },
    percent: {
      style: "percent",
    },
  },
  fr: {
    currency: {
      style: "currency",
      currency: "EUR",
      currencyDisplay: "symbol",
    },
    percent: {
      style: "percent",
    },
  },
};

export const i18n = new VueI18n({
  dateTimeFormats,
  numberFormats,
});

const loadedLocales: any = {};

const setI18nLocale = (locale: string, messages: any) => {
  i18n.locale = locale;
  axios.defaults.headers.common["Accept-Language"] = locale;
  let htmlDoc = document.querySelector("html");
  if (htmlDoc) {
    htmlDoc.setAttribute("lang", locale.substr(0, 2));
  }
  i18n.setLocaleMessage(locale, messages);
};

export const availableLocales = [
  { label: "English", value: "en" },
  { label: "Français", value: "fr" },
  //{ label: 'Deutsch', value: 'de' },
  //{ label: 'Português', value: 'pt' },
  //{ label: 'Русский', value: 'ru' },
  //{ label: '中文', value: 'zh-cn' },
];

export const browserLocale = () => {
  let temp = navigator.language;
  let short = temp ? temp.substr(0, 2) : null;

  let locale: string = null;
  availableLocales.forEach((loc) => {
    if (loc.value === temp) {
      locale = temp;
      return;
    } else if (loc.value === short) {
      locale = short;
      return;
    }
  });

  return locale;
};

export const loadLocaleAsync = async (locale: string) => {
  let messages = loadedLocales[locale];

  if (i18n.locale !== locale && !messages) {
    const res = await axios.get(`/build/i18n/${locale}.json`);

    messages = res.data;
    loadedLocales[locale] = messages;
  }

  setI18nLocale(locale, messages);
};

const defaultLocale = "en";
let initLocale = browserLocale();
if (!!!initLocale) {
  initLocale = defaultLocale;
}

loadedLocales[initLocale] = require("../../../i18n/" + initLocale + ".json");
setI18nLocale(
  initLocale,
  loadedLocales[initLocale]
);

i18n.fallbackLocale = defaultLocale;
if (defaultLocale !== initLocale) {
  loadedLocales[defaultLocale] = require("../../../i18n/" + defaultLocale + ".json");
  i18n.setLocaleMessage(
    defaultLocale,
    loadedLocales[defaultLocale]
  );
}
