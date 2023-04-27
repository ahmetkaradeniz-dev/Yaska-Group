<script setup lang="ts">
import { avatarText } from '@/@core/utils/formatters';
import { getUserData, logout } from '@/router/utils';
import { AuthUser } from "@/types/auth"

const user: AuthUser = getUserData()

</script>

<template>
  <VBadge dot location="bottom right" offset-x="3" offset-y="3" bordered color="success">
    <VAvatar class="cursor-pointer" variant="tonal">
      <span>{{ avatarText(user.first_name + ' ' + user.last_name) }}</span>
      <!-- SECTION Menu -->
      <VMenu activator="parent" max-width="250" location="bottom end" offset="14px">
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge dot location="bottom right" offset-x="3" offset-y="3" color="success">
                  <VAvatar class="cursor-pointer" variant="tonal">
                    <span>{{ avatarText(user.first_name + ' ' + user.last_name) }}</span>
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ user.first_name }} {{ user.last_name }}
            </VListItemTitle>
            <VListItemSubtitle>{{ user.role }}</VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem link @click="logout()">
            <template #prepend>
              <VIcon class="me-2" icon="tabler-logout" size="22" />
            </template>

            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
