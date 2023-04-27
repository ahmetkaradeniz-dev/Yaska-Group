<script setup lang="ts">
import { useThemeConfig } from '@core/composable/useThemeConfig'

const { syncInitialLoaderTheme, syncVuetifyThemeWithTheme: syncConfigThemeWithVuetifyTheme, isAppRtl } = useThemeConfig()

// ℹ️ Sync current theme with initial loader theme
syncInitialLoaderTheme()
syncConfigThemeWithVuetifyTheme()
</script>

<template>
  <VLocaleProvider :rtl="isAppRtl">
    <VApp>
      <VMain>
        <RouterView />
      </VMain>
    </VApp>
  </VLocaleProvider>
</template>
<style lang="scss">
.v-theme--light {

  input:-webkit-autofill,
  input:-webkit-autofill:hover,
  input:-webkit-autofill:focus,
  input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #ffffff inset !important;
  }
}

.v-theme--dark {

  input:-webkit-autofill,
  input:-webkit-autofill:hover,
  input:-webkit-autofill:focus,
  input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #2f3349 inset !important;
  }
}

.el-notification {
  width: auto !important;
}

.el-notification.right {
  right: 25px !important;
}

.v-input--error.position-relative {
  .v-input__details {
    padding-inline-start: 16px;
    padding-inline-end: 16px
  }
}

.v-field--variant-outlined .v-field__outline__notch {
  border-width: 0 !important;
}

.search-filter {
  inline-size: 20rem;
}

.text-capitalize {
  text-transform: capitalize;
}

.list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
}

.v-theme--dark .v-expansion-panel-title {
  background: #2f3349;
}

.v-theme--light .v-expansion-panel-title {
  background: #ffffff;
}

.v-expansion-panel-title.v-expansion-panel-title--active .d-flex button svg {
  transform: rotateZ(0deg) !important;
}

.v-expansion-panel-title.v-expansion-panel-title--active .d-flex svg {
  transform: rotateZ(-180deg);
}
</style>