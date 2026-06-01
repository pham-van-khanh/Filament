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
      'film_strip', 'polaroid', 'masonry' => $ids->count(),
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
  <section class="bg-[#fffaf5] px-4 py-10 sm:px-5 sm:py-16">
    <div class="mx-auto max-w-6xl">
      @if($section->title || $section->subtitle)
        <div class="mb-5 max-w-2xl">
          @if($section->title)
            <h2 class="memory-heading text-3xl font-semibold leading-tight text-[#32131c] sm:text-4xl">{{ $section->title }}</h2>
          @endif
          @if($section->subtitle)
            <p class="mt-2 text-sm leading-6 text-[#8d7168]">{{ $section->subtitle }}</p>
          @endif
        </div>
      @endif

      <div data-lightbox>
        @if($layout === 'film_strip')
          <div class="-mx-4 flex snap-x gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] sm:mx-0 sm:px-0">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group relative block h-[68svh] max-h-[560px] min-h-[360px] w-[82%] shrink-0 snap-center overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_18px_70px_rgba(74,39,32,0.12)] sm:w-[46%] lg:w-[34%]">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($captionFor($id) ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                  @if($captionFor($id))
                    <span class="absolute inset-x-3 bottom-3 rounded-2xl bg-black/30 px-3 py-2 text-xs font-semibold leading-5 text-white backdrop-blur">{{ $captionFor($id) }}</span>
                  @endif
                </a>
              @endif
            @endforeach
          </div>
        @elseif($layout === 'polaroid')
          <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group block rotate-[-1.4deg] bg-white p-2 shadow-[0_16px_45px_rgba(74,39,32,0.12)] ring-1 ring-[#ead7ca] transition duration-300 hover:-translate-y-1 odd:rotate-[1.2deg]">
                  <span class="block aspect-[4/5] overflow-hidden bg-[#eadbd1]">
                    <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($captionFor($id) ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                  </span>
                  @if($captionFor($id))
                    <span class="flex min-h-[2.75rem] items-center justify-center px-1 pt-2 text-center text-xs font-semibold leading-5 text-[#7b6258]">{{ $captionFor($id) }}</span>
                  @endif
                </a>
              @endif
            @endforeach
          </div>
        @elseif($layout === 'masonry')
          <div class="columns-2 gap-2 sm:columns-3 sm:gap-3">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group mb-2 block break-inside-avoid overflow-hidden rounded-3xl bg-[#eadbd1] shadow-sm sm:mb-3">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($captionFor($id) ?? $section->title ?? $post->title) }}" loading="lazy" class="w-full object-cover transition duration-700 group-hover:scale-105">
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

          <div class="grid {{ $gridClass }} auto-rows-[104px] gap-1.5 overflow-hidden rounded-[2rem] bg-[#eadbd1] p-1.5 shadow-[0_22px_80px_rgba(74,39,32,0.1)] sm:auto-rows-[170px] sm:gap-2 sm:p-2">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
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
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="{{ $tileClass }} group relative overflow-hidden rounded-2xl bg-[#d9c8bd]">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($captionFor($id) ?? $section->title ?? $post->title) }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                  @if($loop->last && $remaining > 0)
                    <span class="absolute inset-0 grid place-items-center bg-[#32131c]/52 text-2xl font-semibold text-white backdrop-blur-sm">+{{ $remaining }}</span>
                  @endif
                </a>
              @endif
            @endforeach
          </div>
        @endif

        @foreach($hiddenIds as $id)
          @php
            $media = $mediaById->get($id);
          @endphp
          @if($media)
            <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="hidden">
              {{ $captionFor($id) ?: $media->alt ?: $section->title }}
            </a>
          @endif
        @endforeach
      </div>
    </div>
  </section>
@endif
