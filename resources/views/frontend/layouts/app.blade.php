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

  <nav class="fixed inset-x-0 bottom-0 z-50 border-t border-black/10 bg-white/92 px-2 pb-[max(8px,env(safe-area-inset-bottom))] pt-2 backdrop-blur-xl md:hidden">
    <div class="mx-auto grid max-w-md grid-cols-4 text-center text-[12px] font-medium text-neutral-500">
      <a href="{{ route('home') }}" class="rounded-2xl px-2 py-2 {{ request()->routeIs('home') ? 'text-[#e34d86]' : '' }}">
        <span class="mx-auto mb-1 block h-5 w-5 rounded-md border {{ request()->routeIs('home') ? 'border-[#e34d86] bg-[#fde7f0]' : 'border-neutral-400' }}"></span>
        Trang chu
      </a>
      <a href="{{ route('memories.index') }}" class="rounded-2xl px-2 py-2 {{ request()->routeIs('memories.*') ? 'text-[#e34d86]' : '' }}">
        <span class="mx-auto mb-1 block h-5 w-5 rounded-md border {{ request()->routeIs('memories.*') ? 'border-[#e34d86] bg-[#fde7f0]' : 'border-neutral-400' }}"></span>
        Bo suu tap
      </a>
      <a href="{{ route('map.index') }}" class="rounded-2xl px-2 py-2 {{ request()->routeIs('map.*') ? 'text-[#e34d86]' : '' }}">
        <span class="mx-auto mb-1 block h-5 w-5 rounded-md border {{ request()->routeIs('map.*') ? 'border-[#e34d86] bg-[#fde7f0]' : 'border-neutral-400' }}"></span>
        Ban do
      </a>
      <a href="{{ route('timeline.index') }}" class="rounded-2xl px-2 py-2 {{ request()->routeIs('timeline.*') ? 'text-[#e34d86]' : '' }}">
        <span class="mx-auto mb-1 block h-5 w-5 rounded-md border {{ request()->routeIs('timeline.*') ? 'border-[#e34d86] bg-[#fde7f0]' : 'border-neutral-400' }}"></span>
        Dong thoi gian
      </a>
    </div>
  </nav>
</body>
</html>
