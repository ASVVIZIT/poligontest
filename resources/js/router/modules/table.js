import Content from '@/content';
const tableRoutes = {
  path: '/table',
  component: Content,
  redirect: '/table/DragTable',
  name: 'Complex Table',
  meta: {
    title: 'table',
    icon: 'table',
    permissions: ['view menu table'],
  },
  children: [
    {
      path: '/table/list',
      component: () => import('@/views/table/DragTable'),
      name: 'DragTableList',
      meta: { title: 'DragTableList' },
    },
    {
      path: '/table/DragTable',
      component: () => import('@/views/table/DragTable'),
      name: 'DragTable',
      meta: { title: 'DragTable' },
    },
    {
      path: '/table/DynamicTable/index',
      component: () => import('@/views/table/DynamicTable/index'),
      name: 'DynamicTable',
      meta: { title: 'DynamicTable' },
    },
    {
      path: '/table/InlineEditTable',
      component: () => import('@/views/table/InlineEditTable'),
      name: 'InlineEditTable',
      meta: { title: 'inlineEditTable' },
    },
    {
      path: '/table/TreeTable',
      component: () => import('@/views/table/TreeTable/TreeTable'),
      name: 'TreeTableDemo',
      meta: { title: 'treeTable' },
    },
    {
      path: '/table/CustomTreeTable',
      component: () => import('@/views/table/TreeTable/CustomTreeTable'),
      name: 'CustomTreeTableDemo',
      meta: { title: 'customTreeTable' },
    },
    {
      path: '/table/ComplexTable',
      component: () => import('@/views/table/ComplexTable'),
      name: 'ComplexTable',
      meta: { title: 'complexTable' },
    },
  ],
};
export default tableRoutes;
