<script setup lang="ts">
import { avatarText } from '@/@core/utils/formatters';
import { useUserStore } from '@/store/user';
import { User, UserParams } from '@/types/user';
import UserTablePreview from "@/components/user/TablePreview.vue"

interface Emits {
    (e: 'update:userId', userId: number): void
}

interface Props {
    userId?: number | null
}

const props = defineProps<Props>()
const emits = defineEmits<Emits>()

const store = useUserStore()

const state = reactive({
    userId: props.userId as number | null,
    queryParams: {
        query: null,
        order: 'DESC',
        take: 10,
        page: 1,
    } as unknown as UserParams,
    items: [] as any
})

const fetchData = (a: any) => {
    if (!a.isTrusted && !state.queryParams.query) return
    store.getAll(state.queryParams).then(response => {
        const users = response.data.data.users
        state.items = users.map((user: User) => {
            return {
                title: null,
                value: user.id,
                user
            }
        })
    })
}

watch(state.queryParams, fetchData)

</script>

<template>
    <VSelect @click:control="fetchData" @click:input="$emit('update:userId', $event)" v-model="state.userId"
        :items="state.items" label="Select User" clearable clear-icon="tabler-x">
        <template #prepend-item>
            <v-list-item>
                <v-text-field v-model="state.queryParams.query" placeholder="search"></v-text-field>
            </v-list-item>
            <v-divider class="mt-2"></v-divider>
        </template>
        <template #selection="{ item }">
            {{ item.raw.user.first_name }} {{ item.raw.user.last_name }}
        </template>
        <template v-slot:item="{ item, props }">
            <v-list-item v-bind="props" :title="null">
                <UserTablePreview :user="item.raw.user" />
            </v-list-item>
        </template>
    </VSelect>
</template>