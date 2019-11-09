
const state = {
  data: [
    {
      orderer_id: '1',
      value: 'pyterochka',
      label: 'Магазин OOO "Пятерочка"',
      photo: 'https://lh3.googleusercontent.com/Y6tKwr5HVTXuBoqau16KIB0AW9ku0eqQNTAxu8M3KHPeDwf3Yls2tJ0q83tt-cI7fw=s180-rw',
    },
    {
      orderer_id: '2',
      value: 'mcd',
      label: 'McDonald',
      photo: 'https://upload.wikimedia.org/wikipedia/commons/5/50/McDonald%27s_SVG_logo.svg',
    },
    {
      orderer_id: '3',
      value: 'kfc',
      label: 'KFC',
      photo: 'http://www.kfcku.com/themes/kfc_indonesia/images/kfc-indonesia-logo.png',
    },
    {
      orderer_id: '4',
      value: 'pizzahut',
      label: 'Pizza Hut',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      orderer_id: '5',
      value: 'pizzahut 2',
      label: 'Pizza Hut ИНН 742342348998',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      orderer_id: '6',
      value: 'pizzahut 3',
      label: 'Pizza Hut ИНН 343453458998',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      orderer_id: '7',
      value: 'pizzahut 4',
      label: 'Pizza Hut ИНН 639459349898 Текст...длинный',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      orderer_id: '8',
      value: 'pizzahut 5',
      label: 'Pizza Hut ИНН 539454239898 Текст...длинный',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
  ],
};

const mutations = {
  //
};

const actions = {
  //
};

const getters = {
  getById(state) {
    return (id) => {
      return state.data.find(el => Number(el.orderer_id) === id);
    };
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
