<script lang="ts" setup>
import { useI18n } from 'vue-i18n';


const { locale } = useI18n()

</script>
<template>
  <div class="h-100 d-flex align-center justify-space-between">
    <!-- 👉 Footer: left content -->
    <span class="d-flex align-center" v-if="locale == 'en'">
      &copy;
      {{ new Date().getFullYear() }}
      Made With
      <VIcon icon="tabler-heart" color="error" size="1.25rem" class="mx-1" /> By <a href="https://6harf.com"
        target="_blank" rel="noopener noreferrer" class="text-primary ms-1">6harf</a>
    </span>
    <span class="d-flex align-center" v-if="locale == 'tr'">
      &copy;
      {{ new Date().getFullYear() }}
      <a href="https://6harf.com" target="_blank" rel="noopener noreferrer" class="text-primary ms-1 me-1">6harf</a>
      tarafından
      <VIcon icon="tabler-heart" color="error" size="1.25rem" class="mx-1" />
      ile
      yapılmıştır
    </span>
  </div>
</template>
