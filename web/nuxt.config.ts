export default defineNuxtConfig({
    srcDir: 'src/',
    app: {
        pageTransition: { name: 'page', mode: 'out-in' },
        head: {
            htmlAttrs: {
                lang: 'en'
            },
            title: 'Blog App',
            meta: [
                { charset: 'utf-8' },
                { name: 'viewport', content: 'width=device-width, initial-scale=1' },
                {
                    hid: 'description',
                    name: 'description',
                    content: 'Blog App | Interview Task',
                },
            ],
            link: [
                { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
            ],
        },
    },
    modules: [
        '@nuxtjs/tailwindcss',
        [
            '@pinia/nuxt',
            {
                autoImports: ['defineStore', 'acceptHMRUpdate'],
            }
        ],
        '@pinia-plugin-persistedstate/nuxt',
    ],
    pages: true,
    postcss: {
        plugins: {
            tailwindcss: {},
            autoprefixer: {},
        },
    },
    tailwindcss: {
        cssPath: '~/assets/css/tailwind.css',
    },
    imports: {
        dirs: ['./stores'],
    },
    webpack: {
        optimization: {
            splitChunks: {
                maxSize: 300000
            }
        }
    },
    nitro: {
        compressPublicAssets: true,
    },
    runtimeConfig: {
        public: {
            API_URL: process.env.API_URL
        }
    }
})
