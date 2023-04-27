<script setup lang="ts">
import misc404 from '@/assets/images/pages/404.png'
import miscMaskDark from '@/assets/images/pages/misc-mask-dark.png'
import miscMaskLight from '@/assets/images/pages/misc-mask-light.png'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import { useI18n } from 'vue-i18n';

const authThemeMask = useGenerateImageVariant(miscMaskLight, miscMaskDark)
</script>

<template>
  <div class="misc-wrapper">
    <ErrorHeader :error-title="$t('content.not-found.title')" :error-description="$t('content.not-found.description')" />
    <VBtn to="/" class="mb-12">
      {{ $t('reusable.backToHome') }}
    </VBtn>
    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg :src="misc404" alt="Coming Soon" :max-width="200" class="mx-auto" />
    </div>
    <VImg :src="authThemeMask" class="misc-footer-img d-none d-md-block" />
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/misc.scss";
</style>

<route lang="yaml">
  meta:
    layout: blank
    action: read
    subject: Auth
</route>
  
