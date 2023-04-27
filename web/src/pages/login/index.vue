<script setup lang="ts">
import { LoginData } from "~/types/auth"

definePageMeta({
    middleware: ['auth']
})

const authStore = useAuthStore()
const router = useRouter()

const state = reactive({
    loading: false,
    form: {
        user_name: '',
        password: ''
    } as LoginData,
    type: 'password'
})

const showPassword = () => {
    if (state.type === 'password') {
        state.type = 'text'
    } else {
        state.type = 'password'
    }
}

const login = async () => {
    try {
        state.loading = true
        const response = await authStore.login(state.form)

        swal(response.data.message, { icon: response.data.success ? 'success' : 'warning' })
        if (response.data.success) {
            authStore.user = response.data.data.user
            authStore.token = response.data.data.token
            router.push('/')
        }
    } catch (error: any) {
        swal(error.message, { icon: 'error' })
    } finally {
        state.loading = false
    }
}
</script>

<template>
    <main class="section-padding">
        <div class="container">
            <div class="mx-auto w-7/12 shadow-xl p-4">
                <h1 class="text-center text-2xl text-darkBlue">Login</h1>
                <form @submit.prevent="login">
                    <label class="block mb-4">
                        <span class="block text-sm font-medium text-slate-700">Username</span>
                        <input v-model="state.form.user_name" type="text"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400"
                            required />
                    </label>
                    <label class="block">
                        <span class="block text-sm font-medium text-slate-700">Password</span>
                        <div class="relative">
                            <input v-model="state.form.password" :type="state.type"
                                class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400"
                                required />
                            <svg @click="showPassword()"
                                class="cursor-pointer absolute right-[18px] top-1/2 transform -translate-y-2/4"
                                xmlns="http://www.w3.org/2000/svg" width="11.162" height="15.194"
                                viewBox="0 0 11.162 15.194">
                                <g id="_7ecc0b200030ea793fb7056de43585c3" data-name="7ecc0b200030ea793fb7056de43585c3"
                                    transform="translate(870.5 -985.111)" opacity="0.447">
                                    <path id="Path_2545" data-name="Path 2545"
                                        d="M-865.279,985.111A3.645,3.645,0,0,0-869,988.8v2.84a.62.62,0,0,0,.62.62.62.62,0,0,0,.62-.62V988.8a2.3,2.3,0,0,1,2.48-2.447,2.283,2.283,0,0,1,2.48,2.447v2.836a.62.62,0,0,0,.62.62.62.62,0,0,0,.62-.62V988.8A3.635,3.635,0,0,0-865.279,985.111Z"
                                        transform="translate(0.36 0)" fill="#848895" />
                                    <circle id="Ellipse_211" data-name="Ellipse 211" cx="5.581" cy="5.581" r="5.581"
                                        transform="translate(-870.5 989.143)" fill="#848895" />
                                    <path id="Path_2546" data-name="Path 2546"
                                        d="M-682.6,54.346a1.238,1.238,0,0,0-1.24,1.24,1.238,1.238,0,0,0,.361.879,1.206,1.206,0,0,0,.26.174v2.046a.62.62,0,0,0,.62.62.62.62,0,0,0,.62-.62V56.637a1.279,1.279,0,0,0,.255-.171,1.24,1.24,0,0,0,.273-1.354,1.24,1.24,0,0,0-1.15-.766Z"
                                        transform="translate(-182.319 938.517)" fill="#848895" />
                                </g>
                            </svg>
                        </div>
                    </label>
                    <div class="text-center py-4">
                        <button :disabled="state.loading" type="submit"
                            class="btn hover:bg-white border border-darkBlue duration-200 hover:text-darkBlue">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>