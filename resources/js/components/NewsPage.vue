<template>
  <div>
    <!-- Hero Gambar Berita Utama -->
    <section v-if="hero" class="relative mb-8 rounded-xl overflow-hidden">
      <img :src="hero.image_url" alt="Berita Utama" class="w-full aspect-[16/9] object-cover" loading="lazy" />
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
        <h1 class="text-[32px] font-bold leading-tight">{{ hero.title }}</h1>
        <div class="mt-2 text-sm opacity-80">Sumber: {{ hero.user?.name }} • {{ formatDate(hero.created_at) }}</div>
      </div>
    </section>

    <!-- Konten Utama: Trending & Sponsor -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Trending Artikel (kiri) -->
      <div class="lg:col-span-2 space-y-4">
        <h2 class="text-2xl font-semibold">Trending</h2>
        <article v-for="t in trending" :key="t.id" class="bg-white dark:bg-gray-800 rounded shadow hover:shadow-lg transition-all overflow-hidden">
          <div class="grid grid-cols-1 sm:grid-cols-3">
            <img :src="t.image_url" alt="" class="w-full h-full object-cover sm:aspect-[4/3]" loading="lazy" />
            <div class="p-4 sm:col-span-2">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">{{ t.title }}</h3>
                <span :class="['text-xs px-2 py-1 rounded', labelClass(t.category)]">{{ t.category }}</span>
              </div>
              <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ formatDate(t.created_at) }}</div>
              <p class="text-sm text-gray-700 dark:text-gray-200 line-clamp-3">{{ t.content }}</p>
            </div>
          </div>
        </article>

        <!-- Rekomendasi Trending -->
        <div class="mt-6">
          <h2 class="text-2xl font-semibold mb-3">Rekomendasi untuk Anda</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <article v-for="r in recommendations" :key="r.id" class="bg-white dark:bg-gray-800 rounded shadow hover:shadow-lg transition-all overflow-hidden">
              <img :src="r.image_url" alt="" class="w-full aspect-[4/3] object-cover" loading="lazy" />
              <div class="p-3">
                <h4 class="font-semibold text-sm mb-1">{{ r.title }}</h4>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(r.created_at) }}</div>
              </div>
            </article>
          </div>
        </div>
      </div>

      <!-- Sponsor (kanan) -->
      <aside class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
          <h4 class="text-lg font-semibold mb-3">Iklan Sponsor</h4>
          <div class="h-40 rounded bg-neutral-100 dark:bg-gray-700 flex items-center justify-center text-gray-500">
            Sponsor Area
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
          <h4 class="text-lg font-semibold mb-2">Newsletter</h4>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Dapatkan berita terbaru langsung ke email Anda.</p>
          <form @submit.prevent="subscribe" class="space-y-2">
            <input v-model="email" type="email" placeholder="email@contoh.com" class="w-full border rounded px-3 py-2 dark:bg-gray-900 dark:border-gray-700" />
            <button class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded transition-colors">Langganan</button>
          </form>
        </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted, computed } from 'vue'

const articles = ref([])
const hero = computed(() => (articles.value.length ? articles.value[0] : null))
const trending = computed(() => articles.value.slice(0, 5))
const recommendations = computed(() => articles.value.slice(5, 11))
const email = ref('')

const fetch = async () => {
  const { data } = await axios.get('/articles')
  articles.value = data.data ?? data
}
onMounted(fetch)

const formatDate = (iso) => new Date(iso).toLocaleString()
const labelClass = (cat) => {
  switch (cat) {
    case 'Game': return 'bg-blue-100 text-blue-700'
    case 'Politik': return 'bg-red-100 text-red-700'
    case 'Game Politik': return 'bg-purple-100 text-purple-700'
    default: return 'bg-gray-100 text-gray-700'
  }
}

const subscribe = () => {
  email.value = ''
  alert('Terima kasih telah berlangganan!')
}
</script>

