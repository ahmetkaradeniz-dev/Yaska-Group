export default interface Result<T> {
    success: boolean
    message: string
    errors: null
    data: T
}

export interface Errors {
    property: string
    children: Errors[]
    constraints: Object
}

export interface ApiErrors {
    success: boolean
    message: string
    errors: Errors[]
    data: null
}