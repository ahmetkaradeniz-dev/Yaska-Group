<script setup lang="ts">
import { Blog } from '@/types/blog';

defineProps<{
    blog: Blog
}>()

</script>
<template>
    <div class="d-flex align-center">
        <VAvatar variant="tonal" class="me-3" size="40">
            <VImg
                :src="blog.image_url || 'https://cdn.shopify.com/s/files/1/0070/7032/files/how-to-start-a-blog-illustration.png?format=jpg&quality=90&v=1595363254'"
                :aspect-ratio="1" cover />
        </VAvatar>
        <div class="d-flex flex-column">
            <h6 class="text-base font-weight-medium list-name">
                {{ blog.title }}
            </h6>
            <span v-if="blog.like_count" class="text-sm text-disabled">
                Like Count : {{ blog.like_count }}
            </span>
            <span v-if="blog.comment_count" class="text-sm text-disabled">
                Comment Count : {{ blog.comment_count }}
            </span>
        </div>
    </div>
</template>