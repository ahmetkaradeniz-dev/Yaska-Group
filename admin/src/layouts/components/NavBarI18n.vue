<script lang="ts" setup>
import NavBarI18n from '@core/components/I18n.vue'
import { useThemeConfig } from '@core/composable/useThemeConfig'
import type { I18nLanguage } from '@layouts/types'

const { isAppRtl } = useThemeConfig()

const i18nCompLanguages: I18nLanguage[] = [
  {
    label: 'reusable.english',
    i18nLang: 'en',
  },
  {
    label: 'reusable.turkish',
    i18nLang: 'tr',
  }
]

const handleLangChange = (lang: string) => {
  isAppRtl.value = lang === 'ar'
}
</script>

<template>
  <NavBarI18n :languages="i18nCompLanguages" @change="handleLangChange" />
</template>
