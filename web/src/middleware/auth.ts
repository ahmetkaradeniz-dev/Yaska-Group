export default defineNuxtRouteMiddleware((to) => {
    const authStore = useAuthStore()
    const user = authStore.$state.user

    if (!user && (to.path === '/account' || to.name === 'blog-id')) {
        return navigateTo('/login')
    } else if (user && (to.path === '/login' || to.path === '/register')) {
        return navigateTo('/')
    }
})