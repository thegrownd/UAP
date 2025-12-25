<template>
  <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
    <h1 class="text-2xl font-bold mb-2">Admin Login</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Masuk sebagai admin untuk manajemen penuh.</p>
    <form @submit.prevent="submit" class="space-y-3">
      <div>
        <label class="block mb-1 text-sm">Email</label>
        <input v-model="email" type="email" class="w-full border rounded px-3 py-2 dark:bg-gray-900 dark:border-gray-700" required />
      </div>
      <div>
        <label class="block mb-1 text-sm">Password</label>
        <input v-model="password" type="password" class="w-full border rounded px-3 py-2 dark:bg-gray-900 dark:border-gray-700" required />
      </div>
      <div class="text-xs text-gray-500 dark:text-gray-400">Default: admin@gmail.com / Nagasaya1</div>
      <div v-if="error" class="text-red-600 dark:text-red-400 mb-2">{{ error }}</div>
      <button :disabled="loading" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-4 py-2 rounded shadow transition-colors">
        <span v-if="!loading">Masuk sebagai Admin</span>
        <span v-else>Mengautentikasi...</span>
      </button>
    </form>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'

const email = ref('admin@gmail.com')
const password = ref('Nagasaya1')
const error = ref('')
const loading = ref(false)

onMounted(() => {
})

const submit = async () => {
  error.value = ''
  loading.value = true
  try {
    const { data } = await axios.post('/auth/login', { email: email.value, password: password.value })
    localStorage.setItem('token', data.access_token)
    localStorage.setItem('user_id', data.user.id)
    localStorage.setItem('is_admin', String(!!data.user?.is_admin))
    const isSuper = !!data.user?.is_admin && data.user?.email === 'admin@gmail.com'
    localStorage.setItem('is_super_admin', String(isSuper))
    window.location.href = '/'
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Gagal login'
  } finally {
    loading.value = false
  }
}
</script>
