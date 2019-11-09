import Layout from '@/layout';
const orderRoutes = {
  path: '/',
  component: Layout,
  name: 'orders',
  meta: {
    title: 'orders',
    icon: 'list',
    permissions: ['view menu order'],
  },
  children: [
    {
      path: '/orders/order_list',
      component: () => import('@/views/order/ListOrder'),
      name: 'OrdersList',
      meta: { title: 'OrdersList', icon: 'tree' },
    },
    {
      path: '/orders/order_list_test',
      component: () => import('@/views/order/List'),
      name: 'OrdersListTest',
      meta: { title: 'OrdersListTest', icon: 'tree' },
    },
    {
      path: 'orders/order/create',
      component: () => import('@/views/order/form/formOrder/Create'),
      name: 'CreateOrder',
      meta: { title: 'CreateOrder', icon: 'edit', permissions: ['manage order'] },
      hidden: true,
    },
    {
      path: 'orders/order/edit/:id(\\d+)',
      component: () => import('@/views/order/form/formOrder/Edit'),
      name: 'EditOrder',
      meta: { title: 'EditOrder', noCache: true, permissions: ['manage order'] },
      hidden: true,
    },
    {
      path: 'orders/order/create',
      component: () => import('@/views/order/form/formOrder/Create'),
      name: 'CreateOrder',
      meta: { title: 'CreateOrder', icon: 'create', permissions: ['manage order'] },
      hidden: true,
    },
    {
      path: 'reports/report/edit/:id(\\d+)',
      component: () => import('@/views/order/form/formReport/Edit'),
      name: 'EditReport',
      meta: { title: 'EditReport', noCache: true, permissions: ['manage report'] },
      hidden: true,
    },
    {
      path: 'reports/report/create',
      component: () => import('@/views/order/form/formReport/Create'),
      name: 'CreateReport',
      meta: { title: 'CreateReport', icon: 'create', permissions: ['manage report'] },
      hidden: true,
    },
  ],
};
export default orderRoutes;
