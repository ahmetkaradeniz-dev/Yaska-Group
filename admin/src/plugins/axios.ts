import router from '@/router'
import { logout } from '@/router/utils'
import { AuthUser } from '@/types/auth'
import Result, { ApiErrors } from '@/types/result'
import axios, { AxiosError, AxiosInstance, AxiosRequestConfig, AxiosResponse } from 'axios'
import { ElNotification } from 'element-plus'

const apiUrl: string = import.meta.env.VITE_API_URL

const axiosIns: AxiosInstance = axios.create({
  baseURL: apiUrl
})

export const axiosInsAuth: AxiosInstance = axios.create({
  baseURL: apiUrl
})

axiosIns.interceptors.request.use((config: AxiosRequestConfig): any => {
  config.headers = {
    'Authorization': `Bearer ${localStorage.getItem('accessToken')}`,
    'Accept': 'application/json',


  }
  return config
}, (error: AxiosError) => {
  console.log(error, 'axios interceptors request')
})

axiosInsAuth.interceptors.request.use((config: AxiosRequestConfig): any => {
  config.headers = {
    'Authorization': `Bearer ${localStorage.getItem('accessToken')}`,
    'Accept': 'application/json',

  }
  return config
}, (error: AxiosError) => {
  console.log(error, 'axios interceptors request')
})

axiosIns.interceptors.response.use((response: AxiosResponse) => {
  if (response.data?.message) {
    const icon = response.data.success ? 'success' : 'warning'
    ElNotification({
      duration: 1500,
      type: icon,
      message: response.data.message
    })
  }
  return response
}, async (error: AxiosError<ApiErrors>) => {
  switch (error.response && error.response.status) {
    case 403:
      await router.push({ name: 'not-authorized' })
      break
    default:
      ElNotification({
        duration: 5000,
        type: 'error',
        message: error.response?.data.message
      })
      break
  }
  return Promise.reject(error.response?.data)
})

axiosInsAuth.interceptors.response.use((response: AxiosResponse) => {
  return response
}, (error: AxiosError) => {
  return Promise.reject(error.response?.data)
})

const accessToken = localStorage.getItem('accessToken') || false
if (accessToken) {
  axiosInsAuth.get('auth/user', {
    headers: {
      'Authorization': `Bearer ${accessToken}`
    }
  }).then((response) => {
    localStorage.setItem('userData', JSON.stringify(response.data.data.user))
  }).catch((error: AxiosError) => {

  })
}

export default axiosIns