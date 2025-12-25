<template>
  <div class="min-h-screen bg-[--color-neutral] text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <!-- Navbar Modern dengan Gradient -->
    <nav class="sticky top-0 z-50 navbar-glass navbar-border text-white shadow-xl">
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <!-- Logo & Brand -->
          <router-link to="/" class="flex items-center space-x-3 group">
            <div class="relative">
              <div class="absolute inset-0 bg-white/20 rounded-lg blur-md group-hover:blur-lg transition-all"></div>
              <div class="relative bg-white/10 p-2 rounded-lg backdrop-blur-sm">
                <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-4.41 0-8-3.59-8-8V8.3l8-4.44 8 4.44V12c0 4.41-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                </svg>
              </div>
            </div>
            <div>
              <div class="font-bold text-xl text-white tracking-tight">Game Politik</div>
              <div class="text-xs text-white/70">Portal Berita & Analisis</div>
            </div>
          </router-link>

          <!-- Desktop Navigation (icon-only) -->
          <div class="hidden lg:flex items-center space-x-2">
            <router-link to="/" class="p-2.5 rounded-lg text-white/90 hover:text-white hover:bg-white/10 transition-all" aria-label="Beranda">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            </router-link>
            <button class="p-2.5 rounded-lg text-white/90 hover:text-white hover:bg-white/10 transition-all" @click="goNews" aria-label="Berita">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6zm0 5h10v2H4v-2zm0 5h16v2H4v-2z"/></svg>
            </button>
          </div>

          <!-- Right Actions -->
          <div class="flex items-center space-x-3">
            <!-- Search Bar -->
            <div class="hidden md:block relative group">
              <input 
                v-model="q" 
                @keyup.enter="search" 
                type="search" 
                placeholder="Cari artikel..." 
                class="search-input w-64 pl-10 pr-4 py-2.5 rounded-xl bg-white text-black placeholder-[#000000] border border-white/20 outline-none focus:border-white transition-all"
              />
              <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/70 group-focus-within:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
              </svg>
            </div>

            <!-- Theme Toggle -->
            <button 
              @click="toggleTheme" 
              class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 transition-all group"
              aria-label="Toggle theme"
            >
              <svg v-if="!isDark" class="w-5 h-5 text-white group-hover:rotate-180 transition-transform duration-500" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 18a6 6 0 110-12 6 6 0 010 12zm0 4a1 1 0 001-1v-1a1 1 0 10-2 0v1a1 1 0 001 1zm0-19a1 1 0 001-1V1a1 1 0 10-2 0v1a1 1 0 001 1zM4.22 5.64a1 1 0 10-1.41-1.41l-.7.7a1 1 0 101.41 1.41l.7-.7zm16.69-.71a1 1 0 00-1.41 0l-.7.7a1 1 0 101.41 1.41l.7-.7a1 1 0 000-1.41zM4 12a1 1 0 01-1-1H2a1 1 0 100 2h1a1 1 0 011-1zm18 0a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zM4.22 18.36l-.7.7a1 1 0 101.41 1.41l.7-.7a1 1 0 10-1.41-1.41zm15.36.7l.7.7a1 1 0 001.41-1.41l-.7-.7a1 1 0 10-1.41 1.41z"/>
              </svg>
              <svg v-else class="w-5 h-5 text-white group-hover:rotate-180 transition-transform duration-500" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/>
              </svg>
            </button>

            <!-- Auth Buttons -->
            <router-link 
              v-if="!isAuth" 
              to="/login" 
              class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 transition-all"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Login
            </router-link>
            <button 
              v-else 
              @click="logout" 
              class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl border border-red-500/30 transition-all"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
              </svg>
              Logout
            </button>

            <!-- Mobile Menu Toggle -->
            <button 
              @click="menuOpen = !menuOpen" 
              class="lg:hidden p-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 transition-all"
            >
              <svg v-if="!menuOpen" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
              </svg>
              <svg v-else class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="menuOpen" class="lg:hidden pb-4 space-y-2 animate-fade-in">
          <div class="md:hidden mb-3">
            <div class="relative">
              <input 
                v-model="q" 
                @keyup.enter="search" 
                type="search" 
                placeholder="Cari artikel..." 
                class="search-input w-full pl-10 pr-4 py-2.5 rounded-xl bg-white text-black placeholder-[#000000] border border-white/20 outline-none focus:border-white transition-all"
              />
              <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/70" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
              </svg>
            </div>
          </div>
          <div class="flex flex-wrap gap-2">
            <router-link to="/" class="p-2.5 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-all" aria-label="Beranda">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            </router-link>
            <button class="p-2.5 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-all" @click="goNews" aria-label="Berita">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6zm0 5h10v2H4v-2zm0 5h16v2H4v-2z"/></svg>
            </button>
          </div>
          <div class="sm:hidden flex gap-2 pt-2">
            <router-link 
              v-if="!isAuth" 
              to="/login" 
              class="flex-1 text-center px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 transition-all"
            >
              Login
            </router-link>
            <button 
              v-else 
              @click="logout" 
              class="flex-1 px-4 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-white rounded-xl border border-red-500/30 transition-all"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <router-view />
    </main>

    <!-- Modern Footer -->
    <footer class="relative mt-20 border-t border-gray-200 dark:border-gray-800">
      <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 opacity-50"></div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          <div>
            <h3 class="font-bold text-lg mb-3 gradient-text">Game Politik</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Portal berita dan analisis terkini seputar game, politik, dan persilangan keduanya.</p>
          </div>
          <div>
            <h4 class="font-semibold mb-3">Kategori</h4>
            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
              <li><a href="#" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Game</a></li>
              <li><a href="#" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Politik</a></li>
              <li><a href="#" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Game Politik</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-semibold mb-3">Ikuti Kami</h4>
            <div class="flex gap-3">
              <a href="#" class="w-10 h-10 rounded-full bg-gradient-primary flex items-center justify-center text-white hover:scale-110 transition-transform">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a href="#" class="w-10 h-10 rounded-full bg-gradient-secondary flex items-center justify-center text-white hover:scale-110 transition-transform">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
              </a>
              <a href="#" class="w-10 h-10 rounded-full bg-gradient-success flex items-center justify-center text-white hover:scale-110 transition-transform">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/></svg>
              </a>
            </div>
          </div>
        </div>
        <div class="pt-8 border-t border-gray-200 dark:border-gray-800 text-center text-sm text-gray-600 dark:text-gray-400">
          © {{ new Date().getFullYear() }} Game Politik. Dibangun dengan ❤️ menggunakan Laravel + Vue + Tailwind CSS.
        </div>
      </div>
    </footer>

    <!-- Floating Action Button -->
    <router-link 
      to="/articles/new" 
      class="fixed bottom-8 right-8 btn-gradient text-white p-4 rounded-full shadow-2xl hover:shadow-purple-500/50 z-40 group"
    >
      <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 24 24" fill="currentColor">
        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
      </svg>
    </router-link>
  </div>
</template>

<script setup>
import axios from 'axios'
import { computed, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const isAuth = computed(() => !!localStorage.getItem('token'))
const isDark = ref(false)
const menuOpen = ref(false)
const q = ref('')
const router = useRouter()

const applyTheme = () => {
  const dark = localStorage.getItem('theme') === 'dark'
  isDark.value = dark
  document.documentElement.classList.toggle('dark', dark)
}

const toggleTheme = () => {
  const next = isDark.value ? 'light' : 'dark'
  localStorage.setItem('theme', next)
  applyTheme()
}

onMounted(applyTheme)

const search = () => {
  const term = q.value.trim()
  if (term) {
    router.push({ path: '/', query: { q: term } })
  } else {
    router.push({ path: '/', query: {} })
  }
  console.debug('Search submitted', { query: term })
  menuOpen.value = false
}

const goNews = () => {
  router.push('/news')
  menuOpen.value = false
}

const logout = async () => {
  if (confirm('Apakah Anda yakin ingin logout?')) {
    try {
      await axios.post('/auth/logout')
    } catch {}
    localStorage.removeItem('token')
    window.location.href = '/'
  }
}
</script>

