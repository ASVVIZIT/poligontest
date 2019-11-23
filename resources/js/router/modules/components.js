/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout';
import elementUiRoutes from '@/router/modules/element-ui';
import tableRoutes from '@/router/modules/table';
import nestedRoutes from '@/router/modules/nested';
import errorRoutes from '@/router/modules/error';
import excelRoutes from '@/router/modules/excel';
import chartsRoutes from '@/router/modules/charts';
const componentRoutes = {
  path: '/components',
  component: Layout,
  redirect: 'noredirect',
  name: 'ComponentDemo',
  alwaysShow: true,
  meta: {
    title: 'components',
    icon: 'component',
    permissions: ['view menu components'],
  },
  children: [
    elementUiRoutes,
    tableRoutes,
    nestedRoutes,
    errorRoutes,
    excelRoutes,
    chartsRoutes,
    {
      path: '/theme',
      component: () => import('@/views/theme/index'),
      redirect: 'noredirect',
      children: [
        {
          path: 'index',
          component: () => import('@/views/theme/index'),
          name: 'Theme',
          meta: { title: 'theme', icon: 'theme' },
        },
      ],
    },
    {
      path: '/clipboard',
      component: () => import('@/views/clipboard/index'),
      redirect: 'noredirect',
      meta: { permissions: ['view menu clipboard'] },
      children: [
        {
          path: 'index',
          component: () => import('@/views/clipboard/index'),
          name: 'ClipboardDemo',
          meta: { title: 'clipboardDemo', icon: 'clipboard', roles: ['admin', 'moderator', 'manager', 'editor', 'user'] },
        },
      ],
    },
    {
      path: '/components/i18n',
      component: () => import('@/views/i18n'),
      meta: { permissions: ['view menu i18n'] },
      children: [
        {
          path: 'index',
          component: () => import('@/views/i18n'),
          name: 'I18n',
          meta: { title: 'i18n', icon: 'international' },
        },
      ],
    },
    {
      path: '/components/tinymce',
      component: () => import('@/views/components-demo/Tinymce'),
      name: 'Tiny',
      meta: { title: 'tiny' },
      children: [
        {
          path: '/components/tinymce',
          component: () => import('@/views/components-demo/Tinymce'),
          name: 'TinymceDemo',
          meta: { title: 'tinymce', noCache: true },
        },
        {
          path: '/components/markdown',
          component: () => import('@/views/components-demo/Markdown'),
          name: 'MarkdownDemo',
          meta: { title: 'markdown', noCache: true },
        },
      ],
    },
    {
      path: '/components/json-editor',
      component: () => import('@/views/components-demo/JsonEditor'),
      name: 'JsonEditorDemo',
      meta: { title: 'jsonEditor' },
    },
    {
      path: '/components/splitpane',
      component: () => import('@/views/components-demo/SplitPane'),
      name: 'SplitpaneDemo',
      meta: { title: 'splitPane' },
    },
    {
      path: '/components/avatar-upload',
      component: () => import('@/views/components-demo/AvatarUpload'),
      name: 'AvatarUploadDemo',
      meta: { title: 'avatarUpload' },
    },
    {
      path: '/components/dropzone',
      component: () => import('@/views/components-demo/Dropzone'),
      name: 'DropzoneDemo',
      meta: { title: 'dropzone' },
    },
    {
      path: '/components/sticky',
      component: () => import('@/views/components-demo/Sticky'),
      name: 'StickyDemo',
      meta: { title: 'sticky' },
    },
    {
      path: '/components/back-to-top',
      component: () => import('@/views/components-demo/BackToTop'),
      name: 'BackToTopDemo',
      meta: { title: 'backToTop' },
    },
    {
      path: '/components/count-to',
      component: () => import('@/views/components-demo/CountTo'),
      name: 'CountToDemo',
      meta: { title: 'countTo' },
    },
    {
      path: '/components/mixin',
      component: () => import('@/views/components-demo/Mixin'),
      name: 'ComponentMixinDemo',
      meta: { title: 'componentMixin' },
    },
    {
      path: '/components/drag-dialog',
      component: () => import('@/views/components-demo/DragDialog'),
      name: 'DragDialogDemo',
      meta: { title: 'dragDialog' },
    },
    {
      path: '/components/drag-select',
      component: () => import('@/views/components-demo/DragSelect'),
      name: 'DragSelectDemo',
      meta: { title: 'dragSelect' },
    },
    {
      path: '/components/dnd-list',
      component: () => import('@/views/components-demo/DndList'),
      name: 'DndListDemo',
      meta: { title: 'dndList' },
    },
    {
      path: '/components/drag-kanban',
      component: () => import('@/views/components-demo/DragKanban'),
      name: 'DragKanbanDemo',
      meta: { title: 'dragKanban' },
    },
  ],
};

export default componentRoutes;
