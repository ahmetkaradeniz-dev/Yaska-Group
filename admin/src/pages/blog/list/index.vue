<script setup lang="ts">
import { useBlogStore } from "@/store/blog";
import { Blog, BlogParams } from "@/types/blog";
import { confirmMessage, getTotalPage, dateFromNow, dateFormat, getBlogStatusList } from "@/utils";
import AppDateTimePicker from '@core/components/AppDateTimePicker.vue';
import BlogTablePreview from "@/components/blog/TablePreview.vue"
import UserTablePreview from "@/components/user/TablePreview.vue"
import UserSelect from "@/components/user/Select.vue"

const blogStore = useBlogStore()

const state = reactive({
  queryParams: {
    query: null,
    take: 10,
    page: 1,
    filters: {
      role: null,
      status: null
    }
  } as unknown as BlogParams,
  blog: [] as Blog[],
  totalPage: 1,
  total: 0
})

const fetchBlog = () => {
  blogStore.getAll(state.queryParams).then(response => {
    state.blog = response.data.data.blog
    state.total = response.data.data.total
    state.totalPage = getTotalPage(state.total, state.queryParams.take)
  })
}

watchEffect(fetchBlog)

// 👉 watching current page
watchEffect(() => {
  if (state.queryParams.page > state.totalPage)
    state.queryParams.page = state.totalPage
})

// 👉 Computing pagination data
const paginationData = computed(() => {
  const firstIndex = state.blog.length ? ((state.queryParams.page - 1) * state.queryParams.take) + 1 : 0
  const lastIndex = state.blog.length + ((state.queryParams.page - 1) * state.queryParams.take)
  return `Showing ${firstIndex} to ${lastIndex} of ${state.total} entries`
})

const deleteBlog = (blog: Blog) => {
  confirmMessage(async () => {
    await blogStore.delete(blog.id)
    fetchBlog()
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
              <!-- 👉 Select Status -->
              <VCol cols="12" sm="4">
                <VSelect v-model="state.queryParams.filters.status" label="Select Status" :items="getBlogStatusList()"
                  clearable clear-icon="tabler-x" />
              </VCol>
              <!-- 👉 Select Status -->
              <VCol cols="12" sm="4">
                <UserSelect v-model="state.queryParams.filters.user_id" clearable clear-icon="tabler-x" />
              </VCol>
              <!-- 👉 Select Published Date -->
              <VCol cols="12" md="4">
                <AppDateTimePicker v-model="state.queryParams.filters.published_date" clearable clear-icon="tabler-x"
                  :config="{ mode: 'range', altInput: true, dateFormat: 'Y-m-d', i18n: 'Select Published Date' }"
                  append-inner-icon="tabler-calendar" />
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
                  STATUS
                </th>
                <th scope="col">
                  PUBLISHED DATE
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
              <tr v-for="blog in state.blog " :key="blog.id" style="height: 3.75rem;">
                <td>
                  <BlogTablePreview :blog="blog" />
                </td>
                <td>
                  <UserTablePreview :user="blog.user" />
                </td>
                <td>
                  {{ blog.status }}
                </td>
                <td>
                  <VChip label color="success" size="small" class="text-capitalize">
                    {{ dateFormat(blog.published_date) }}
                  </VChip>
                </td>
                <td>
                  <VChip label color="success" size="small" class="text-capitalize">
                    {{ dateFromNow(blog.createdAt) }}
                    <VTooltip activator="parent" location="top">
                      {{ dateFormat(blog.createdAt) }}
                    </VTooltip>
                  </VChip>
                </td>
                <td>
                  <VBtn :to="{ name: 'blog-edit-id', params: { id: blog.id } }" icon size="x-small" color="default"
                    variant="text">
                    <VIcon size="22" icon="tabler-edit" />
                    <VTooltip activator="parent" location="top">
                      Edit
                    </VTooltip>
                  </VBtn>
                  <VBtn @click="deleteBlog(blog)" icon size="x-small" color="default" variant="text">
                    <VIcon size="22" icon="tabler-trash" />
                    <VTooltip activator="parent" location="top">
                      Delete
                    </VTooltip>
                  </VBtn>
                </td>
              </tr>
            </tbody>
            <!-- 👉 table footer  -->
            <tfoot v-show="!state.blog.length">
              <tr>
                <td colspan="6" class="text-center">
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