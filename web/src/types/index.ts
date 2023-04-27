export default interface Result<T = null> {
    success: boolean
    message: string
    errors: Errors
    data: T
}

export interface Errors {

}