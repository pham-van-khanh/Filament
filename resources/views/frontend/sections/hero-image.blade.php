@php
  $media = $mediaById->get($data['media_id'] ?? null) ?: $post->coverMedia;
  $height = $style['--section-height'] ?? 'min(92svh, 820px)';
  $primary = data_get($tokens, 'colors.primary', data_get($tokens, 'colors.accent', '#812744'));
  $dateLine = $data['date_range'] ?? $post->date_range;
  $headline = ($data['headline'] ?? null) ?: $post->title;
@endphp

<section class="relative isolate overflow-hidden bg-[#32131c] text-white" style="background-color: {{ $primary }}">
  <div class="relative min-h-[100dvh] md:min-h-[760px]" style="min-height: {{ $height }}">
    @if($media)
      <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: $headline }}" fetchpriority="high" class="absolute inset-0 h-full w-full object-cover">
    @endif

    <div class="absolute inset-0 bg-gradient-to-b from-[#14070c]/42 via-[#14070c]/12 to-[#32131c]/94"></div>
    <div class="absolute inset-y-0 left-0 hidden w-3/5 bg-gradient-to-r from-[#14070c]/48 to-transparent md:block"></div>

    <div class="relative z-10 flex min-h-[100dvh] flex-col justify-between px-4 pb-8 pt-4 md:min-h-[760px] md:px-8 md:pb-12" style="min-height: {{ $height }}">
      <nav class="flex items-center justify-between gap-3" aria-label="Memory navigation">
        <a href="{{ route('home') }}" aria-label="Về trang chủ" class="grid h-11 w-11 place-items-center rounded-full bg-white/14 text-white backdrop-blur-xl transition hover:bg-white/22 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70 active:translate-y-px">
          <x-ui-icon name="arrow-left" class="h-5 w-5" />
        </a>

        <div class="flex min-w-0 items-center gap-2 rounded-full bg-white/14 px-3 py-2 text-xs font-semibold text-white/88 backdrop-blur-xl">
          <x-ui-icon name="images" class="h-4 w-4 shrink-0" />
          <span class="truncate">{{ $post->media_count ?: $post->media()->count() }} ảnh</span>
        </div>
      </nav>

      <div class="mx-auto w-full max-w-6xl">
        <div class="max-w-4xl">
          @if($post->category)
            <span class="inline-flex max-w-full items-center gap-2 rounded-full border border-white/20 bg-white/14 px-3 py-1.5 text-xs font-semibold text-white/90 backdrop-blur-xl">
              <x-ui-icon name="tag" class="h-3.5 w-3.5 shrink-0" />
              <span class="truncate">{{ $post->category->name }}</span>
            </span>
          @endif

          <h1 class="memory-heading mt-4 max-w-4xl text-balance text-5xl font-semibold leading-[0.95] tracking-normal text-white sm:text-6xl lg:text-7xl">
            {{ $headline }}
          </h1>

          @if($post->excerpt)
            <p class="mt-4 max-w-2xl text-base leading-7 text-white/76 sm:text-lg sm:leading-8">
              {{ $post->excerpt }}
            </p>
          @endif

          <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-medium text-white/76 sm:text-base">
            @if($dateLine)
              <span>{{ $dateLine }}</span>
            @endif
            @if($post->location_name)
              <span class="inline-flex min-w-0 items-center gap-1.5">
                <x-ui-icon name="map-pin" class="h-4 w-4 shrink-0" />
                <span class="truncate">{{ $post->location_name }}</span>
              </span>
            @endif
          </div>

          @if($post->tags->isNotEmpty())
            <div class="mt-5 flex max-w-2xl gap-2 overflow-x-auto pb-1 [scrollbar-width:none]">
              @foreach($post->tags as $tag)
                <span class="shrink-0 rounded-full bg-white/13 px-3 py-1.5 text-xs font-semibold text-white/86 backdrop-blur">{{ $tag->name }}</span>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
