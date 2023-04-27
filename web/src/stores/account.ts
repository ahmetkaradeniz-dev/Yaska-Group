import Result from "~/types";
import http from "@/http"
import { AllBlogResponse, BlogQueryParams } from "~/types/blog";

export const useAccountStore = defineStore('AccountStore', {
    state: () => ({
        module: 'account'
    }),
    actions: {
        async blog(params: BlogQueryParams) { return http.get<Result<AllBlogResponse>>(`${this.module}/blog`, { params }) },
    },
    persist: true
})

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useBlogStore, import.meta.hot))
}