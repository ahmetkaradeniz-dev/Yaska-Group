<script lang="ts" setup>
import { useLayouts } from '@layouts'
import { config } from '@layouts/config'
import { can } from '@layouts/plugins/casl'
import type { NavSectionTitle } from '@layouts/types'
import { useI18n } from 'vue-i18n';

const { t } = useI18n()

const props = defineProps<{
  item: NavSectionTitle
}>()

const { isVerticalNavMini, dynamicI18nProps } = useLayouts()
const { width: windowWidth } = useWindowSize()
const shallRenderIcon = isVerticalNavMini(windowWidth)

const name = ref(t(props.item.i18n))
</script>

<template>
  <li v-if="can(item.action, item.subject)" class="nav-section-title">
    <div class="title-wrapper">
      <Transition name="vertical-nav-section-title" mode="out-in">
        <!-- eslint-disable vue/no-v-text-v-html-on-component -->
        <Component :is="shallRenderIcon ? config.app.iconRenderer : config.app.enableI18n ? 'i18n-t' : 'span'"
          :key="shallRenderIcon" :class="shallRenderIcon ? 'placeholder-icon' : 'title-text'"
          v-bind="{ ...config.icons.sectionTitlePlaceholder, ...dynamicI18nProps(item.i18n || '', 'span') }"
          v-text="!shallRenderIcon ? name : null" />
        <!-- eslint-enable vue/no-v-text-v-html-on-component -->
      </Transition>
    </div>
  </li>
</template>
