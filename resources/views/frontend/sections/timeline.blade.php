@php
  $items = collect($data['items'] ?? []);
@endphp

@if($items->isNotEmpty())
  <section class="memory-section-frame bg-[#fffaf5] px-4 py-12 sm:px-5 sm:py-20" style="{{ memory_style($style) }}">
    <div class="mx-auto max-w-6xl">
      @if($section->title || $section->subtitle)
        <div class="mb-8 max-w-2xl sm:mb-10">
          @if($section->title)
            <h2 class="memory-heading text-balance text-3xl font-semibold leading-tight text-[#32131c] sm:text-5xl">{{ $section->title }}</h2>
          @endif
          @if($section->subtitle)
            <p class="mt-3 text-sm leading-6 text-[#7b6258] sm:text-base sm:leading-7">{{ $section->subtitle }}</p>
          @endif
        </div>
      @endif

      <div class="relative space-y-8 before:absolute before:left-[1.15rem] before:top-1 before:h-[calc(100%_-_0.5rem)] before:w-px before:bg-[#d9b9aa] sm:space-y-10 lg:before:left-1/2">
        @foreach($items as $item)
          @php
            $media = $mediaById->get($item['media_id'] ?? null);
          @endphp
          <article class="relative grid gap-4 pl-12 lg:grid-cols-2 lg:gap-10 lg:pl-0">
            <span aria-hidden="true" class="absolute left-[0.8rem] top-3 z-10 h-4 w-4 rounded-full border-4 border-[#fffaf5] bg-[#d84d80] shadow-[0_0_0_7px_rgba(216,77,128,0.12)] lg:left-1/2 lg:-ml-2"></span>

            <div class="{{ $loop->odd ? 'lg:order-1 lg:pr-10' : 'lg:order-2 lg:pl-10' }}">
              @if($media)
                <figure class="group overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_22px_80px_rgba(74,39,32,0.12)] ring-1 ring-[#ead7ca]">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($item['title'] ?? $post->title) }}" loading="lazy" class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-[1.025]">
                </figure>
              @endif
            </div>

            <div class="{{ $loop->odd ? 'lg:order-2 lg:pl-10' : 'lg:order-1 lg:pr-10 lg:text-right' }}">
              <div class="max-w-xl {{ $loop->even ? 'lg:ml-auto' : '' }}">
                @if(! empty($item['time']))
                  <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#b75b72]">{{ $item['time'] }}</p>
                @endif
                @if(! empty($item['title']))
                  <h3 class="memory-heading mt-2 text-balance text-3xl font-semibold leading-tight text-[#32131c]">{{ $item['title'] }}</h3>
                @endif
                @if(! empty($item['body']))
                  <p class="mt-3 text-sm leading-7 text-[#6b554d]">{{ $item['body'] }}</p>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
