@php
  $media = $mediaById->get($data['media_id'] ?? null) ?: $post->coverMedia;
  $height = $style['--section-height'] ?? '390px';
  $primary = data_get($tokens, 'colors.primary', data_get($tokens, 'colors.accent', '#0F4C81'));
@endphp

<section class="relative overflow-hidden text-white" style="background: {{ $primary }}">
  <div class="relative mx-auto max-w-[760px]">
    <div class="relative h-[var(--hero-height)] overflow-hidden" style="--hero-height: {{ $height }}">
      @if($media)
        <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $post->title }}" class="absolute inset-0 h-full w-full object-cover opacity-78">
      @endif
      <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(0,0,0,.10), rgba(0,0,0,.12), color-mix(in srgb, {{ $primary }} 92%, black));"></div>
      <div class="absolute left-5 top-5 flex items-center gap-3 text-sm font-semibold text-white/85">
        <a href="{{ route('home') }}" class="grid h-11 w-11 place-items-center rounded-full bg-white/16 backdrop-blur">□</a>
        <span class="rounded-full bg-white/16 px-4 py-2 backdrop-blur">□ {{ $post->media_count ?: $post->media()->count() }} anh</span>
      </div>
    </div>
    <div class="px-5 py-6" style="background: {{ $primary }}">
      @if($post->category)
        <span class="rounded-full bg-white/15 px-3 py-1 text-sm font-semibold text-white/90">□ {{ $post->category->name }}</span>
      @endif
      <h1 class="memory-heading mt-4 text-3xl font-bold leading-tight">{{ $data['headline'] ?? $post->title }}</h1>
      <p class="mt-2 text-base text-white/68">{{ $data['date_range'] ?? data_get($post->settings, 'date_range') }} · {{ $post->location_name }}</p>
      @if(! empty($data['tags']))
        <div class="mt-4 flex flex-wrap gap-2">
          @foreach($data['tags'] as $tag)
            <span class="rounded-full bg-white/14 px-3 py-1 text-sm text-white/85">{{ $tag }}</span>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>
