import Result from '@/types/result'
import { UserAllData, UserParams } from '@/types/user'
import axios from '@axios'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('UserStore', {
  actions: {
    getAll(params: UserParams) { return axios.get<Result<UserAllData>>('users', { params }) },
    forbidden(id: number) { return axios.patch<Result<null>>('users/forbidden/' + id) },
    recover(id: number) { return axios.patch<Result<null>>('users/recover/' + id) },
  }
})