@php($rtl = app()->getLocale() === 'ar')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title') | {{ __('admin.brand') }}</title>
<script>if(localStorage.theme==='dark'||(!('theme' in localStorage)&&matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark')</script>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['"IBM Plex Sans Arabic"','system-ui','sans-serif']},colors:{ink:'#0F1B2D',signal:'#F59E0B',paper:'#F3F5F7',panel:'#121C2E',night:'#0B1220'}}}}</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-paper dark:bg-night text-slate-800 dark:text-slate-200 font-sans antialiased"
      x-data="{ open:false, dark:document.documentElement.classList.contains('dark') }">
@php($nav = [
  ['dashboard','home','M3 12l9-9 9 9M5 10v10h14V10'],
  ['dashboard.products','products','M21 8l-9-5-9 5v8l9 5 9-5V8zM3 8l9 5 9-5M12 13v8'],
  ['dashboard.about','about','M12 21a9 9 0 100-18 9 9 0 000 18zM12 8h.01M11 12h1v5h1'],
  ['dashboard.contact','contact','M3 6h18v12H3zM3 6l9 7 9-7'],
])
<aside class="fixed inset-y-0 start-0 z-40 w-64 bg-ink dark:bg-black/40 text-slate-300 flex flex-col transition-transform lg:!translate-x-0"
:class="open ? '' : '-translate-x-full rtl:translate-x-full'">
  <div class="h-16 px-5 flex items-center gap-3 border-b border-white/10">
    <span class="w-8 h-8 rounded-full border-2 border-signal grid place-items-center"><span class="w-2.5 h-2.5 rounded-full bg-signal"></span></span>
    <span class="font-bold text-white">{{ __('admin.brand') }}</span>
  </div>
  <nav class="flex-1 p-3 space-y-1">
    @foreach($nav as [$route,$key,$icon])
      <a href="{{ route($route) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-md border-s-2 {{ request()->routeIs($route) ? 'bg-white/10 text-white border-signal' : 'border-transparent hover:bg-white/5' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
        {{ __('admin.'.$key) }}
      </a>
    @endforeach
  </nav>
</aside>
<div x-show="open" @click="open=false" class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-cloak></div>
<div class="lg:ps-64">
    <header class="h-16 sticky top-0 z-20 flex items-center gap-3 px-4 lg:px-8 bg-paper/90 dark:bg-night/90 backdrop-blur border-b border-slate-200 dark:border-white/10">
    <button class="lg:hidden p-2 -ms-2" @click="open=true" aria-label="Menu">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
    <input type="search" placeholder="{{ __('admin.search') }}" class="hidden sm:block w-72 rounded-md border border-slate-300 dark:border-white/15 bg-white dark:bg-panel px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-signal">
    <div class="ms-auto flex items-center gap-2">
        <a href="{{ route('lang.switch', $rtl ? 'en' : 'ar') }}" class="px-3 py-1.5 rounded-md border border-slate-300 dark:border-white/15 text-sm font-medium hover:bg-white dark:hover:bg-panel">{{ $rtl ? 'English' : 'العربية' }}</a>

        <button type="button" aria-label="Toggle theme"
                @click="dark=!dark; document.documentElement.classList.toggle('dark',dark); localStorage.theme=dark?'dark':'light'"
                class="p-2 rounded-md border border-slate-300 dark:border-white/15 hover:bg-white dark:hover:bg-panel">
          <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
          <svg x-show="dark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
        </button>

        <div class="relative" x-data="{ menu:false }" @click.outside="menu=false" @keydown.escape.window="menu=false">
          <button type="button" @click="menu=!menu" :aria-expanded="menu" aria-label="Account menu"
                  class="w-9 h-9 rounded-full bg-signal text-ink font-bold grid place-items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-signal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night">
            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
          </button>

          <div x-show="menu" x-cloak x-transition.opacity.duration.100ms
               class="absolute end-0 mt-2 w-56 py-1 z-50 rounded-lg border border-slate-200 dark:border-white/10 bg-white dark:bg-panel shadow-lg">
            <div class="px-4 py-2 border-b border-slate-100 dark:border-white/10">
              <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
              <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-100 dark:hover:bg-white/5">{{ __('admin.edit_profile') }}</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="w-full text-start px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-slate-100 dark:hover:bg-white/5">{{ __('admin.logout') }}</button>
            </form>
          </div>
        </div>
      </div>
  </header>
  <main class="p-4 lg:p-8 max-w-7xl">
    <h1 class="text-2xl font-bold mb-6">@yield('title')</h1>
    @yield('content')
  </main>
</div>
</body>
</html>
