<script setup lang="ts">
import { Blog, BlogComment, BlogCommentQueryParams } from '~/types/blog';

definePageMeta({
    middleware: ['auth']
})

const blogStore = useBlogStore()
const authStore = useAuthStore()
const route = useRoute()

const state = reactive({
    id: Number(route.params.id),
    loading: true,
    blog: {} as Blog,
    is_liked: false,
    likeBlog: false,
    commentBlog: false,
    commentLoad: false,
    comment: '',
    commentQueryParams: {
        page: 1,
        take: 10,
    } as BlogCommentQueryParams,
    comments: [] as BlogComment[],
    commentTotal: 0 as number
})

const fetchComments = async () => {
    try {
        const response = await blogStore.comments(state.id, state.commentQueryParams)
        if (response.data.success) {
            state.comments = response.data.data.comments
            state.commentTotal = response.data.data.total
        }
    } catch {

    } finally {
        state.loading = false
    }
}

const likeBlog = async () => {
    try {
        if (!state.is_liked) {
            const response = await blogStore.like(state.id)
            if (response.data.success) {
                state.is_liked = true
                state.blog.like_count++
            }
        } else {
            const response = await blogStore.unlike(state.id)
            if (response.data.success) {
                state.is_liked = false
                state.blog.like_count--
            }
        }
    } catch {

    } finally {

    }
}

const comment = async () => {
    try {
        const response = await blogStore.comment(state.id, state.comment)
        if (response.data.success) {
            state.blog.comment_count++
            state.comments.push({ author: authStore.$state.user?.first_name + ' ' + authStore.$state.user?.last_name, comment: state.comment })
        }
    } catch {

    } finally {

    }
}

const loadMoreComment = async () => {
    try {
        state.commentLoad = true
        state.commentQueryParams.page++
        const response = await blogStore.comments(state.id, state.commentQueryParams)
        state.comments.push(...response.data.data.comments)
    } catch {

    } finally {
        state.commentLoad = false
    }
}

onMounted(async () => {
    try {
        const response = await blogStore.get(state.id)
        if (response.data.success) {
            state.blog = response.data.data.blog
            state.is_liked = response.data.data.is_liked
        }
        fetchComments()
    } catch {

    } finally {
        state.loading = false
    }
})

</script>
<template>
    <main class="section-padding">
        <div class="container">
            <div v-if="!state.loading">
                <div class="mb-6">
                    <h1 class="font-semibold text-3xl text-darkBlue mb-6">{{ state.blog.title }}
                    </h1>
                    <div class="overflow-hidden relative">
                        <img class="rounded h-[500px] object-cover w-full"
                            :src="state.blog.image_url || 'https://cdn.shopify.com/s/files/1/0070/7032/files/how-to-start-a-blog-illustration.png?format=jpg&quality=90&v=1595363254'"
                            :alt="state.blog.image_url">
                        <div class="absolute right-[10px] bottom-[10px] flex gap-x-2 items-center">
                            <div class="bg-darkBlue text-white text-sm p-2 rounded">
                                {{ dateFromNow(state.blog.created_at) }}
                            </div>
                            <div class="bg-darkBlue text-white text-sm p-2 rounded">
                                {{ calculateReadingTime(state.blog.content) }} Minute
                            </div>
                            <div class="bg-darkBlue text-white text-sm p-2 rounded">
                                Author: {{ state.blog.author }}
                            </div>
                        </div>
                    </div>
                    <!-- V-html içeriği -->
                    <div class="py-8 blog-content border-b border-text" v-html="state.blog.content"></div>
                </div>
                <!-- Beğen ve Yorum Yap -->
                <div class="flex justify-end">
                    <div class="gap-x-3 flex">
                        <button @click="state.commentBlog = !state.commentBlog"
                            class="text-sm bg-text/20 text-darkBlue rounded px-4 py-2 flex justify-center items-center min-w-[100px] gap-x-2">
                            Yorum Yap
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M20 17.17L18.83 16H4V4h16v13.17zM20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2z" />
                            </svg>
                        </button>
                        <div>
                            <button @click="likeBlog()"
                                class="min-w-[100px] text-darkBlue flex items-center justify-center text-sm gap-x-2 bg-text/20 rounded px-4 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                    <path :class="state.is_liked ? 'fill-[#D21312]' : ''" fill="#3AAAA6"
                                        d="m12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                                <b>{{ state.blog.like_count }}</b>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Yorum Yap -->
                <div v-if="state.commentBlog" class="w-10/12 mx-auto p-3">
                    <div class="mb-4 px-2">
                        <form @submit.prevent="comment">
                            <textarea v-model="state.comment" placeholder="Comment..."
                                class="h-[120px] border border-text/60 rounded-md p-3 w-full resize-none"
                                required></textarea>
                            <div class="text-center">
                                <button type="submit" class="btn">Gönder</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Yorumlar -->
                <div class="w-10/12 mx-auto mt-2">
                    <div class="text-center mb-4 text-[20px] font-semibold flex items-center justify-center">
                        Yorumlar
                        <span class="text-sm ml-2 bg-text/20 px-2">{{ state.blog.comment_count }}</span>
                    </div>
                    <div class="px-4">
                        <div v-for="comment, index in state.comments" :key="index"
                            class="border border-text/60 rounded-md p-3 mb-4">
                            <div class="flex flex-col gap-y-2">
                                <span class="text-black">
                                    {{ comment.author }}
                                </span>
                                <div class="text-text">{{ comment.comment }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <button v-if="state.commentTotal > state.comments.length" :disabled="false" @click="loadMoreComment"
                            class="btn hover:bg-white border border-darkBlue duration-200 hover:text-darkBlue">
                            Load More
                        </button>
                    </div>
                </div>
            </div>
            <div v-else>Loading...</div>
        </div>
    </main>
</template>

<style>
.blog-content p {
    padding-bottom: 16px;
    line-height: 24px;
    color: #848895;
}

.blog-content img {
    padding-bottom: 16px;
    object-fit: cover;
    height: 300px;
}
</style>