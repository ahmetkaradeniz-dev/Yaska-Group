import { defineStore } from 'pinia'
import { AuthLoginData, AuthLoginResponseData, AuthUser } from '@/types/auth'
import { axiosInsAuth } from '@axios'
import Result from '@/types/result'

export const useAuthStore = defineStore('AuthStore', {
    actions: {
        login(data: AuthLoginData) {
            return axiosInsAuth.post<Result<AuthLoginResponseData>>('auth/login', data)
        },
        getUser() {
            return axiosInsAuth.get<Result<AuthUser>>('auth/user')
        },
        logout() {
            return axiosInsAuth.get<Result<any>>('auth/logout')
        },
    },
    persist: false
})