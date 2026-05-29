<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="@yield('description', 'chuaminh.vn - visual memory website.')">
  <link rel="canonical" href="{{ url()->current() }}">
  @yield('meta')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Dancing+Script:wght@600&family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-shell @yield('body_class')">
  <header class="hidden border-b border-black/5 bg-white/85 backdrop-blur-xl md:block">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
      <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight text-[#812744]">chuaminh.vn</a>
      <nav class="flex items-center gap-7 text-sm font-medium text-neutral-600">
        <a href="{{ route('memories.index') }}" class="hover:text-[#d84d80]">Bo suu tap</a>
        <a href="{{ route('gallery.index') }}" class="hover:text-[#d84d80]">Thu vien anh</a>
        <a href="{{ route('map.index') }}" class="hover:text-[#d84d80]">Ban do</a>
        <a href="{{ route('timeline.index') }}" class="hover:text-[#d84d80]">Dong thoi gian</a>
        <a href="/admin" class="rounded-full border border-[#d84d80]/30 px-4 py-2 text-[#812744] hover:bg-[#fce8f0]">Admin</a>
      </nav>
    </div>
  </header>

  @if(session('status'))
    <div class="fixed left-1/2 top-4 z-[80] -translate-x-1/2 rounded-full bg-[#812744] px-4 py-2 text-sm font-semibold text-white shadow-xl">
      {{ session('status') }}
    </div>
  @endif

  <main class="min-h-screen pb-24 md:pb-0">
    @yield('content')
  </main>

  <nav data-bottom-navigation class="fixed inset-x-0 bottom-0 z-50 border-t border-black/10 bg-white/95 px-2 pb-[max(8px,env(safe-area-inset-bottom))] pt-2 backdrop-blur-xl md:hidden">
    <div class="mx-auto grid max-w-md grid-cols-4 text-center text-[12px] font-medium text-neutral-500">
      <a href="{{ route('home') }}" class="flex min-h-[54px] flex-col items-center justify-center gap-1 rounded-lg px-1 py-1.5 {{ request()->routeIs('home') ? 'bg-[#fff3f8] text-[#e34d86]' : '' }}">
        <svg aria-hidden="true" class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 10.75 9-7.25 9 7.25"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.5 9.5v10.25h13V9.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19.75v-6h5v6"/></svg>
        Trang chủ
      </a>
      <a href="{{ route('memories.index') }}" class="flex min-h-[54px] flex-col items-center justify-center gap-1 rounded-lg px-1 py-1.5 {{ request()->routeIs('memories.*') ? 'bg-[#fff3f8] text-[#e34d86]' : '' }}">
        <svg aria-hidden="true" class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.5" y="4" width="17" height="16" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="m6.5 16 4.2-4.5 3.1 3 2.1-2.1 2.6 3.6"/><circle cx="15.75" cy="8.5" r="1.25"/></svg>
        Bộ sưu tập
      </a>
      <a href="{{ route('map.index') }}" class="flex min-h-[54px] flex-col items-center justify-center gap-1 rounded-lg px-1 py-1.5 {{ request()->routeIs('map.*') ? 'bg-[#fff3f8] text-[#e34d86]' : '' }}">
        <svg aria-hidden="true" class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s6.25-5.45 6.25-11A6.25 6.25 0 0 0 5.75 10c0 5.55 6.25 11 6.25 11Z"/><circle cx="12" cy="10" r="2.2"/></svg>
        Bản đồ
      </a>
      <a href="{{ route('timeline.index') }}" class="flex min-h-[54px] flex-col items-center justify-center gap-1 rounded-lg px-1 py-1.5 {{ request()->routeIs('timeline.*') ? 'bg-[#fff3f8] text-[#e34d86]' : '' }}">
        <svg aria-hidden="true" class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v5l3.25 2"/></svg>
        Dòng thời gian
      </a>
    </div>
  </nav>
</body>
</html>
