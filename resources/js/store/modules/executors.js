
const state = {
  data: [
    {
      executor_id: '1',
      value: 'lab1',
      label: 'Электролаборатория ООО ЭлектроСити 1',
      photo: '/favicon/favicon-32x32.png',
    },
    {
      executor_id: '2',
      value: 'lab2',
      label: 'Электролаборатория ООО ЭлектроСити 2',
      photo: '/favicon/android-icon-192x192.png',
    },
    {
      executor_id: '3',
      value: 'lab3',
      label: 'Электролаборатория ООО ЭлектроСити 3',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      executor_id: '4',
      value: 'lab4',
      label: 'Электролаборатория ООО ЭлектроСити 4',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      executor_id: '5',
      value: 'lab5',
      label: 'Электролаборатория ООО ЭлектроСити 5',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      executor_id: '6',
      value: 'lab6',
      label: 'Электролаборатория ООО ЭлектроСити 6',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      executor_id: '7',
      value: 'lab7',
      label: 'Электролаборатория ООО ЭлектроСити 7',
      photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
    },
    {
      executor_id: '8',
      value: 'lab8',
      label: 'Электролаборатория ООО ЭлектроСити 8',
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
      return state.data.find(el => Number(el.executor_id) === id);
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
