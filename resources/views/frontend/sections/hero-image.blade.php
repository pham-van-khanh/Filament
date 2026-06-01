@php
  $media = $mediaById->get($data['media_id'] ?? null) ?: $post->coverMedia;
  $height = $style['--section-height'] ?? 'min(82svh, 760px)';
  $primary = data_get($tokens, 'colors.primary', data_get($tokens, 'colors.accent', '#812744'));
  $dateLine = $data['date_range'] ?? $post->date_range;
@endphp

<section class="relative overflow-hidden bg-[#241218] text-white" style="background-color: {{ $primary }}">
  <div class="relative min-h-[620px] md:min-h-[720px]" style="min-height: {{ $height }}">
    @if($media)
      <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($data['headline'] ?? $post->title) }}" class="absolute inset-0 h-full w-full object-cover">
    @endif

    <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/20 to-[#241218]/92"></div>
    <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/45 to-transparent"></div>

    <div class="relative z-10 flex min-h-[620px] flex-col justify-between px-4 pb-8 pt-4 md:min-h-[720px] md:px-8 md:pb-12" style="min-height: {{ $height }}">
      <div class="flex items-center justify-between gap-3">
        <a href="{{ route('home') }}" aria-label="Về trang chủ" class="grid h-11 w-11 place-items-center rounded-full bg-white/14 text-white backdrop-blur-xl transition hover:bg-white/22 focus:outline-none focus:ring-2 focus:ring-white/70">
          <x-ui-icon name="arrow-left" class="h-5 w-5" />
        </a>

        <div class="flex items-center gap-2 rounded-full bg-white/14 px-3 py-2 text-xs font-semibold text-white/88 backdrop-blur-xl">
          <x-ui-icon name="images" class="h-4 w-4" />
          {{ $post->media_count ?: $post->media()->count() }} ảnh
        </div>
      </div>

      <div class="mx-auto w-full max-w-6xl">
        <div class="max-w-3xl">
          @if($post->category)
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/14 px-3 py-1.5 text-xs font-semibold text-white/90 backdrop-blur-xl">
              <x-ui-icon name="tag" class="h-3.5 w-3.5" />
              {{ $post->category->name }}
            </span>
          @endif

          <h1 class="memory-heading mt-4 max-w-4xl text-balance text-5xl font-semibold leading-[0.95] tracking-normal text-white sm:text-6xl lg:text-7xl">
            {{ $data['headline'] ?: $post->title }}
          </h1>

          <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-medium text-white/76 sm:text-base">
            @if($dateLine)
              <span>{{ $dateLine }}</span>
            @endif
            @if($post->location_name)
              <span class="inline-flex items-center gap-1.5">
                <x-ui-icon name="map-pin" class="h-4 w-4" />
                {{ $post->location_name }}
              </span>
            @endif
          </div>

          @if(! empty($data['tags']))
            <div class="mt-5 flex max-w-2xl gap-2 overflow-x-auto pb-1 [scrollbar-width:none]">
              @foreach($data['tags'] as $tag)
                <span class="shrink-0 rounded-full bg-white/13 px-3 py-1.5 text-xs font-semibold text-white/86 backdrop-blur">{{ $tag }}</span>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
