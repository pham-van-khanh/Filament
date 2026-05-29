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
  <section class="bg-white px-5 pb-8">
    <div class="mx-auto max-w-[760px]">
      @if($section->title)
        <h2 class="mb-4 text-xl font-semibold text-neutral-950">{{ $section->title }}</h2>
      @endif

      <div data-lightbox>
        @if($layout === 'film_strip')
          <div class="-mx-5 flex snap-x gap-3 overflow-x-auto px-5 [scrollbar-width:none]">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group relative block h-72 w-[78%] shrink-0 snap-center overflow-hidden rounded-2xl bg-[#e7f2fb] md:w-[46%]">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $captionFor($id) ?? '' }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                </a>
              @endif
            @endforeach
          </div>
        @elseif($layout === 'polaroid')
          <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group block rotate-[-1deg] bg-white p-2 shadow-md ring-1 ring-black/5 odd:rotate-[1deg]">
                  <span class="block aspect-[4/5] overflow-hidden bg-[#e7f2fb]">
                    <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $captionFor($id) ?? '' }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                  </span>
                  @if($captionFor($id))
                    <span class="flex min-h-[2.75rem] items-center justify-center px-1 pt-2 text-center text-xs font-semibold leading-5 text-neutral-600">{{ $captionFor($id) }}</span>
                  @endif
                </a>
              @endif
            @endforeach
          </div>
        @elseif($layout === 'masonry')
          <div class="columns-2 gap-2 md:columns-3">
            @foreach($visibleIds as $id)
              @php
                $media = $mediaById->get($id);
              @endphp
              @if($media)
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="group mb-2 block break-inside-avoid overflow-hidden rounded-2xl bg-[#e7f2fb]">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $captionFor($id) ?? '' }}" loading="lazy" class="w-full object-cover transition duration-700 group-hover:scale-105">
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

          <div class="grid {{ $gridClass }} auto-rows-[116px] gap-1 overflow-hidden rounded-2xl md:auto-rows-[160px]">
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
                <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="{{ $tileClass }} group relative overflow-hidden bg-[#e7f2fb]">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $captionFor($id) ?? '' }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                  @if($loop->last && $remaining > 0)
                    <span class="absolute inset-0 grid place-items-center bg-black/45 text-2xl font-semibold text-white">+{{ $remaining }}</span>
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
