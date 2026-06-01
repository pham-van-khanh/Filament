@php
  $slides = collect($data['slides'] ?? []);
  $height = $style['--section-height'] ?? 'min(72svh, 560px)';
@endphp

@if($slides->isNotEmpty())
  <section class="overflow-hidden bg-[#f8f0ea] px-4 py-10 sm:px-5 sm:py-16">
    <div class="mx-auto max-w-6xl">
      <div class="mb-5 flex items-end justify-between gap-4">
        <div class="max-w-2xl">
          <p class="font-['Dancing_Script'] text-2xl text-[#c05779]">Những khung hình</p>
          <h2 class="memory-heading text-3xl font-semibold leading-tight text-[#32131c] sm:text-4xl">
            {{ $section->title ?: 'Khoảnh khắc nổi bật' }}
          </h2>
        </div>

        <div class="hidden items-center gap-2 sm:flex">
          <button type="button" class="memory-slider-prev grid h-11 w-11 place-items-center rounded-full border border-[#d8beb0] bg-white text-[#812744] transition hover:bg-[#fff7f1] focus:outline-none focus:ring-2 focus:ring-[#e6a4ba]" aria-label="Ảnh trước">
            <x-ui-icon name="arrow-left" class="h-4 w-4" />
          </button>
          <button type="button" class="memory-slider-next grid h-11 w-11 place-items-center rounded-full border border-[#d8beb0] bg-white text-[#812744] transition hover:bg-[#fff7f1] focus:outline-none focus:ring-2 focus:ring-[#e6a4ba]" aria-label="Ảnh tiếp theo">
            <x-ui-icon name="chevron-right" class="h-4 w-4" />
          </button>
        </div>
      </div>

      <div class="swiper -mx-4 overflow-visible px-4 sm:mx-0 sm:px-0" data-memory-slider data-loop="false" data-autoplay="{{ ($data['autoplay'] ?? false) ? 'true' : 'false' }}" data-slides-per-view="auto" data-space-between="14">
        <div class="swiper-wrapper">
          @foreach($slides as $slide)
            @php
              $media = $mediaById->get($slide['media_id'] ?? null);
            @endphp
            @if($media)
              <figure class="swiper-slide group relative !w-[84%] overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_22px_80px_rgba(74,39,32,0.12)] sm:!w-[54%] lg:!w-[38%]" style="height: {{ $height }}">
                <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($slide['caption'] ?? $slide['title'] ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#241218]/72 to-transparent p-4 text-white">
                  @if(! empty($slide['title']))
                    <h3 class="memory-heading text-2xl font-semibold leading-tight">{{ $slide['title'] }}</h3>
                  @endif
                  @if(! empty($slide['caption']))
                    <figcaption class="mt-1 line-clamp-2 text-sm leading-6 text-white/78">{{ $slide['caption'] }}</figcaption>
                  @else
                    <figcaption class="text-xs font-semibold text-white/62">{{ $loop->iteration }} / {{ $slides->count() }}</figcaption>
                  @endif
                </div>
              </figure>
            @endif
          @endforeach
        </div>

        <div class="swiper-pagination !relative !bottom-auto mt-4"></div>
      </div>
    </div>
  </section>
@endif
