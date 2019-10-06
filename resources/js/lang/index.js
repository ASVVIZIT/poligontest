import Vue from 'vue';
import VueI18n from 'vue-i18n';
import Cookies from 'js-cookie';
import elementRuLocale from 'element-ui/lib/locale/lang/ru-RU'; // element-ui lang
import elementEnLocale from 'element-ui/lib/locale/lang/en'; // element-ui lang
import elementZhLocale from 'element-ui/lib/locale/lang/zh-CN';// element-ui lang
import elementViLocale from 'element-ui/lib/locale/lang/vi';// element-ui lang
import ruLocale from './ru';
import enLocale from './en';
import zhLocale from './zh';
import viLocale from './vi';

Vue.use(VueI18n);

const messages = {
  ru: {
    ...ruLocale,
    ...elementRuLocale,
  },
  en: {
    ...enLocale,
    ...elementEnLocale,
  },
  zh: {
    ...zhLocale,
    ...elementZhLocale,
  },
  vi: {
    ...viLocale,
    ...elementViLocale,
  },
};

navigator.browserLanguage = undefined;

export function getLanguage() {
  const chooseLanguage = Cookies.get('language');
  if (chooseLanguage) {
    return chooseLanguage;
  }

  // if has not choose language
  const language = (navigator.language || navigator.browserLanguage).toLowerCase();
  const locales = Object.keys(messages);
  for (const locale of locales) {
    if (language.indexOf(locale) > -1) {
      return locale;
    }
  }
  return 'ru_RU';
}
const i18n = new VueI18n({
  // set locale
  // options: ru | en | vi | zh
  locale: getLanguage(),
  // set locale messages
  messages,
});

export default i18n;
