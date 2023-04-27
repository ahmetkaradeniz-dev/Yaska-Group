<script setup lang="ts">
const authStore = useAuthStore()

</script>

<template>
    <header class="shadow-lg p-4">
        <div class="container">
            <div class="flex items-center justify-between">
                <nuxt-link to="/">
                    <h1 class="font-bold text-3xl text-[#17244c]">Blog App</h1>
                </nuxt-link>
                <div v-if="!authStore.$state.user" class="flex gap-x-4">
                    <nuxt-link to="/login" class="btn">Login</nuxt-link>
                    <nuxt-link to="/register" class="btn bg-gamboge">Register</nuxt-link>
                </div>
                <div v-else class="relative group">
                    <button class="btn">{{ authStore.$state.user.first_name }} {{ authStore.$state.user.last_name }}
                    </button>
                    <div class="group-hover:block hidden absolute bg-white shadow rounded w-full p-4">
                        <ul class="px-2 text-center">
                            <li class="mb-2"><nuxt-link to="/account">Account</nuxt-link></li>
                            <li><button @click="authStore.logout()">Logout</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>