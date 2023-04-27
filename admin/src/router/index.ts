import { canNavigate } from '@layouts/plugins/casl'
import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import routes from '~pages'
import { getHomeRouteForLoggedInUser, getUserData, isUserLoggedIn } from './utils'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: to => {
        const userData = JSON.parse(localStorage.getItem('userData') || '{}')
        const userRole = userData && userData?.role ? userData.role : null

        if (userRole)
          return { name: 'user-list' }
        return { name: 'auth-login', query: to.query }
      },
    },
    ...setupLayouts(routes),
  ],
  scrollBehavior() {
    return { top: 0 }
  },
})

router.beforeEach((to, _, next) => {
  const isLoggedIn = isUserLoggedIn()

  if (!canNavigate(to)) {
    if (!isLoggedIn)
      return next({ name: 'auth-login', query: { to: to.name !== 'index' ? to.fullPath : undefined } })
    return next({ name: 'not-authorized' })
  }

  if (to.meta.redirectIfLoggedIn && isLoggedIn) {
    const userData = getUserData()
    next(getHomeRouteForLoggedInUser(userData.role))
  }

  return next()
})

export default router
