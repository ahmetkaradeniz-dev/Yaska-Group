import ability, { initialAbility } from "@/plugins/casl/ability"
import { AuthUser } from "@/types/auth"
import router from "."
import { UserRole } from "@/types/user"

export const isUserLoggedIn = () => !!(localStorage.getItem('userData') && localStorage.getItem('accessToken'))

export const getUserData = (): AuthUser => {
  const stringifiedUserData = localStorage.getItem('userData')

  return stringifiedUserData ? JSON.parse(stringifiedUserData) : null
}

export const logout = async () => {
  localStorage.removeItem('userData')
  localStorage.removeItem('accessToken')
  localStorage.removeItem('userAbilities')
  ability.update(initialAbility)
  await router.push({ name: 'auth-login' })
}

export const getHomeRouteForLoggedInUser = (userRole: UserRole) => {
  if (userRole === UserRole.ADMIN)
    return '/'
  return { name: 'auth-login' }
}