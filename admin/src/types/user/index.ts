export enum UserRole {
    ADMIN = "Admin",
    USER = "User"
}

export enum UserStatus {
    ACTIVE = 'Active',
    FORBIDDEN = 'Forbidden'
}

export interface User {
    id: number
    role: UserRole
    user_name: string
    first_name: string
    last_name: string
    email: string
    status: UserStatus
    createdAt: Date
    updatedAt: Date
}

export interface Filter {
    role: string
    status: string
}

export interface UserParams {
    query: string
    take: number
    page: number
    filters: Filter
}

export interface UserAllData {
    users: User[]
    total: number
}