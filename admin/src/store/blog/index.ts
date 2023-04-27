import { BlogAllData, BlogCommentAllData, BlogCommentData, BlogCommentParams, BlogData, BlogParams } from '@/types/blog'
import Result from '@/types/result'
import axios from '@axios'
import { defineStore } from 'pinia'

export const useBlogStore = defineStore('BlogStore', {
  actions: {
    getAll(params: BlogParams) { return axios.get<Result<BlogAllData>>('blog/getAllByAdmin', { params }) },
    get(id: number) { return axios.get<Result<BlogData>>('blog/getByAdmin/' + id) },
    delete(id: number) { return axios.delete<Result<null>>('blog/' + id) },
    update(id: number, data: any) {
      const formData = new FormData()
      formData.append('title', data.title)
      formData.append('content', data.content)
      formData.append('published_date', data.published_date || '')
      formData.append('status', data.status)
      formData.append('file', data.file || '')
      return axios.post<Result<null>>(`blog/update/${id}`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        }
      })
    },
    comments(params: BlogCommentParams) { return axios.get<Result<BlogCommentAllData>>('blog/comment/getAllByAdmin', { params }) },
    getComment(id: number) { return axios.get<Result<BlogCommentData>>('blog/comment/getByAdmin/' + id) },
    deleteComment(id: number) { return axios.delete<Result<BlogData>>('blog/comment/deleteByAdmin/' + id) },
    updateComment(id: number, data: any) { return axios.put<Result<null>>('blog/comment/updateByAdmin/' + id, data) }
  }
})