<template>
  <div class="animate-fade-in">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div class="animate-slide-in-left">
        <h1 class="text-4xl font-bold tracking-tight gradient-text mb-2">Artikel Terkini</h1>
        <p class="text-gray-600 dark:text-gray-400">Kumpulan tulisan bertema game, politik, dan persilangan keduanya.</p>
      </div>
      <div class="flex items-center gap-3 animate-slide-in-right">
        <select v-model="filter" class="px-4 py-2.5 rounded-xl border-2 border-purple-200 dark:border-purple-800 bg-white dark:bg-gray-800 text-sm font-medium focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all">
          <option value="">🎯 Semua Kategori</option>
          <option>Game</option>
          <option>Politik</option>
          <option>Game Politik</option>
        </select>
        <router-link v-if="isAuth" to="/articles/new" class="inline-flex items-center gap-2 btn-gradient text-white px-4 py-2.5 rounded-xl shadow-lg font-medium">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13H13v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
          Tambah Artikel
        </router-link>
      </div>
    </div>

    <!-- Hero Section with Modern Design -->
    <section v-if="hero" class="relative mb-10 rounded-3xl overflow-hidden shadow-2xl group animate-fade-in">
      <div class="relative min-h-[450px]">
        <!-- Image with Loading State -->
        <div v-if="!imageLoaded[hero.id]" class="absolute inset-0 skeleton"></div>
        <img 
          :src="hero.image_url || 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=1200'" 
          :alt="hero.title"
          @load="imageLoaded[hero.id] = true"
          @error="handleImageError"
          class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
        />
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
        
        <!-- Content -->
        <div class="relative min-h-[450px] flex items-end">
          <div class="p-8 sm:p-12 w-full">
            <div class="flex items-center gap-3 mb-4">
              <span :class="['px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider', labelClass(hero.category)]">
                {{ hero.category }}
              </span>
              <span class="text-white/80 text-sm">{{ formatDate(hero.created_at) }}</span>
            </div>
            <h2 class="font-bold text-4xl sm:text-5xl leading-tight mb-4 text-white drop-shadow-lg">{{ hero.title }}</h2>
            <p class="text-white/90 text-lg mb-6 line-clamp-2 max-w-3xl">{{ hero.content }}</p>
            <div class="flex items-center gap-4">
              <button @click="openDetail(hero)" class="inline-flex items-center gap-2 btn-gradient px-6 py-3 rounded-xl font-semibold shadow-xl">
                Baca Selengkapnya
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
              </button>
              <div class="flex items-center gap-2 text-white/80">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span class="text-sm font-medium">{{ hero.user?.name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-6">
        <!-- Articles Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <article
            v-for="(a, index) in filteredArticles"
            :key="a.id"
            :style="{ animationDelay: `${index * 0.1}s` }"
            class="card-hover bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden group animate-fade-in"
          >
            <!-- Image Container -->
            <div class="relative overflow-hidden aspect-[16/10]">
              <div v-if="!imageLoaded[a.id]" class="absolute inset-0 skeleton"></div>
              <img 
                :src="a.image_url || `https://images.unsplash.com/photo-${1550745165 + index}?w=600`" 
                :alt="a.title"
                @load="imageLoaded[a.id] = true"
                @error="handleImageError"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                loading="lazy" 
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <span :class="['absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg', labelClass(a.category)]">
                {{ a.category }}
              </span>
            </div>

            <!-- Content -->
            <div class="p-5">
              <h2 class="text-xl font-bold mb-2 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                <span :class="isSearching ? 'text-[#000000]' : ''">{{ a.title }}</span>
              </h2>
              
              <div class="flex items-center gap-2 mb-3 text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>{{ a.user?.name }}</span>
                <span class="text-gray-400">•</span>
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                </svg>
                <span>{{ formatDate(a.created_at) }}</span>
              </div>

              <p :class="['text-sm line-clamp-3 mb-4', isSearching ? 'text-[#000000]' : 'text-gray-700 dark:text-gray-300']">{{ a.content }}</p>

              <!-- Action Buttons -->
              <div class="flex items-center gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <button @click="openDetail(a)" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white rounded-lg font-medium transition-all">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                  </svg>
                  Baca
                </button>
                <router-link v-if="isAuth && (currentUserId === a.user_id || isAdmin)" :to="`/articles/${a.id}/edit`" class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 000-1.42l-2.34-2.34a1.003 1.003 0 00-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>
                  </svg>
                </router-link>
                <button v-if="isSuperAdmin" @click="remove(a.id)" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 19c0 1.1.9 2 2 2h8a2 2 0 002-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>
        </div>

        <!-- Video/Media Section with Modern Design -->
        <section class="mt-10">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-2xl font-bold gradient-text">Video & Media</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Konten multimedia pilihan</p>
            </div>
            <div class="flex gap-2">
              <button @click="scrollLeft" class="p-3 rounded-xl bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-800 hover:border-purple-500 transition-all shadow-md hover:shadow-lg" aria-label="Prev">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
              </button>
              <button @click="scrollRight" class="p-3 rounded-xl bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-800 hover:border-purple-500 transition-all shadow-md hover:shadow-lg" aria-label="Next">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
              </button>
            </div>
          </div>
          <div ref="carousel" class="flex overflow-x-auto gap-4 scroll-smooth snap-x snap-mandatory pb-4 -mx-2 px-2">
            <div v-for="v in videos" :key="v.id" class="min-w-[280px] snap-start bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all overflow-hidden group">
              <div class="relative overflow-hidden">
                <img :src="v.thumbnail" :alt="v.title" class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                  <div class="w-14 h-14 rounded-full bg-white/90 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 ml-1" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                </div>
                <div class="absolute bottom-2 right-2 px-2 py-1 bg-black/80 rounded text-white text-xs font-semibold">
                  {{ v.duration }}
                </div>
              </div>
              <div class="p-4">
                <div class="text-sm font-bold mb-2 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ v.title }}</div>
                <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                  </svg>
                  <span>{{ v.views }} views</span>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Modern Sidebar -->
      <aside class="space-y-6">
        <!-- Popular Articles -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-purple-100 dark:border-purple-900">
          <div class="flex items-center gap-2 mb-4">
            <svg class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
            </svg>
            <h4 class="text-xl font-bold gradient-text">Trending</h4>
          </div>
          <ul class="space-y-4">
            <li v-for="(p, index) in popular" :key="p.id" class="group">
              <a @click.prevent="openDetail(p)" href="#" class="block">
                <div class="flex gap-3">
                  <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-primary flex items-center justify-center text-white font-bold text-sm">
                    {{ index + 1 }}
                  </div>
                  <div class="flex-1">
                    <div class="text-sm font-semibold group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-2 mb-1">
                      {{ p.title }}
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                      </svg>
                      {{ formatDate(p.created_at) }}
                    </div>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </div>

        <!-- Sponsor Ad -->
        <div class="bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl shadow-lg p-6 text-white overflow-hidden relative">
          <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
          <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
          <div class="relative">
            <h4 class="text-lg font-bold mb-2">Iklan Sponsor</h4>
            <p class="text-sm text-white/90 mb-4">Ruang untuk konten sponsor Anda</p>
            <div class="h-32 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30 border-dashed">
              <span class="text-sm font-semibold">300 x 250</span>
            </div>
          </div>
        </div>

        <!-- Newsletter -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-purple-100 dark:border-purple-900">
          <div class="flex items-center gap-2 mb-3">
            <svg class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            <h4 class="text-lg font-bold gradient-text">Newsletter</h4>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Dapatkan update terbaru langsung ke email Anda.</p>
          <form @submit.prevent="subscribe" class="space-y-3">
            <input 
              v-model="email" 
              type="email" 
              placeholder="email@contoh.com" 
              class="w-full px-4 py-2.5 rounded-xl border-2 border-purple-200 dark:border-purple-800 bg-white dark:bg-gray-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all" 
              required
            />
            <button class="w-full btn-gradient text-white px-4 py-2.5 rounded-xl font-semibold shadow-lg">
              Langganan Sekarang
            </button>
          </form>
        </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const articles = ref([])
const imageLoaded = reactive({})
const hero = computed(() => (filteredArticles.value.length ? filteredArticles.value[0] : null))
const popular = computed(() => [...articles.value].slice(0, 5))
const email = ref('')
const videos = ref([
  { id: 1, title: 'Highlights Turnamen E-Sports', duration: '8:12', views: '12k', thumbnail: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400' },
  { id: 2, title: 'Analisis Kebijakan Publik', duration: '12:30', views: '4.1k', thumbnail: 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=400' },
  { id: 3, title: 'Review Game Politik', duration: '5:44', views: '8.5k', thumbnail: 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400' },
  { id: 4, title: 'Debat Ekonomi Digital', duration: '9:03', views: '3.8k', thumbnail: 'https://images.unsplash.com/photo-1591115765373-5207764f72e7?w=400' },
  { id: 5, title: 'Kesehatan Mental Gamer', duration: '7:20', views: '2.3k', thumbnail: 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=400' },
])
const filter = ref('')
const isAuth = computed(() => !!localStorage.getItem('token'))
const isSuperAdmin = computed(() => localStorage.getItem('is_super_admin') === 'true')
const isAdmin = computed(() => localStorage.getItem('is_admin') === 'true' || isSuperAdmin.value)
const currentUserId = computed(() => {
  const val = localStorage.getItem('user_id')
  return val ? Number(val) : null
})

const fetch = async () => {
  const { data } = await axios.get('/articles')
  articles.value = data.data ?? data
}
onMounted(fetch)

const searchTerm = computed(() => {
  try {
    return String(route.query.q ?? '').trim()
  } catch (e) {
    console.error('Search term parse error', e)
    return ''
  }
})

const filteredArticles = computed(() => {
  try {
    let base = Array.isArray(articles.value) ? articles.value : []
    const term = searchTerm.value.toLowerCase()
    if (term) {
      base = base.filter(a =>
        String(a.title ?? '').toLowerCase().includes(term) ||
        String(a.content ?? '').toLowerCase().includes(term)
      )
    }
    if (filter.value) {
      base = base.filter(a => a.category === filter.value)
    }
    return base
  } catch (err) {
    console.error('Search filtering error', err)
    return Array.isArray(articles.value) ? articles.value : []
  }
})

const route = useRoute()
const router = useRouter()
const isSearching = computed(() => !!route.query.q)

watch(() => route.query.q, (q, prev) => {
  try {
    const term = String(q ?? '').toLowerCase()
    const matches = (Array.isArray(articles.value) ? articles.value : []).filter(a =>
      String(a.title ?? '').toLowerCase().includes(term) ||
      String(a.content ?? '').toLowerCase().includes(term)
    ).length
    console.debug('Search term changed', { previous: prev ?? '', current: q ?? '', matches })
  } catch (e) {
    console.error('Search watch error', e)
  }
}, { flush: 'post' })

const labelClass = (cat) => {
  switch (cat) {
    case 'Game': return 'bg-blue-500 text-white'
    case 'Politik': return 'bg-red-500 text-white'
    case 'Game Politik': return 'bg-purple-500 text-white'
    default: return 'bg-gray-500 text-white'
  }
}

const formatDate = (iso) => {
  const date = new Date(iso)
  const now = new Date()
  const diff = Math.floor((now - date) / 1000)
  
  if (diff < 60) return 'Baru saja'
  if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`
  if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`
  if (diff < 604800) return `${Math.floor(diff / 86400)} hari lalu`
  
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

const handleImageError = (e) => {
  // Fallback to a gradient placeholder if image fails to load
  e.target.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="600" height="400"%3E%3Cdefs%3E%3ClinearGradient id="grad" x1="0%25" y1="0%25" x2="100%25" y2="100%25"%3E%3Cstop offset="0%25" style="stop-color:%23667eea;stop-opacity:1" /%3E%3Cstop offset="100%25" style="stop-color:%23764ba2;stop-opacity:1" /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width="600" height="400" fill="url(%23grad)" /%3E%3Ctext x="50%25" y="50%25" font-family="Arial" font-size="24" fill="white" text-anchor="middle" dominant-baseline="middle"%3EGame Politik%3C/text%3E%3C/svg%3E'
}

const carousel = ref(null)
const scrollLeft = () => carousel.value?.scrollBy({ left: -300, behavior: 'smooth' })
const scrollRight = () => carousel.value?.scrollBy({ left: 300, behavior: 'smooth' })

const openDetail = (a) => {
  // Navigate to article detail page
  router.push(`/articles/${a.id}`)
}

const subscribe = () => {
  if (email.value) {
    alert(`Terima kasih telah berlangganan! Konfirmasi telah dikirim ke ${email.value}`)
    email.value = ''
  }
}

const remove = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus artikel ini?')) return
  try {
    await axios.delete(`/articles/${id}`)
    articles.value = articles.value.filter(x => x.id !== id)
    alert('Artikel berhasil dihapus!')
  } catch (error) {
    alert('Gagal menghapus artikel. Silakan coba lagi.')
  }
}
</script>
