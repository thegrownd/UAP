<template>
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Daftar</h1>
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="block mb-1">Nama</label>
        <input v-model="name" type="text" class="w-full border rounded px-3 py-2" required />
      </div>
      <div class="mb-3">
        <label class="block mb-1">Email</label>
        <input v-model="email" type="email" class="w-full border rounded px-3 py-2" required />
      </div>
      <div class="mb-3">
        <label class="block mb-1">Password</label>
        <input v-model="password" type="password" class="w-full border rounded px-3 py-2" required />
      </div>
      <div class="mb-3">
        <label class="block mb-1">Konfirmasi Password</label>
        <input v-model="password_confirmation" type="password" class="w-full border rounded px-3 py-2" required />
      </div>
      <div v-if="error" class="text-red-600 mb-2">{{ error }}</div>
      <button class="bg-green-600 text-white px-4 py-2 rounded">Daftar</button>
      <router-link to="/login" class="ml-4 text-gray-700">Login</router-link>
    </form>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref('')

const submit = async () => {
  error.value = ''
  try {
    await axios.post('/auth/register', { name: name.value, email: email.value, password: password.value, password_confirmation: password_confirmation.value })
    window.location.href = '/login'
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Gagal daftar'
  }
}
</script>

