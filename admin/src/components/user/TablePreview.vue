<script setup lang="ts">
import { avatarText } from '@/@core/utils/formatters';
import { User } from '@/types/user';

defineProps<{
    user: User
}>()

</script>
<template>
    <div class="d-flex align-center">
        <VAvatar variant="tonal" class="me-3" size="40">
            <span>{{ avatarText(user.first_name + ' ' + user.last_name) }}</span>
        </VAvatar>
        <div class="d-flex flex-column">
            <h6 class="text-base font-weight-medium list-name">
                {{ user.first_name }} {{ user.last_name }}
            </h6>
            <span class="text-sm text-disabled">{{ user.user_name }}</span>
        </div>
    </div>
</template>