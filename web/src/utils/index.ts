import relativeTime from "dayjs/plugin/relativeTime"
import dayjs from "dayjs"
dayjs.extend(relativeTime)

export const swal = (title: string, options?: { toast?: boolean, position?: string, timer?: number, icon?: string, showConfirmButton?: boolean }) => {
    const { $swal }: any = useNuxtApp()
    const data = {
        title: title,
        toast: true,
        position: 'top-end',
        timer: 3000,
        icon: 'success',
        showConfirmButton: false,
    }
    $swal.fire({
        ...data,
        ...options
    })
}

export const calculateReadingTime = (content: string): number => {
    const wordsPerMinute = 200
    const words = content?.split(' ').length
    const minutes = Math.ceil(words / wordsPerMinute)
    return minutes
}


export const dateFromNow = (date: Date) => {
    return dayjs(date).fromNow()
}