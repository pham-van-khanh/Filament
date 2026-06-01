@php
  $slides = collect($data['slides'] ?? []);
  $height = $style['--section-height'] ?? '84svh';
@endphp

@if($slides->isNotEmpty())
  <section class="memory-section !py-0" style="{{ memory_style($style) }}">
    <div class="swiper" data-memory-slider data-loop="true" data-autoplay="{{ ($data['autoplay'] ?? true) ? 'true' : 'false' }}">
      <div class="swiper-wrapper">
        @foreach($slides as $slide)
          @php
            $media = $mediaById->get($slide['media_id'] ?? null);
          @endphp
          @if($media)
            <figure class="swiper-slide relative bg-[#14090d]" style="height: {{ $height }}">
              <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($slide['caption'] ?? $slide['title'] ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover opacity-90">
              <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/10 to-[#14090d]/86"></div>
              <figcaption class="absolute bottom-10 left-1/2 w-[min(100%-32px,960px)] -translate-x-1/2 text-white sm:bottom-14">
                @if(! empty($slide['title']))
                  <h3 class="memory-heading max-w-3xl text-balance text-4xl font-semibold leading-tight sm:text-6xl">{{ $slide['title'] }}</h3>
                @endif
                @if(! empty($slide['caption']))
                  <p class="mt-3 max-w-xl text-sm leading-6 text-white/72 sm:text-lg sm:leading-8">{{ $slide['caption'] }}</p>
                @endif
              </figcaption>
            </figure>
          @endif
        @endforeach
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>
@endif
