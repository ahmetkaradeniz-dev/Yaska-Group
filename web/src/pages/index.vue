<script setup lang="ts">
import { Blog, BlogQueryParams, Column, Direction } from "~/types/blog"

const blogStore = useBlogStore()

const state = reactive({
    load: false,
    loading: true,
    queryParams: {
        page: 1,
        take: 10,
        column: Column.CREATED_AT,
        direction: Direction.DESC
    } as BlogQueryParams,
    blog: [] as Blog[],
    total: 0 as number
})

const fetch = async () => {
    try {
        state.loading = true
        const response = await blogStore.getAll(state.queryParams)
        state.blog = response.data.data.blog
        state.total = response.data.data.total
    } catch {

    } finally {
        state.loading = false
    }
}

const loadMore = async () => {
    try {
        state.load = true
        state.queryParams.page++
        const response = await blogStore.getAll(state.queryParams)
        state.blog.push(...response.data.data.blog)
    } catch {

    } finally {
        state.load = false
    }
}

watchEffect(() => {
    fetch()
})
</script>
<template>
    <main class="section-padding">
        <section>
            <div class="container">
                <div>
                    <select
                        class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        v-model="state.queryParams.direction">
                        <option :value="Direction.DESC">Descending by date added</option>
                        <option :value="Direction.ASC">Ascending by date added</option>
                    </select>
                </div>
                <div v-if="!state.loading">
                    <div v-if="state.blog.length > 0" class="grid my-12 md:grid-cols-3 grid-cols-1 gap-8">
                        <nuxt-link v-for="blog in state.blog" :key="blog.id" :to="`blog/${blog.id}`" class="group block">
                            <article class="shadow-lg rounded-lg">
                                <div class="overflow-hidden relative">
                                    <img class="group-hover:scale-110 duration-300 object-cover w-full"
                                        :src="blog.image_url || 'https://cdn.shopify.com/s/files/1/0070/7032/files/how-to-start-a-blog-illustration.png?format=jpg&quality=90&v=1595363254'"
                                        :alt="blog.image_url">
                                    <div
                                        class="absolute right-[10px] bottom-[10px] bg-darkBlue text-white text-sm p-2 rounded">
                                        {{ blog.author }}</div>
                                </div>
                                <div class="p-4">
                                    <div
                                        class="h-[85px] group-hover:opacity-60 text-[22px] font-semibold duration-300 line-clamp-2">
                                        {{ blog.title }}
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex justify-start items-center">
                                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M11 14v-2h2v2h-2Zm-4 0v-2h2v2H7Zm8 0v-2h2v2h-2Zm-4 4v-2h2v2h-2Zm-4 0v-2h2v2H7Zm8 0v-2h2v2h-2ZM3 22V4h3V2h2v2h8V2h2v2h3v18H3Zm2-2h14V10H5v10Z" />
                                            </svg>
                                            {{ dateFromNow(blog.created_at) }}
                                        </div>
                                        <!-- Comments and Likes -->
                                        <div class="flex items-center gap-x-2">
                                            <!-- Comments -->
                                            <span class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M20 17.17L18.83 16H4V4h16v13.17zM20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2z" />
                                                </svg>
                                                <span
                                                    class="bg-darkBlue text-white rounded-full flex items-center justify-center h-4 w-4 absolute -right-[5px] -top-[5px] text-[8px]">{{
                                                        blog.comment_count }}</span>
                                            </span>
                                            <!-- Likes -->
                                            <span class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3C4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5C22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1l-.1-.1C7.14 14.24 4 11.39 4 8.5C4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5c0 2.89-3.14 5.74-7.9 10.05z" />
                                                </svg>
                                                <span
                                                    class="bg-darkBlue text-white rounded-full flex items-center justify-center h-4 w-4 absolute -right-[5px] -top-[5px] text-[8px]">{{
                                                        blog.like_count }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </nuxt-link>
                    </div>
                    <div v-else>No Data</div>
                    <div v-if="state.total > state.blog.length" class="text-center py-4">
                        <button :disabled="state.load" @click="loadMore"
                            class="btn hover:bg-white border border-darkBlue duration-200 hover:text-darkBlue">
                            Load More
                        </button>
                    </div>
                </div>
                <div v-else>
                    Loading...
                </div>
            </div>
        </section>
    </main>
</template>