@php
  $slides = collect($data['slides'] ?? []);
  $height = $style['--section-height'] ?? '82vh';
@endphp

<section class="memory-section !py-0" style="{{ memory_style($style) }}">
  <div class="swiper" data-memory-slider data-loop="true" data-autoplay="{{ ($data['autoplay'] ?? true) ? 'true' : 'false' }}">
    <div class="swiper-wrapper">
      @foreach($slides as $slide)
        @php($media = $mediaById->get($slide['media_id'] ?? null))
        @if($media)
          <figure class="swiper-slide relative bg-black" style="height: {{ $height }}">
            <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $slide['caption'] ?? '' }}" loading="lazy" class="h-full w-full object-cover opacity-90">
            <div class="absolute inset-0 bg-gradient-to-b from-black/15 via-black/20 to-black/80"></div>
            <figcaption class="absolute bottom-12 left-1/2 w-[min(100%-32px,960px)] -translate-x-1/2 text-white">
              @if(! empty($slide['title']))
                <h3 class="memory-heading text-4xl font-semibold">{{ $slide['title'] }}</h3>
              @endif
              @if(! empty($slide['caption']))
                <p class="mt-3 max-w-xl text-lg text-white/75">{{ $slide['caption'] }}</p>
              @endif
            </figcaption>
          </figure>
        @endif
      @endforeach
    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>

