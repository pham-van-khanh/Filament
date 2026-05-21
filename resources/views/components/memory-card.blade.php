@props(['post'])

<a href="{{ route('memories.show', $post->slug) }}" class="group block overflow-hidden rounded-[18px] bg-white shadow-sm ring-1 ring-black/5">
  <div class="relative aspect-[4/5] overflow-hidden bg-[#e7f2fb]">
    @if($post->coverMedia)
      <img src="{{ $post->coverMedia->display_url }}" alt="{{ $post->coverMedia->alt ?? $post->title }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
    @endif
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/60"></div>
    @if($post->category)
      <span class="absolute left-4 top-4 rounded-full bg-white/70 px-3 py-1 text-xs font-semibold backdrop-blur" style="color: {{ $post->category->color ?? '#2563eb' }}">□ {{ $post->category->name }}</span>
    @endif
    <div class="absolute inset-x-0 bottom-0 p-4 text-white">
      <h3 class="text-xl font-bold leading-tight">{{ $post->title }}</h3>
      <p class="mt-1 text-sm text-white/78">{{ data_get($post->settings, 'date_range') ?: optional($post->memory_date)->format('d/m/Y') }}</p>
    </div>
  </div>
</a>
