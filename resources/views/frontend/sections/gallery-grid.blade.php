@php
  $items = collect($data['items'] ?? []);
  $ids = $items->pluck('media_id')->filter()->values();

  if ($ids->isEmpty()) {
      $ids = collect($data['media_ids'] ?? [])->filter()->values();
  }

  $layout = $data['layout'] ?? 'mosaic';
  $visibleLimit = match ($layout) {
      'grid_2' => 8,
      'grid_3' => 9,
      'featured_left' => 5,
      'polaroid' => 4,
      'film_strip', 'masonry' => $ids->count(),
      default => 6,
  };
  $visibleIds = $ids->take($visibleLimit)->values();
  $hiddenIds = $ids->slice($visibleLimit)->values();
  $remaining = $hiddenIds->count();
  $visibleCount = $visibleIds->count();

  $captionFor = function ($id) use ($items) {
      $item = $items->first(fn ($row) => (int) ($row['media_id'] ?? 0) === (int) $id);

      return $item['caption'] ?? null;
  };
@endphp

@if($ids->isNotEmpty())
  <section class="memory-section-frame bg-[#fff8f3] px-4 py-14 sm:px-5 sm:py-20">
    <div class="mx-auto max-w-6xl">
      @if($section->title || $section->subtitle)
        <div class="mb-6 grid gap-3 sm:mb-8 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
          <div class="max-w-2xl">
            <p class="font-['Dancing_Script'] text-2xl leading-none text-[#c05779] sm:text-3xl">
              {{ $layout === 'polaroid' ? 'Những khung hình' : 'Góc ảnh nhỏ' }}
            </p>
            @if($section->title)
              <h2 class="memory-heading mt-1 text-balance text-[2rem] font-semibold leading-[1.05] text-[#32131c] sm:text-5xl">{{ $section->title }}</h2>
            @endif
            @if($section->subtitle)
              <p class="mt-3 text-sm leading-6 text-[#7b6258] sm:text-base sm:leading-7">{{ $section->subtitle }}</p>
            @endif
          </div>
          <span class="inline-flex w-fit items-center gap-2 rounded-full border border-[#ead7ca] bg-white/78 px-3 py-2 text-xs font-semibold text-[#8b4b42] shadow-sm">
            <x-ui-icon name="images" class="h-4 w-4" />
            {{ $ids->count() }} ảnh
          </span>
        </div>
      @endif

      <div class="memory-gallery-shell" data-lightbox>
        @if($layout === 'film_strip')
          <div class="-mx-4 flex snap-x gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] sm:mx-0 sm:px-0">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
                $caption = $captionFor($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group relative block h-[68svh] max-h-[590px] min-h-[360px] w-[82%] shrink-0 snap-center overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_22px_80px_rgba(74,39,32,0.13)] ring-1 ring-[#ead7ca] sm:w-[46%] lg:w-[34%]" aria-label="Mở ảnh {{ $loop->iteration }}">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($caption ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.035]">
                  @if($caption)
                    <span class="absolute inset-x-3 bottom-3 rounded-2xl bg-[#241218]/48 px-3 py-2 text-xs font-semibold leading-5 text-white backdrop-blur">{{ $caption }}</span>
                  @endif
                </a>
              @endif
            @endforeach
          </div>
        @elseif($layout === 'polaroid')
          <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-5">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
                $caption = $captionFor($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group relative block rotate-[-1deg] rounded-[1.5rem] bg-white p-2 shadow-[0_16px_40px_rgba(74,39,32,0.11)] ring-1 ring-[#f0d3d9] transition duration-300 hover:-translate-y-1 odd:rotate-[0.8deg] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#d84d80]" aria-label="Mở ảnh {{ $loop->iteration }}">
                  <span class="block h-40 overflow-hidden rounded-[1.15rem] bg-[#eadbd1] sm:h-52">
                    <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($caption ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.035]">
                  </span>
                  @if($caption)
                    <span class="line-clamp-2 min-h-[2.65rem] px-1 pt-2 text-left text-[0.72rem] font-semibold leading-5 text-[#7b6258]">{{ $caption }}</span>
                  @endif
                  @if($loop->last && $remaining > 0)
                    <span class="absolute inset-2 grid place-items-center rounded-[1.15rem] bg-[#32131c]/42 px-4 text-center text-sm font-semibold leading-5 text-white backdrop-blur-sm">
                      Xem thêm {{ $remaining }} ảnh
                      <span class="sr-only">+{{ $remaining }}</span>
                    </span>
                  @endif
                </a>
              @endif
            @endforeach
          </div>
        @elseif($layout === 'masonry')
          <div class="columns-2 gap-2 sm:columns-3 sm:gap-4">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
                $caption = $captionFor($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group mb-2 block break-inside-avoid overflow-hidden rounded-[1.5rem] bg-[#eadbd1] shadow-[0_12px_38px_rgba(74,39,32,0.09)] ring-1 ring-[#ead7ca] sm:mb-4 sm:rounded-[2rem]" aria-label="Mở ảnh {{ $loop->iteration }}">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($caption ?? $section->title ?? $post->title) }}" loading="lazy" class="w-full object-cover transition duration-700 group-hover:scale-[1.025]">
                </a>
              @endif
            @endforeach
          </div>
        @else
          @php
            $gridClass = match ($layout) {
                'grid_2' => 'grid-cols-2',
                'grid_3' => 'grid-cols-3',
                default => 'grid-cols-4',
            };
          @endphp

          <div class="grid {{ $gridClass }} auto-rows-[112px] gap-2 overflow-hidden rounded-[2rem] bg-[#eadbd1] p-2 shadow-[0_26px_92px_rgba(74,39,32,0.13)] ring-1 ring-[#ead7ca] sm:auto-rows-[178px] sm:gap-2 sm:p-2 lg:auto-rows-[218px]">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
                $caption = $captionFor($id);
              @endphp
              @if($media)
                @php
                  $tileClass = match ($layout) {
                      'grid_2', 'grid_3' => '',
                      'featured_left' => $loop->first ? 'col-span-2 row-span-2' : '',
                      default => match (true) {
                          $loop->first => 'col-span-2 row-span-2',
                          $loop->iteration === 4 => 'col-span-2',
                          $visibleCount === 5 && $loop->iteration === 5 => 'col-span-4',
                          $visibleCount >= 6 && in_array($loop->iteration, [5, 6], true) => 'col-span-2',
                          default => '',
                      },
                  };
                @endphp
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="{{ $tileClass }} group relative overflow-hidden rounded-[1.5rem] bg-[#d9c8bd] outline-none ring-0 transition focus-visible:ring-2 focus-visible:ring-[#d84d80]" aria-label="Mở ảnh {{ $loop->iteration }}">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($caption ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.035]">
                  <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#241218]/18 via-transparent to-transparent opacity-0 transition group-hover:opacity-100"></span>
                  @if($caption && !($loop->last && $remaining > 0))
                    <span class="absolute inset-x-2 bottom-2 hidden rounded-xl bg-[#241218]/48 px-2.5 py-1.5 text-xs font-semibold leading-5 text-white backdrop-blur sm:block">{{ $caption }}</span>
                  @endif
                  @if($loop->last && $remaining > 0)
                    <span class="absolute inset-0 grid place-items-center bg-[#32131c]/44 px-4 text-center text-sm font-semibold leading-5 text-white backdrop-blur-sm">
                      Xem thêm {{ $remaining }} ảnh
                      <span class="sr-only">+{{ $remaining }}</span>
                    </span>
                  @endif
                </a>
              @endif
            @endforeach
          </div>
        @endif

        @foreach($hiddenIds as $id)
          @php
            $media = $mediaById->get($id);
            $caption = $captionFor($id);
          @endphp
          @if($media)
            <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="hidden">
              {{ $caption ?: $media->alt ?: $section->title }}
            </a>
          @endif
        @endforeach
      </div>
    </div>
  </section>
@endif
