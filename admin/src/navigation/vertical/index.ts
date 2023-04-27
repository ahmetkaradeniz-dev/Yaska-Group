import type { VerticalNavItems } from '@/@layouts/types'

export default [
  {
    i18n: 'Users',
    icon: { icon: 'tabler-user' },
    to: 'user-list'
  },
  {
    i18n: 'Blog',
    icon: { icon: 'tabler-blockquote' },
    children: [
      {
        i18n: 'Comments', icon: { icon: 'tabler-messages' }, children: [
          { i18n: 'List', to: 'blog-comment-list', icon: { icon: 'tabler-list' } },
          { disable: true, i18n: 'Edit', to: { name: 'blog-comment-edit-id', params: { id: 0 } }, icon: { icon: 'tabler-edit' } }
        ],
      },
      { i18n: 'List', to: 'blog-list', icon: { icon: 'tabler-list' } },
      { disable: true, i18n: 'Edit', to: { name: 'blog-edit-id', params: { id: 0 } }, icon: { icon: 'tabler-edit' } }
    ],
  },
] as VerticalNavItems