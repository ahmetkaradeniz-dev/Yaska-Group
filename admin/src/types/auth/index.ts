import { UserRole } from "../user"

export interface AuthLoginData {
    user_name: string | null
    password: string | null
}

export interface AuthUser {
    id: number
    role: UserRole
    first_name: string
    last_name: string
    email: string
}

export interface Token {
    type: string
    access: string
}

export interface AuthLoginResponseData {
    user: AuthUser
    token: Token
}