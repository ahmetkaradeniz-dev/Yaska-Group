export interface Blog {
    id: number
    image_url: string
    title: string
    content: string
    author: string
    like_count: number
    comment_count: number
    created_at: Date
}

export enum Column {
    CREATED_AT = 'created_at'
}

export enum Direction {
    DESC = 'DESC',
    ASC = 'ASC'
}

export interface BlogQueryParams {
    page: number
    take: number
    column: Column
    direction: Direction
}

export interface AllBlogResponse {
    blog: Blog[]
    total: number
}

export interface BlogResponse {
    blog: Blog
    is_liked: boolean
}

export interface BlogData {
    title: string
    content: string
    file: any
    published_date: string
}

export interface BlogComment {
    author: string
    comment: string
}

export interface BlogCommentResponse {
    comments: BlogComment[]
    total: number
}

export interface BlogCommentQueryParams {
    page: number
    take: number
}