@php
  $slides = collect($data['slides'] ?? []);
  $height = $style['--section-height'] ?? 'min(74svh, 590px)';
  $slideHeight = "max({$height}, 320px)";
@endphp

@if($slides->isNotEmpty())
  <section class="memory-section-frame overflow-hidden bg-[#f7eee8] px-4 py-14 sm:px-5 sm:py-20">
    <div class="mx-auto max-w-6xl">
      <div class="mb-5 flex items-end justify-between gap-4 sm:mb-7">
        <div class="max-w-2xl">
          <p class="font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">Những khung hình</p>
          <h2 class="memory-heading mt-1 text-balance text-[2rem] font-semibold leading-[1.05] text-[#32131c] sm:text-5xl">
            {{ $section->title ?: 'Khoảnh khắc nổi bật' }}
          </h2>
          @if($section->subtitle)
            <p class="mt-3 text-sm leading-6 text-[#7b6258] sm:text-base sm:leading-7">{{ $section->subtitle }}</p>
          @endif
        </div>

        <div class="hidden items-center gap-2 sm:flex">
          <button type="button" class="memory-slider-prev grid h-11 w-11 place-items-center rounded-full border border-[#d8beb0] bg-white text-[#812744] transition hover:-translate-y-0.5 hover:bg-[#fff7f1] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#e6a4ba] active:translate-y-0" aria-label="Ảnh trước">
            <x-ui-icon name="arrow-left" class="h-4 w-4" />
          </button>
          <button type="button" class="memory-slider-next grid h-11 w-11 place-items-center rounded-full border border-[#d8beb0] bg-white text-[#812744] transition hover:-translate-y-0.5 hover:bg-[#fff7f1] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#e6a4ba] active:translate-y-0" aria-label="Ảnh tiếp theo">
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
              <figure class="swiper-slide group relative !w-[88%] overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_24px_88px_rgba(74,39,32,0.14)] ring-1 ring-[#ead7ca] sm:!w-[54%] lg:!w-[38%]" style="height: {{ $slideHeight }}">
                <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($slide['caption'] ?? $slide['title'] ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.035]">
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#241218]/78 via-[#241218]/38 to-transparent p-4 text-white sm:p-5">
                  @if(! empty($slide['title']))
                    <h3 class="memory-heading text-2xl font-semibold leading-tight sm:text-3xl">{{ $slide['title'] }}</h3>
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

        <div class="swiper-pagination !relative !bottom-auto mt-5"></div>
      </div>
    </div>
  </section>
@endif
