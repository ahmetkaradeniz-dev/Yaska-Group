import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import ability from '@/plugins/casl/ability'
import i18n from '@/plugins/i18n'
import layoutsPlugin from '@/plugins/layouts'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import '@/styles/styles.scss'
import { abilitiesPlugin } from '@casl/vue'
import '@core/scss/template/index.scss'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createApp } from 'vue'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import ElementPlus from "element-plus";
import "element-plus/theme-chalk/index.css";

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

loadFonts()

const app = createApp(App)
app.use(vuetify)
app.use(pinia)
app.use(router)
app.use(layoutsPlugin)
app.use(i18n)
app.use(abilitiesPlugin, ability, {
  useGlobalProperties: true,
})
app.use(VueSweetalert2)
app.use(ElementPlus)
app.mount('#app')