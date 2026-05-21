@php
  $slides = collect($data['slides'] ?? []);
  $height = $style['--section-height'] ?? '240px';
@endphp

<section class="bg-white px-5 py-6">
  <div class="mx-auto max-w-[760px]">
    <div class="mb-4 flex items-center justify-between">
      <h2 class="text-xl font-semibold text-neutral-950">{{ $section->title ?: 'Khoanh khac noi bat' }}</h2>
      <span class="text-sm text-neutral-500">1 / {{ max(1, $slides->count()) }}</span>
    </div>
    <div class="swiper -mr-5 overflow-visible" data-memory-slider data-loop="false" data-autoplay="{{ ($data['autoplay'] ?? false) ? 'true' : 'false' }}" data-slides-per-view="auto" data-space-between="12">
      <div class="swiper-wrapper">
        @foreach($slides as $slide)
          @php($media = $mediaById->get($slide['media_id'] ?? null))
          @if($media)
            <figure class="swiper-slide relative !w-[76%] overflow-hidden rounded-2xl bg-[#e7f2fb] md:!w-[42%]" style="height: {{ $height }}">
              <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $slide['caption'] ?? '' }}" loading="lazy" class="h-full w-full object-cover">
              <figcaption class="absolute bottom-3 right-3 rounded-full bg-black/25 px-2 py-1 text-xs font-semibold text-white">{{ $loop->iteration }}/{{ $slides->count() }}</figcaption>
            </figure>
          @endif
        @endforeach
      </div>
      <div class="swiper-pagination !relative !bottom-auto mt-3"></div>
    </div>
  </div>
</section>
