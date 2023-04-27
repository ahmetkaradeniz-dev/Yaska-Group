<script setup lang="ts">
import BlogTablePreview from "@/components/blog/TablePreview.vue"
import { useBlogStore } from '@/store/blog';
import { Blog, BlogParams } from "@/types/blog";

interface Emits {
    (e: 'update:blogId', blogId: number): void
}

interface Props {
    blogId?: number | null
}

const props = defineProps<Props>()
const emits = defineEmits<Emits>()

const store = useBlogStore()

const state = reactive({
    blogId: props.blogId as number | null,
    queryParams: {
        query: null,
        order: 'DESC',
        take: 10,
        page: 1,
    } as unknown as BlogParams,
    items: [] as any
})

const fetchData = (a: any) => {
    if (!a.isTrusted && !state.queryParams.query) return
    store.getAll(state.queryParams).then(response => {
        const blog = response.data.data.blog
        state.items = blog.map((blog: Blog) => {
            return {
                title: null,
                value: blog.id,
                blog
            }
        })
    })
}

watch(state.queryParams, fetchData)
</script>

<template>
    <VSelect @click:control="fetchData" @click:input="$emit('update:blogId', $event)" v-model="state.blogId"
        :items="state.items" label="Select Blog" clearable clear-icon="tabler-x">
        <template #prepend-item>
            <v-list-item>
                <v-text-field v-model="state.queryParams.query" placeholder="search"></v-text-field>
            </v-list-item>
            <v-divider class="mt-2"></v-divider>
        </template>
        <template #selection="{ item }">
            {{ item.raw.blog.title }}
        </template>
        <template v-slot:item="{ item, props }">
            <v-list-item v-bind="props" :title="null">
                <BlogTablePreview :blog="item.raw.blog" />
            </v-list-item>
        </template>
    </VSelect>
</template>