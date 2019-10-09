import '@mdi/font/css/materialdesignicons.css'; // Ensure you are using css-loader
import Vue from 'vue';
import Cookies from 'js-cookie';
import ElementUI from 'element-ui';
import App from './views/App';
import store from './store';
import router from '@/router';
import i18n from './lang'; // Internationalization
import '@/icons'; // icon
import '@/permission'; // permission control
import * as filters from './filters'; // global filters
import colors from 'vuetify/lib/util/colors';
import LRU from 'lru-cache';
import Vuetify from 'vuetify';

Vue.use(Vuetify);

const themeCache = new LRU({
  max: 50,
  maxAge: 1000 * 60 * 60, // 1 hour
});

const vuetify = new Vuetify({
  theme: {
    themes: {
      light: {
        primary: {
          base: colors.blue.darken2,
          darken2: colors.blue.darken1,
        },
        secondary: colors.grey.lighten3,
        accent: colors.shades.black,
        error: colors.red.accent3,
      },
      dark: {
        primary: colors.blue.lighten1,
      },
    },
    options: {
      themeCache,
      minifyTheme: function(css) {
        return process.env.NODE_ENV === 'production'
          ? css.replace(/[\r\n|\r|\n]/g, '')
          : css;
      },
    },
  },
});

export default new Vuetify({
  icons: {
    iconfont: 'mdi',
  },
});

Vue.use(ElementUI, {
  size: Cookies.get('size') || 'medium', // set element-ui default size
  i18n: (key, value) => i18n.t(key, value),
});

// register global utility filters.
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key]);
});

Vue.config.productionTip = false;

new Vue({
  el: '#app',
  vuetify,
  router,
  store,
  i18n,
  render: h => h(App),
});
