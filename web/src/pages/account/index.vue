<script setup lang="ts">
import { Blog, BlogData, BlogQueryParams, Column, Direction } from "~/types/blog"

definePageMeta({
    middleware: ['auth']
})

const accountStore = useAccountStore()
const blogStore = useBlogStore()

const state = reactive({
    active: 1,
    load: false,
    loading: true,
    queryParams: {
        page: 1,
        take: 10,
        column: Column.CREATED_AT,
        direction: Direction.DESC
    } as BlogQueryParams,
    blog: [] as Blog[],
    total: 0 as number,
    form: {} as BlogData
})

const fetch = async () => {
    try {
        state.loading = true
        const response = await accountStore.blog(state.queryParams)
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
        const response = await accountStore.blog(state.queryParams)
        state.blog.push(...response.data.data.blog)
    } catch {

    } finally {
        state.load = false
    }
}

const removeBlog = async (blog: Blog, index: number) => {
    try {
        const response = await blogStore.delete(blog.id)
        if (response.data.success) {
            state.blog.splice(index, 1)
        }
    } catch {

    } finally {

    }
}

const addBlog = async () => {
    try {
        //state.form.published_date = state.form.published_date[0] || ''
        const response = await blogStore.create(state.form)
        if (response.data.success) {
            resetForm()
        }
        swal(response.data.message, { icon: response.data.success ? 'success' : 'warning' })
    } catch {

    } finally {

    }
}

const handleUploadedFile = (e: any) => {
    for (const file of e.target.files) {
        state.form.file = file
    }
}

const resetForm = () => {
    state.form.title = ''
    state.form.file = null
    state.form.content = ''
    state.form.published_date = ''
}

watchEffect(() => fetch())
</script>

<template>
    <main class="section-padding">
        <div class="container">
            <button @click="state.active = 1, fetch()" class="btn mr-4">Listele</button>
            <button @click="state.active = 2" class="btn bg-gamboge">Oluştur</button>
            <div v-if="state.active == 2" class="section-padding">
                <div class="font-semibold text-2xl mb-6">Blog Oluştur</div>
                <div>
                    <form @submit.prevent="addBlog">
                        <div class="mb-4">
                            <label class="block mb-2">Image
                            </label>
                            <input @change="handleUploadedFile" type="file" class="w-full border border-darkBlue p-2"
                                accept="image/*" />
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2" for="">Title</label>
                            <input v-model="state.form.title" type="text" class="w-full border border-darkBlue p-2"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2" for="">Published Date (Opsiyonel) </label>
                            <vue-tailwind-datepicker as-single v-model="state.form.published_date"
                                input-classes="w-full border border-darkBlue p-2" />
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2" for="">Content</label>
                            <textarea v-model="state.form.content"
                                class="h-[90px] resize-none border border-darkBlue p-2 w-full" required></textarea>
                        </div>
                        <button class="btn">Create</button>
                    </form>
                </div>
            </div>
            <div v-if="state.active == 1" class="section-padding">
                <div>
                    <select
                        class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        v-model="state.queryParams.direction">
                        <option :value="Direction.DESC">Descending by date added</option>
                        <option :value="Direction.ASC">Ascending by date added</option>
                    </select>
                </div>
                <div v-if="!state.loading">
                    <div v-for="blog, index in state.blog" :key="blog.id"
                        class="bg-text/20 rounded p-4 mb-4 flex items-center justify-between">
                        <b>{{ index + 1 }}. {{ blog.title }}</b>
                        <button @click="removeBlog(blog, index)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M7 21q-.825 0-1.413-.588T5 19V6q-.425 0-.713-.288T4 5q0-.425.288-.713T5 4h4q0-.425.288-.713T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5q0 .425-.288.713T19 6v13q0 .825-.588 1.413T17 21H7Zm2-5q0 .425.288.713T10 17q.425 0 .713-.288T11 16V9q0-.425-.288-.713T10 8q-.425 0-.713.288T9 9v7Zm4 0q0 .425.288.713T14 17q.425 0 .713-.288T15 16V9q0-.425-.288-.713T14 8q-.425 0-.713.288T13 9v7Z" />
                            </svg>
                        </button>
                    </div>
                    <div v-if="state.blog.length == 0">No Data</div>
                </div>
                <div v-else>Loading...</div>
            </div>
        </div>
    </main>
</template>