import { UserRole, UserStatus } from '@/types/user';
import Swal, { SweetAlertIcon } from 'sweetalert2';
import dayjs from "dayjs"
import relativeTime from "dayjs/plugin/relativeTime"
import { BlogStatus } from '@/types/blog';
dayjs.extend(relativeTime)

export const toastMessage = (message: string, icon: SweetAlertIcon = 'success') => {
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
  })

  Toast.fire({
    icon,
    title: message
  })
}

export const getTotalPage = (count: number, take: number) => {
  return Math.max(Math.ceil(count / take), 1)
}

export const confirmMessage = (callback: any) => {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
  })

  swalWithBootstrapButtons.fire({
    title: 'Are you sure?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      callback()
    }
  })
}

export const getUserRoleList = () => {
  const roles = [
    { title: UserRole.ADMIN, value: UserRole.ADMIN },
    { title: UserRole.USER, value: UserRole.USER },
  ]
  return roles
}


export const getUserStatusList = () => {
  const status = [
    { title: UserStatus.ACTIVE, value: UserStatus.ACTIVE },
    { title: UserStatus.FORBIDDEN, value: UserStatus.FORBIDDEN },
  ]
  return status
}


export const dateFromNow = (date: Date) => {
  return dayjs(date).fromNow()
}

export const dateFormat = (date: Date, format: string = 'YYYY-MM-DD HH:mm:ss') => {
  return dayjs(date).format(format)
}

export const getBlogStatusList = () => {
  const status = [
    { title: BlogStatus.ACTIVE, value: BlogStatus.ACTIVE },
    { title: BlogStatus.PASSIVE, value: BlogStatus.PASSIVE },
  ]
  return status
}