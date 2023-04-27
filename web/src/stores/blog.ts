import Result from "~/types";
import http from "@/http"
import { AllBlogResponse, BlogCommentQueryParams, BlogCommentResponse, BlogData, BlogQueryParams, BlogResponse } from "~/types/blog";

export const useBlogStore = defineStore('BlogStore', {
    state: () => ({
        module: 'blog'
    }),
    actions: {
        async getAll(params: BlogQueryParams) { return http.get<Result<AllBlogResponse>>(`${this.module}`, { params }) },
        async get(id: number) { return http.get<Result<BlogResponse>>(`${this.module}/${id}`) },
        async create(data: BlogData) {
            const formData = new FormData()
            formData.append('title', data.title)
            formData.append('content', data.content)
            formData.append('published_date', data.published_date || '')
            formData.append('file', data.file)
            return http.post<Result>(`${this.module}`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                }
            })
        },
        async update() { },
        async delete(id: number) { return http.delete<Result>(`${this.module}/${id}`) },
        async like(id: number) { return http.post<Result>(`${this.module}/like/${id}`) },
        async unlike(id: number) { return http.post<Result>(`${this.module}/unlike/${id}`) },
        async comments(id: number, params: BlogCommentQueryParams) { return http.get<Result<BlogCommentResponse>>(`${this.module}/comments/${id}`, { params }) },
        async comment(id: number, comment: string) { return http.post<Result>(`${this.module}/comment/${id}`, { comment }) },
    },
    persist: true
})

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useBlogStore, import.meta.hot))
}