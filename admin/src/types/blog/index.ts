import { User } from "../user"

export enum BlogStatus {
    ACTIVE = 'Active',
    PASSIVE = 'Passive'
}

export interface Blog {
    id: number
    user: User
    status: BlogStatus
    title: string
    content: string
    image_url: string
    published_date: Date
    like_count: number
    comment_count: number
    createdAt: Date
}

export interface Filter {
    user_id: number
    status: BlogStatus
    published_date: Date
}

export interface BlogParams {
    query: string
    take: number
    page: number
    filters: Filter
}

export interface BlogAllData {
    blog: Blog[]
    total: number
}


export interface BlogData {
    blog: Blog
}


export interface BlogComment {
    id: number
    user: User
    blog: Blog
    comment: string
    createdAt: Date
}

export interface BlogCommentData {
    comment: BlogComment
}


export interface CommentFilter {
    user_id: number
    blog_id: number
}

export interface BlogCommentParams {
    query: string
    take: number
    page: number
    filters: CommentFilter
}


export interface BlogCommentAllData {
    comments: BlogComment[]
    total: number
}
