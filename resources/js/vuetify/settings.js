import colors from 'vuetify/lib/util/colors';
import Vue from 'vue';
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
      dark: {
        primary: {
          base: '#3f51b5',
          darken2: colors.blue.darken1,
        },
        secondary: '#000000',
        accent: colors.shades.white,
        error: colors.red.accent3,
      },
      light: {
        primary: colors.blue.darken2,
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

export default vuetify;
