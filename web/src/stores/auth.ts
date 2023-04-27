import Result from "~/types";
import { LoginData, LoginResponse, RegisterData, Token, User } from "../types/auth";
import http from "@/http"

export const useAuthStore = defineStore('AuthStore', {
    state: () => ({
        module: 'auth',
        user: null as User | null,
        token: null as Token | null
    }),
    actions: {
        async register(data: RegisterData) { return http.post<Result<LoginResponse>>(`${this.module}/register`, data) },
        async login(data: LoginData) { return http.post<Result<LoginResponse>>(`${this.module}/login`, data) },
        async getUser() { return http.get(`${this.module}/user`) },
        async logout() {
            const router = useRouter()
            const response = await http.post<Result>(`${this.module}/logout`)
            swal(response.data.message, { icon: response.data.success ? 'success' : 'error' })
            this.$reset()
            router.push('/')
        }
    },
    persist: true
})

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useAuthStore, import.meta.hot))
}