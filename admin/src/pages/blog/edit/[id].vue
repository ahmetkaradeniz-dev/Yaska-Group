<script lang="ts" setup>
import { VForm } from 'vuetify/components';
import { useBlogStore } from '@/store/blog';
import { getBlogStatusList } from '@/utils';
import { Blog } from '@/types/blog';
import { requiredValidator } from '@/@core/utils/validators';

const route = useRoute()
const store = useBlogStore()

const id = Number(route.params.id)

store.get(id).then((response) => {
    state.isLoad = true
    state.blog = response.data.data.blog
})

const refVForm = ref<VForm>()

const state = reactive({
    isLoad: false,
    loading: false,
    file: [] as any[],
    blog: {} as Blog
})

const onSubmit = () => {
    refVForm.value?.validate()
        .then(({ valid: isValid }) => {
            if (isValid) {
                update()
            }
        })
}

async function update() {
    try {
        state.loading = true
        await store.update(id, {
            ...state.blog,
            file: state.file[0]
        })
    } catch {

    } finally {
        state.loading = false
    }
}

</script>

<template>
    <VForm v-if="state.isLoad" ref="refVForm" @submit.prevent="onSubmit">
        <VRow>
            <VCol cols="12" md="9">
                <VCard title="Blog" class="mb-5">
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="12">
                                <VFileInput v-model="state.file" label="File" show-size prepend-icon=""
                                    prepend-inner-icon="" clearable accept="image/*" />
                            </VCol>
                            <VCol cols="12" md="12">
                                <VTextField v-model="state.blog.title" label="Title" :rules="[requiredValidator]" />
                            </VCol>
                            <VCol cols="12" md="12">
                                <AppDateTimePicker v-model="state.blog.published_date" clearable clear-icon="tabler-x"
                                    :config="{ altInput: true, dateFormat: 'Y-m-d', i18n: 'Published Date' }"
                                    append-inner-icon="" />
                            </VCol>
                            <VCol cols="12" md="12">
                                <VTextarea v-model="state.blog.content" label="Content" :rules="[requiredValidator]" />
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>

            </VCol>
            <VCol cols="12" md="3">
                <VCard title="Actions" class="mb-5">
                    <VCardText>
                        <!-- 👉 Update -->
                        <VBtn :loading="state.loading" type="submit" block prepend-icon="tabler-refresh" class="mb-2">
                            UPDATE
                        </VBtn>
                    </VCardText>
                </VCard>
                <VCard title="Options" class="mb-5">
                    <VCardText>
                        <!-- 👉 Status -->
                        <VSelect v-model="state.blog.status" :items="getBlogStatusList()" :rules="[requiredValidator]"
                            label="Select Status" class="mb-4" />
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>
    </VForm>
</template>