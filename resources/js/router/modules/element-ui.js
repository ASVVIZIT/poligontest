import Content from '@/content';
const elementUiRoutes = {
  path: '/form',
  component: Content,
  redirect: '/form/index',
  name: 'Element UI',
  meta: {
    title: 'elementUi',
    icon: 'layout',
    permissions: ['view menu element ui'],
  },
  children: [
    {
      path: '/form',
      name: 'Form',
      component: () => import('@/views/form/index'),
      meta: { title: 'form', icon: 'form', noCache: false },
    },
    {
      path: '/form/icons',
      name: 'Icons',
      component: () => import('@/views/form/icons/index'),
      meta: { title: 'icons', icon: 'icon', noCache: false },
    },
    {
      path: '/form/ElementUI/tab',
      name: 'Tab',
      component: () => import('@/views/form/ElementUI/tab/index'),
      meta: { title: 'tab', icon: 'tab', noCache: false },
    },
  ],
};

export default elementUiRoutes;
