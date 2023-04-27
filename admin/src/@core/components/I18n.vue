<script setup lang="ts">
import type { Anchor } from 'vuetify/lib/components'
import type { I18nLanguage } from '@layouts/types'
import { useI18n } from 'vue-i18n';

const props = withDefaults(defineProps<Props>(), {
  location: 'bottom end',
})

defineEmits<{
  (e: 'change', id: string): void
}>()

interface Props {
  languages: I18nLanguage[]
  location?: Anchor
}

const { locale } = useI18n({ useScope: 'global' })


const updateLanguage = (lang: string) => {
  localStorage.setItem('app-language', lang)
  locale.value = lang
  const html = document.documentElement
  html.setAttribute('lang', lang)
}

onMounted(() => {
  updateLanguage(localStorage.getItem('app-language') ?? 'en')
})

</script>

<template>
  <VBtn icon variant="text" color="default" size="small">
    <VIcon icon="tabler-language" size="24" />
    <!-- Menu -->
    <VMenu activator="parent" :location="props.location" offset="14px">
      <!-- List -->
      <VList min-width="175px">
        <!-- List item -->
        <VListItem v-for="lang in props.languages" :key="lang.i18nLang" :value="lang.i18nLang"
          @click="updateLanguage(lang.i18nLang); $emit('change', lang.i18nLang)">
          <!-- Language label -->
          <VListItemTitle>{{ $t(lang.label) }}</VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
  </VBtn>
</template>
