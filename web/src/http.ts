import axios, { AxiosInstance, AxiosRequestConfig, AxiosError, AxiosResponse } from 'axios'
import { useAuthStore } from './stores/auth'

const apiUrl: string = 'https://yaska-group-interview-task-api.ahmetkaradeniz.dev//api/'

const axiosIns: AxiosInstance = axios.create({
    baseURL: apiUrl
})

axiosIns.interceptors.request.use((config: AxiosRequestConfig): any => {
    const authStore = useAuthStore()
    config.headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...config.headers,
        'Authorization': `${authStore.$state.token?.type} ${authStore.$state.token?.access}`,
    }
    return config
}, (error: AxiosError) => {
    console.log(error, 'axios interceptors request')
})

axiosIns.interceptors.response.use((response: AxiosResponse) => {
    return response
}, (error: AxiosError) => {
    return Promise.reject(error.response?.data)
})

export default axiosIns

