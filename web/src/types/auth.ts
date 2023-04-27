export interface LoginData {
    user_name: string
    password: string
}

export interface RegisterData extends LoginData {
    first_name: string
    last_name: string,
    email: string
}

export enum UserRole {
    ADMIN = 'Admin',
    USER = 'User'
}

export interface User {
    id: number
    role: UserRole
    first_name: string
    last_name: string
    email: string
    user_name: string
}

export interface Token {
    type: string
    access: string
}

export interface LoginResponse {
    user: User
    token: Token
}