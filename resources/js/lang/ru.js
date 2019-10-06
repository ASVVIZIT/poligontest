export default {
  route: {
    dashboard: 'Панель управления',
    introduction: 'Введение',
    documentation: 'Документация',
    guide: 'Руководство',
    permission: 'Разрешения',
    pagePermission: 'Разрешения для страниц',
    rolePermission: 'Разрешение на роль',
    administrator: 'Администрирование',
    users: 'Пользователи',
    userProfile: 'Профиль пользователей',
    directivePermission: 'Директивы',
    icons: 'Иконки',
    components: 'Компоненты',
    componentIndex: 'Введение',
    tinymce: 'TinyMCE',
    markdown: 'Редактор текса',
    jsonEditor: 'JSON Editor',
    dndList: 'Dnd List',
    splitPane: 'Разделитель панели',
    avatarUpload: 'Загрузить Аватар',
    dropzone: 'Зона сброса файла',
    sticky: 'Липкая кнопка',
    countTo: 'Плавный Счетчик',
    componentMixin: 'Mixin',
    backToTop: 'BackToTop',
    dragDialog: 'Drag Dialog',
    dragSelect: 'Drag Select',
    dragKanban: 'Drag Kanban',
    charts: 'Графики',
    keyboardChart: 'Диаграмма клавиатуры',
    lineChart: 'Линейный график',
    mixChart: 'Смешаная диаграмма',
    example: 'Примеры',
    nested: 'Nested Routes',
    menu1: 'Меню 1',
    'menu1-1': 'Меню 1-1',
    'menu1-2': 'Меню 1-2',
    'menu1-2-1': 'Меню 1-2-1',
    'menu1-2-2': 'Меню 1-2-2',
    'menu1-3': 'Меню 1-3',
    menu2: 'Menu 2',
    'menu2-1': 'Меню 2-1',
    'menu2-2': 'Меню 2-2',
    'menu2-2-1': 'Меню 2-2-1',
    'menu2-2-2': 'Меню 2-2-2',
    'menu2-3': 'Меню 2-3',
    table: 'Таблица',
    dynamicTable: 'Dynamic Table (Пример)',
    dragTable: 'Drag Table (Пример)',
    inlineEditTable: 'Inline Edit (Пример)',
    complexTable: 'Complex Table (Пример)',
    complexTableMain: 'Таблица заказов (Разработка)',
    treeTable: 'Tree Table (Пример)',
    customTreeTable: 'Custom TreeTable (Пример)',
    tab: 'Tab (Пример)',
    form: 'Форма',
    createArticle: 'Создание статьи',
    editArticle: 'Правка статьи',
    articleList: 'статьи',
    errorPages: 'Страница ошибок',
    page401: '401',
    page404: '404',
    errorLog: 'Error Log (Пример)',
    excel: 'Excel',
    exportExcel: 'Export Excel',
    selectExcel: 'Export Selected',
    mergeHeader: 'Merge Header',
    uploadExcel: 'Upload Excel',
    zip: 'Zip (Пример)',
    pdf: 'PDF (Пример)',
    exportZip: 'Export Zip',
    theme: 'Тема',
    clipboardDemo: 'Буфер обмена',
    i18n: 'I18n (Перевод)',
    externalLink: 'Link (Репозиторий разработки)',
    externallink_poligon: 'Link (Репозиторий тестирования)',
    elementUi: 'Element UI',
  },
  headerSearch: {
    placeholder: 'Поиск',
  },
  navbar: {
    logOut: 'Выйти',
    dashboard: 'Панель управления',
    github: 'Github',
    theme: 'Тема',
    size: 'Global Size',
    profile: 'Профиль',
  },
  login: {
    title: 'Форма авторизации',
    logIn: 'Войти',
    username: 'Имя пользотвателя',
    password: 'Введите пароль',
    any: 'Любые',
    thirdparty: 'связаться с нами',
    thirdpartyTips: 'Can not be simulated on local, so please combine you own business simulation! ! !',
    email: 'Введите E-mail',
    lable_Profile: 'Учётка админа',
    lable_Email: 'E-mail',
    lable_password: 'Пароль',
    Switch_message: 'Смена языка прошла успешна',
    Switch_type: 'Success',
  },
  register: {
    title: 'Форма Регистрации',
    Submit: 'Зарегистрироваться и войти',
    username: 'Имя пользотвателя',
    password: 'Введите пароль',
    any: 'Любые',
    thirdparty: 'связаться с нами',
    thirdpartyTips: 'Can not be simulated on local, so please combine you own business simulation! ! !',
    email: 'Введите Email',
  },
  documentation: {
    documentation: 'Документация',
    laravel: 'Laravel',
    github: 'Github Repository',
  },
  permission: {
    addRole: 'Новая роль',
    editPermission: 'Edit Permission',
    roles: 'Ваша роль',
    switchRoles: 'Сменить роль',
    tips: 'В некоторых случаях не подходит использование v-role / v -missions, например компонента Tab элемента или el-table-column и других случаев асинхронного рендеринга, что может быть достигнуто только путем ручной установки v-if с checkRole или / и проверьте разрешение.',
    delete: 'Удалить',
    confirm: 'Подтвердить',
    cancel: 'Отмена',
  },
  guide: {
    description: 'Страница руководства полезна для людей, которые впервые вошли в проект с данным движком. Вы можете кратко представить себе особенности проекта. Основное в нем показано',
    button: 'Показать руководство',
  },
  components: {
    documentation: 'Документация',
    tinymceTips: 'Rich text editor is a core part of management system, but at the same time is a place with lots of problems. In the process of selecting rich texts, I also walked a lot of detours. The common rich text editors in the market are basically used, and the finally chose Tinymce. See documentation for more detailed rich text editor comparisons and introductions.',
    dropzoneTips: 'Because my business has special needs, and has to upload images to qiniu, so instead of a third party, I chose encapsulate it by myself. It is very simple, you can see the detail code in @/components/Dropzone.',
    stickyTips: 'when the page is scrolled to the preset position will be sticky on the top.',
    backToTopTips1: 'When the page is scrolled to the specified position, the Back to Top button appears in the lower right corner',
    backToTopTips2: 'You can customize the style of the button, show / hide, height of appearance, height of the return. If you need a text prompt, you can use element-ui el-tooltip elements externally',
    imageUploadTips: 'Since I was using only the vue@1 version, and it is not compatible with mockjs at the moment, I modified it myself, and if you are going to use it, it is better to use official version.',
  },
  table: {
    description: 'Описание',
    dynamicTips1: 'Фиксированный заголовок, отсортированный по порядку заголовка',
    dynamicTips2: 'Not fixed header, sorted by click order',
    dragTips1: 'Порядок по умолчанию',
    dragTips2: 'Порядок после перетаскивания',
    name: 'Имя',
    title: 'Название',
    importance: 'Важность',
    type: 'Тип',
    remark: 'Замечание',
    search: 'Поиск',
    add: 'Добавить',
    export: 'Экспортировать',
    reviewer: 'Рецензент',
    id: 'ID',
    date: 'Дата',
    author: 'Автор',
    readings: 'Прочитали',
    status: 'Статусы',
    actions: 'Действия',
    edit: 'Изменить',
    publish: 'Опубликовать',
    draft: 'Модерация',
    delete: 'Удалить',
    cancel: 'Отмена',
    confirm: 'Подтвердить',
    keyword: 'Поиск по таблице',
    role: 'Роль',
    Email: 'Почта E-mail',
  },
  errorLog: {
    tips: 'Пожалуйста, нажмите значок ошибки в правом верхнем углу',
    description: 'Now the management system are basically the form of the spa, it enhances the user experience, but it also increases the possibility of page problems, a small negligence may lead to the entire page deadlock. Fortunately Vue provides a way to catch handling exceptions, where you can handle errors or report exceptions.',
    documentation: 'Document introduction',
  },
  excel: {
    export: 'Export',
    selectedExport: 'Выбранные элементы для экспорта',
    placeholder: 'Пожалуйста, введите имя файла (по умолчанию "excel-list")',
  },
  zip: {
    export: 'Export',
    placeholder: 'Пожалуйста, введите имя файла (по умолчанию file.zip)',
  },
  pdf: {
    tips: 'Используется window.print() для реализации функции загрузки pdf.',
  },
  theme: {
    change: 'Выбор темы',
    documentation: 'Theme документация',
    tips: 'Советы: Он отличается от выбора темы на панели навигации двумя разными способами создания скинов, каждый из которых имеет свои сценарии применения. Обратитесь к документации для деталей.',
  },
  tagsView: {
    refresh: 'Обновить',
    close: 'Закрыть',
    closeOthers: 'Закрыть другие',
    closeAll: 'Закрыть всё',
  },
  settings: {
    title: 'Стиль страницы настроек',
    theme: 'Цвет темы',
    tagsView: 'Открыть Tags-View',
    fixedHeader: 'Фиксированная шапка',
    sidebarLogo: 'Sidebar Logo',
  },
  user: {
    'role': 'Роль',
    'password': 'Пароль',
    'confirmPassword': 'Подтвердите пароль',
    'name': 'Имя',
    'email': 'Email Адресс',
  },
  roles: {
    description: {
      SuperAdmin: 'Super Administrator. Имеет доступ и полные разрешения ко всем страницам. Запасной - права такие же как и у Admin',
      admin: 'Administrator. Имеет доступ и полные разрешения ко всем страницам.',
      manager: 'Manager. Имеет доступ и разрешения для большинства страниц, кроме страницы для выдачи и редактирования разрешений.',
      editor: 'Editor. Имеет доступ к большинству страниц, полное разрешения к статьям и соответствующими ресурсами для правки.',
      user: 'Обычный пользователь. Имеет доступ к некоторым страницам доступным только ему',
      visitor: 'Visitor. Имеет доступ к статическим страницам, не должен иметь права на запись',
    },
  },
};
