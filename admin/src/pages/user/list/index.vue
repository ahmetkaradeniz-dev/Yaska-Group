<script setup lang="ts">
import { avatarText } from "@/@core/utils/formatters";
import { useUserStore } from "@/store/user";
import { UserStatus } from "@/types/user";
import { User, UserParams } from '@/types/user';
import { confirmMessage, getTotalPage, getUserRoleList, getUserStatusList, dateFromNow, dateFormat } from "@/utils";
import UserTablePreview from "@/components/user/TablePreview.vue"

const userStore = useUserStore()

const state = reactive({
  queryParams: {
    query: null,
    take: 10,
    page: 1,
    filters: {
      role: null,
      status: null
    }
  } as unknown as UserParams,
  users: [] as User[],
  totalPage: 1,
  total: 0
})

const fetchUsers = () => {
  userStore.getAll(state.queryParams).then(response => {
    state.users = response.data.data.users
    state.total = response.data.data.total
    state.totalPage = getTotalPage(state.total, state.queryParams.take)
  })
}

watchEffect(fetchUsers)

// 👉 watching current page
watchEffect(() => {
  if (state.queryParams.page > state.totalPage)
    state.queryParams.page = state.totalPage
})

// 👉 Computing pagination data
const paginationData = computed(() => {
  const firstIndex = state.users.length ? ((state.queryParams.page - 1) * state.queryParams.take) + 1 : 0
  const lastIndex = state.users.length + ((state.queryParams.page - 1) * state.queryParams.take)
  return `Showing ${firstIndex} to ${lastIndex} of ${state.total} entries`
})

const forbidden = (user: User) => {
  confirmMessage(async () => {
    await userStore.forbidden(user.id)
    fetchUsers()
  })
}

const recoverUser = (user: User) => {
  confirmMessage(async () => {
    await userStore.recover(user.id)
    fetchUsers()
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
              <!-- 👉 Select Role -->
              <VCol cols="12" sm="4">
                <VSelect v-model="state.queryParams.filters.role" label="Select Role" :items="getUserRoleList()" clearable
                  clear-icon="tabler-x" />
              </VCol>
              <!-- 👉 Select Status -->
              <VCol cols="12" sm="4">
                <VSelect v-model="state.queryParams.filters.status" label="Select Status" :items="getUserStatusList()"
                  clearable clear-icon="tabler-x" />
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
                  USER
                </th>
                <th scope="col">
                  E-MAIL
                </th>
                <th scope="col">
                  ROLE
                </th>
                <th scope="col">
                  STATUS
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
              <tr v-for="user in state.users" :key="user.id" style="height: 3.75rem;">
                <td>
                  <UserTablePreview :user="user" />
                </td>
                <td>
                  {{ user.email }}
                </td>
                <td>
                  {{ user.role }}
                </td>
                <td>
                  {{ user.status }}
                </td>
                <td>
                  <VChip label color="success" size="small" class="text-capitalize">
                    {{ dateFromNow(user.createdAt) }}
                    <VTooltip activator="parent" location="top">
                      {{ dateFormat(user.createdAt) }}
                    </VTooltip>
                  </VChip>
                </td>
                <td>
                  <VBtn @click="forbidden(user)" v-if="user.status != UserStatus.FORBIDDEN" icon size="x-small"
                    color="default" variant="text">
                    <VIcon size="22" icon="tabler-user-off" />
                    <VTooltip activator="parent" location="top">
                      Forbidden
                    </VTooltip>
                  </VBtn>
                  <VBtn v-else @click="recoverUser(user)" icon size="x-small" color="default" variant="text">
                    <VIcon size="22" icon="tabler-user-check" />
                    <VTooltip activator="parent" location="top">
                      Recover
                    </VTooltip>
                  </VBtn>
                </td>
              </tr>
            </tbody>
            <!-- 👉 table footer  -->
            <tfoot v-show="!state.users.length">
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