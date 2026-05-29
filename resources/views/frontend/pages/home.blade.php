@extends('frontend.layouts.app')

@section('title', 'chuaminh.vn - Visual memory')

@section('content')
  <section class="bg-[#fbfaf7] px-4 pb-10 pt-8 md:px-6 md:py-12">
    <div class="mx-auto max-w-[430px] md:max-w-6xl">
      <div class="mb-7 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-3xl font-semibold tracking-tight text-[#812744]">chuaminh.vn</a>
        <div class="flex items-center gap-3">
          <a href="{{ route('search') }}" class="grid h-10 w-10 place-items-center rounded-full border border-black/10 bg-white text-neutral-500">⌕</a>
          <a href="/admin" class="grid h-10 w-10 place-items-center rounded-full border border-black/10 bg-white text-neutral-500">♡</a>
        </div>
      </div>

      <div class="-mx-4 mb-7 overflow-x-auto px-4 [scrollbar-width:none]">
        <div class="flex min-w-max gap-5">
          @foreach($categories as $category)
            @php
              $stickerIcon = match(mb_strtolower(trim($category->name))) {
                'tình yêu', 'tinh yeu' => '♡',
                'du lịch', 'du lich' => '✈',
                'dịp đặc biệt', 'dip dac biet' => '☆',
                'ngày thường', 'ngay thuong' => '☼',
                'khoảnh khắc', 'khoanh khac' => '📷',
                default => '◌',
              };
            @endphp
            <a href="{{ route('categories.show', $category->slug) }}" class="w-[78px] text-center">
              <span class="mx-auto block h-[68px] w-[68px] rounded-full border-[3px] border-[#b85586] p-1 shadow-sm ring-2 ring-[#4f8cff]/45" style="background: color-mix(in srgb, {{ $category->color ?? '#d84d80' }} 18%, white)">
                <span class="grid h-full w-full place-items-center rounded-full bg-white/45 text-xl" style="color: {{ $category->color ?? '#d84d80' }}">{{ $stickerIcon }}</span>
              </span>
              <span class="mt-2 block truncate text-sm font-medium text-neutral-600">{{ $category->name }}</span>
            </a>
          @endforeach
        </div>
      </div>

      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-neutral-950">Bo suu tap</h1>
        <a href="{{ route('memories.index') }}" class="text-base font-semibold text-[#e34d86]">Xem tat ca</a>
      </div>

      <div class="grid grid-cols-2 gap-1.5 md:grid-cols-4 md:gap-4">
        @foreach($latest->take(6) as $post)
          @php($tall = $loop->first || $loop->iteration === 5)
          <a href="{{ route('memories.show', $post->slug) }}" class="group relative overflow-hidden rounded-[18px] bg-[#dcecf8] {{ $tall ? 'row-span-2 min-h-[250px]' : 'min-h-[128px]' }} md:min-h-[270px]">
            @if($post->coverMedia)
              <img src="{{ $post->coverMedia->display_url }}" alt="{{ $post->coverMedia->alt ?? $post->title }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105">
            @endif
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/58"></div>
            @if($post->category)
              <span class="absolute left-4 top-4 inline-flex items-center gap-1 rounded-full bg-white/65 px-3 py-1 text-xs font-semibold backdrop-blur" style="color: {{ $post->category->color ?? '#2563eb' }}">
                <x-ui-icon name="tag" class="h-3 w-3" />
                {{ $post->category->name }}
              </span>
            @endif
            <div class="absolute inset-x-0 bottom-0 p-4 text-white">
              <h2 class="text-lg font-bold leading-tight">{{ $post->title }}</h2>
              <p class="mt-1 text-sm text-white/80">{{ $post->date_range ?: optional($post->memory_date)->format('d/m/Y') }}</p>
            </div>
          </a>
        @endforeach
      </div>

      <div class="my-6 h-px bg-black/10"></div>

      <div class="mb-5 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-neutral-950">Gan day nhat</h2>
        <a href="{{ route('memories.index') }}" class="text-base font-semibold text-[#e34d86]">Xem them</a>
      </div>

      <div class="divide-y divide-black/10">
        @foreach($latest->take(5) as $post)
          <a href="{{ route('memories.show', $post->slug) }}" class="grid grid-cols-[92px_1fr_24px] items-center gap-4 py-4">
            <div class="aspect-square overflow-hidden rounded-2xl bg-[#e7f2fb]">
              @if($post->coverMedia)
                <img src="{{ $post->coverMedia->display_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
              @endif
            </div>
            <div class="min-w-0">
              <h3 class="truncate text-lg font-semibold text-neutral-950">{{ $post->title }}</h3>
              <p class="mt-1 text-sm text-neutral-600">{{ $post->date_range ?: optional($post->memory_date)->format('d/m/Y') }} · {{ $post->media_count ?: 0 }} anh</p>
              @if($post->category)
                <span class="mt-2 inline-flex rounded-full bg-[#e4f2ff] px-3 py-1 text-xs font-semibold" style="color: {{ $post->category->color ?? '#2563eb' }}">{{ $post->category->name }}</span>
              @endif
            </div>
            <x-ui-icon name="chevron-right" class="h-5 w-5 text-neutral-400" />
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endsection
