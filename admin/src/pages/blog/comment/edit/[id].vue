<script lang="ts" setup>
import { VForm } from 'vuetify/components';
import { useBlogStore } from '@/store/blog';
import { BlogComment } from '@/types/blog';
import { requiredValidator } from '@/@core/utils/validators';

const route = useRoute()
const store = useBlogStore()

const id = Number(route.params.id)

store.getComment(id).then((response) => {
    state.isLoad = true
    state.comment = response.data.data.comment
})

const refVForm = ref<VForm>()

const state = reactive({
    isLoad: false,
    loading: false,
    comment: {} as BlogComment
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
        await store.updateComment(id, { comment: state.comment.comment })
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
                <VCard title="Blog Comment" class="mb-5">
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="12">
                                <VTextarea v-model="state.comment.comment" label="Comment" :rules="[requiredValidator]" />
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
            </VCol>
        </VRow>
    </VForm>
</template>