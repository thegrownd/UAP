<template>
  <div class="max-w-4xl mx-auto animate-fade-in">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-purple-100 dark:border-purple-900">
      <!-- Header -->
      <div class="bg-gradient-to-r from-purple-500 to-blue-500 p-8 text-white">
        <div class="flex items-center gap-3 mb-2">
          <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-bold">{{ isEdit ? 'Edit Artikel' : 'Buat Artikel Baru' }}</h1>
            <p class="text-white/80 text-sm">{{ isEdit ? 'Perbarui konten artikel Anda' : 'Tulis dan publikasikan artikel Anda' }}</p>
          </div>
        </div>
      </div>

      <!-- Form Content -->
      <form @submit.prevent="submit" class="p-8 space-y-6">
        <!-- Title Input -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 000-1.42l-2.34-2.34a1.003 1.003 0 00-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>
            </svg>
            Judul Artikel
          </label>
          <input 
            v-model="title" 
            type="text" 
            placeholder="Masukkan judul artikel yang menarik..." 
            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900 outline-none transition-all text-lg font-medium"
            required 
          />
        </div>

        <!-- Image URL Input -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
            </svg>
            URL Gambar
          </label>
          <input 
            v-model="image_url" 
            type="url" 
            placeholder="https://example.com/image.jpg" 
            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900 outline-none transition-all"
            required 
          />
          <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            Gunakan URL gambar dari Unsplash, Pexels, atau sumber lainnya
          </p>
          
          <!-- Image Preview -->
          <div v-if="image_url" class="mt-4 relative rounded-2xl overflow-hidden shadow-xl group">
            <div v-if="!imageLoaded" class="absolute inset-0 skeleton"></div>
            <img 
              :src="image_url" 
              alt="Preview" 
              @load="imageLoaded = true"
              @error="handleImageError"
              class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" 
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
              <p class="text-white font-semibold">Preview Gambar</p>
            </div>
          </div>
        </div>

        <!-- Category Select -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
            </svg>
            Kategori
          </label>
          <select 
            v-model="category" 
            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900 outline-none transition-all font-medium"
            required
          >
            <option value="Game">🎮 Game</option>
            <option value="Politik">🏛️ Politik</option>
            <option value="Game Politik">🎯 Game Politik</option>
          </select>
        </div>

        <!-- Content Textarea -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
            </svg>
            Konten Artikel
          </label>
          <textarea 
            v-model="content" 
            placeholder="Tulis konten artikel Anda di sini..." 
            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900 outline-none transition-all min-h-[300px] resize-y"
            required 
          />
          <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>{{ content.length }} karakter</span>
            <span>Minimal 50 karakter</span>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 flex items-start gap-3 animate-fade-in">
          <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <div>
            <p class="font-semibold text-red-800 dark:text-red-200">Terjadi Kesalahan</p>
            <p class="text-red-600 dark:text-red-400">{{ error }}</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4 pt-4">
          <button 
            type="submit"
            :disabled="loading"
            class="flex-1 btn-gradient text-white px-6 py-4 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
          >
            <svg v-if="!loading" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            <svg v-else class="w-6 h-6 animate-spin" viewBox="0 0 24 24" fill="none">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Menyimpan...' : (isEdit ? 'Perbarui Artikel' : 'Publikasikan Artikel') }}
          </button>
          <router-link 
            to="/" 
            class="px-6 py-4 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 font-semibold transition-all flex items-center gap-2"
          >
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
            Batal
          </router-link>
        </div>
      </form>
    </div>

    <!-- Tips Section -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-purple-100 dark:border-purple-900">
        <div class="w-12 h-12 rounded-full bg-gradient-primary flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <h3 class="font-bold text-lg mb-2">Judul Menarik</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Buat judul yang singkat, jelas, dan menarik perhatian pembaca.</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-purple-100 dark:border-purple-900">
        <div class="w-12 h-12 rounded-full bg-gradient-secondary flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
          </svg>
        </div>
        <h3 class="font-bold text-lg mb-2">Gambar Berkualitas</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Gunakan gambar beresolusi tinggi yang relevan dengan konten artikel.</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-purple-100 dark:border-purple-900">
        <div class="w-12 h-12 rounded-full bg-gradient-success flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
          </svg>
        </div>
        <h3 class="font-bold text-lg mb-2">Konten Berkualitas</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Tulis konten yang informatif, mudah dipahami, dan memberikan nilai tambah.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const id = route.params.id
const isEdit = computed(() => !!id)

const title = ref('')
const content = ref('')
const category = ref('Game')
const image_url = ref('')
const error = ref('')
const loading = ref(false)
const imageLoaded = ref(false)

onMounted(async () => {
  if (isEdit.value) {
    try {
      const { data } = await axios.get(`/articles/${id}`)
      title.value = data.title
      content.value = data.content
      category.value = data.category
      image_url.value = data.image_url
    } catch (e) {
      error.value = 'Gagal memuat artikel'
    }
  }
})

const handleImageError = (e) => {
  e.target.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="600" height="400"%3E%3Cdefs%3E%3ClinearGradient id="grad" x1="0%25" y1="0%25" x2="100%25" y2="100%25"%3E%3Cstop offset="0%25" style="stop-color:%23667eea;stop-opacity:1" /%3E%3Cstop offset="100%25" style="stop-color:%23764ba2;stop-opacity:1" /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width="600" height="400" fill="url(%23grad)" /%3E%3Ctext x="50%25" y="50%25" font-family="Arial" font-size="20" fill="white" text-anchor="middle" dominant-baseline="middle"%3EGambar tidak dapat dimuat%3C/text%3E%3C/svg%3E'
}

const submit = async () => {
  error.value = ''
  
  // Validation
  if (content.value.length < 50) {
    error.value = 'Konten artikel minimal 50 karakter'
    return
  }
  
  loading.value = true
  
  try {
    const payload = { 
      title: title.value, 
      content: content.value, 
      category: category.value, 
      image_url: image_url.value 
    }
    
    if (isEdit.value) {
      await axios.put(`/articles/${id}`, payload)
    } else {
      await axios.post(`/articles`, payload)
    }
    
    router.push('/')
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Gagal menyimpan artikel. Silakan coba lagi.'
  } finally {
    loading.value = false
  }
}
</script>
