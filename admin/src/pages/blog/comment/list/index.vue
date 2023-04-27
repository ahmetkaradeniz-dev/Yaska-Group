<script setup lang="ts">
import { useBlogStore } from "@/store/blog";
import { Blog, BlogComment, BlogCommentParams } from "@/types/blog";
import { confirmMessage, getTotalPage, dateFromNow, dateFormat, getBlogStatusList } from "@/utils";
import BlogTablePreview from "@/components/blog/TablePreview.vue"
import UserTablePreview from "@/components/user/TablePreview.vue"
import BlogSelect from "@/components/blog/Select.vue"
import UserSelect from "@/components/user/Select.vue"

const blogStore = useBlogStore()

const state = reactive({
  queryParams: {
    query: null,
    take: 10,
    page: 1,
    filters: {
      blog_id: null
    }
  } as unknown as BlogCommentParams,
  comments: [] as BlogComment[],
  totalPage: 1,
  total: 0
})

const fetchBlogComment = () => {
  blogStore.comments(state.queryParams).then(response => {
    state.comments = response.data.data.comments
    state.total = response.data.data.total
    state.totalPage = getTotalPage(state.total, state.queryParams.take)
  })
}

watchEffect(fetchBlogComment)

// 👉 watching current page
watchEffect(() => {
  if (state.queryParams.page > state.totalPage)
    state.queryParams.page = state.totalPage
})

// 👉 Computing pagination data
const paginationData = computed(() => {
  const firstIndex = state.comments.length ? ((state.queryParams.page - 1) * state.queryParams.take) + 1 : 0
  const lastIndex = state.comments.length + ((state.queryParams.page - 1) * state.queryParams.take)
  return `Showing ${firstIndex} to ${lastIndex} of ${state.total} entries`
})

const deleteComment = (comment: BlogComment) => {
  confirmMessage(async () => {
    await blogStore.deleteComment(comment.id)
    fetchBlogComment()
  })
}

</script>

<template>
  <section>
    <VRow>
      <VCol cols="12">
        <VCard title="Filter">
          <!-- 👉 Filters -->
          <VCardText>
            <VRow>
              <!-- 👉 Select Blog -->
              <VCol cols="12" sm="4">
                <BlogSelect v-model="state.queryParams.filters.blog_id" clearable clear-icon="tabler-x" />
              </VCol>
              <!-- 👉 Select User -->
              <VCol cols="12" sm="4">
                <UserSelect v-model="state.queryParams.filters.user_id" clearable clear-icon="tabler-x" />
              </VCol>
            </VRow>
          </VCardText>

          <VCardText class="d-flex flex-wrap py-4 gap-4">
            <div style="width: 80px;">
              <VSelect v-model="state.queryParams.take" density="compact" variant="outlined" :items="[10, 20, 30, 50]" />
            </div>

            <VSpacer />

            <div class="search-filter d-flex align-center flex-wrap gap-4">
              <!-- 👉 Search  -->
              <VTextField v-model="state.queryParams.query" placeholder="search" density="compact" />
            </div>
          </VCardText>

          <VDivider />

          <VTable class="text-no-wrap">
            <!-- 👉 table head -->
            <thead>
              <tr>
                <th scope="col">
                  BLOG
                </th>
                <th scope="col">
                  USER
                </th>
                <th scope="col">
                  COMMENT
                </th>
                <th scope="col">
                  CREATED AT
                </th>
                <th scope="col">
                  ACTIONS
                </th>
              </tr>
            </thead>
            <!-- 👉 table body -->
            <tbody>
              <tr v-for="comment in state.comments " :key="comment.id" style="height: 3.75rem;">
                <td>
                  <BlogTablePreview :blog="comment.blog" />
                </td>
                <td>
                  <UserTablePreview :user="comment.user" />
                </td>
                <td>
                  {{ comment.comment }}
                </td>
                <td>
                  <VChip label color="success" size="small" class="text-capitalize">
                    {{ dateFromNow(comment.createdAt) }}
                    <VTooltip activator="parent" location="top">
                      {{ dateFormat(comment.createdAt) }}
                    </VTooltip>
                  </VChip>
                </td>
                <td>
                  <VBtn :to="{ name: 'blog-comment-edit-id', params: { id: comment.id } }" icon size="x-small"
                    color="default" variant="text">
                    <VIcon size="22" icon="tabler-edit" />
                    <VTooltip activator="parent" location="top">
                      Edit
                    </VTooltip>
                  </VBtn>
                  <VBtn @click="deleteComment(comment)" icon size="x-small" color="default" variant="text">
                    <VIcon size="22" icon="tabler-trash" />
                    <VTooltip activator="parent" location="top">
                      Delete
                    </VTooltip>
                  </VBtn>
                </td>
              </tr>
            </tbody>
            <!-- 👉 table footer  -->
            <tfoot v-show="!state.comments.length">
              <tr>
                <td colspan="5" class="text-center">
                  No Data
                </td>
              </tr>
            </tfoot>
          </VTable>
          <VDivider />
          <VCardText class="d-flex align-center flex-wrap justify-space-between gap-4 py-3 px-5">
            <span class="text-sm text-disabled">
              {{ paginationData }}
            </span>
            <VPagination v-model="state.queryParams.page" size="small" :total-visible="5" :length="state.totalPage" />
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </section>
</template>