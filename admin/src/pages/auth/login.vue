<script setup lang="ts">
import { useAppAbility } from '@/plugins/casl/useAppAbility';
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant';
import { requiredValidator } from '@validators';
import { VForm } from 'vuetify/components';

import authV2LoginIllustrationBorderedDark from '@/assets/images/pages/auth-v2-login-illustration-bordered-dark.png';
import authV2LoginIllustrationBorderedLight from '@/assets/images/pages/auth-v2-login-illustration-bordered-light.png';
import authV2LoginIllustrationDark from '@/assets/images/pages/auth-v2-login-illustration-dark.png';
import authV2LoginIllustrationLight from '@/assets/images/pages/auth-v2-login-illustration-light.png';
import authV2MaskDark from '@/assets/images/pages/misc-mask-dark.png';
import authV2MaskLight from '@/assets/images/pages/misc-mask-light.png';
import type { UserAbility } from '@/plugins/casl/AppAbility';
import { useAuthStore } from "@/store/auth"
import { AuthLoginData } from '@/types/auth'
import { UserRole } from '@/types/user';
import { toastMessage } from '@/utils';

const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

const isPasswordVisible = ref(false)

const route = useRoute()
const router = useRouter()

const ability = useAppAbility()

const refVForm = ref<VForm>()

const state = reactive({
  loader: false,
  loginData: {
    user_name: null,
    password: null
  } as AuthLoginData,
})

const authStore = useAuthStore()

const login = () => {
  state.loader = true
  authStore.login(state.loginData).then((response) => {
    const { success, data, message, errors } = response.data

    if (!success) {
      toastMessage(message, 'warning')
      return
    }

    const { user, token } = data

    if (user?.role == UserRole.USER) {
      toastMessage('Unauthorized', 'warning')
      return
    }

    const abilities: UserAbility[] = [{
      action: 'manage',
      subject: 'all',
    }]

    localStorage.setItem('userAbilities', JSON.stringify(abilities))
    ability.update(abilities)

    console.log(token)

    localStorage.setItem('userData', JSON.stringify(user))
    localStorage.setItem('accessToken', token.access)


    if (route.query.to)
      router.replace(String(route.query.to))
    else router.replace('/')
  }).finally(() => {
    state.loader = false
  })
}

const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid)
        login()
    })
}
</script>
  
<template>
  <VRow no-gutters class="auth-wrapper">
    <VCol lg="8" class="d-none d-lg-flex">
      <div class="position-relative auth-bg rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg max-width="505" :src="authThemeImg" class="auth-illustration mt-16 mb-2" />
        </div>
        <VImg :src="authThemeMask" class="auth-footer-mask" />
      </div>
    </VCol>

    <VCol cols="12" lg="4" class="d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
        <VCardText>
          <h5 class="text-h5 font-weight-semibold mb-1">
            Welcome to Blog App 👋🏻
          </h5>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onSubmit">
            <VRow>
              <!-- Username -->
              <VCol cols="12">
                <VTextField v-model="state.loginData.user_name" label="Username" prepend-inner-icon="tabler-user"
                  :rules="[requiredValidator]" />
              </VCol>

              <!-- Password -->
              <VCol cols="12">
                <VTextField v-model="state.loginData.password" label="Password" prepend-inner-icon="tabler-lock"
                  :rules="[requiredValidator]" :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible" />

                <VBtn :loading="state.loader" block type="submit" class="mt-5">
                  Login
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
