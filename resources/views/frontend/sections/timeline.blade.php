@php
  $items = collect($data['items'] ?? []);
@endphp

@if($items->isNotEmpty())
  <section class="memory-section-frame bg-[#fffaf5] px-4 py-16 sm:px-5 sm:py-24" style="{{ memory_style($style) }}">
    <div class="mx-auto max-w-5xl">
      @if($section->title || $section->subtitle)
        <div class="mb-8 max-w-2xl sm:mb-10">
          <p class="font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">Hành trình của</p>
          @if($section->title)
            <h2 class="memory-heading mt-1 text-balance text-[2.05rem] font-semibold leading-[1.05] text-[#32131c] sm:text-5xl">{{ $section->title }}</h2>
          @endif
          @if($section->subtitle)
            <p class="mt-3 text-sm leading-6 text-[#7b6258] sm:text-base sm:leading-7">{{ $section->subtitle }}</p>
          @endif
        </div>
      @endif

      <div class="relative pl-7 before:absolute before:left-2 before:top-2 before:h-[calc(100%_-_1rem)] before:w-px before:bg-[#efc9d3] sm:pl-9">
        @foreach($items as $item)
          @php
            $media = $mediaById->get($item['media_id'] ?? null);
          @endphp

          <article class="relative mb-8 last:mb-0">
            <span aria-hidden="true" class="absolute -left-[1.72rem] top-4 z-10 h-4 w-4 rounded-full border-[4px] border-[#fffaf5] bg-[#d84d80] shadow-[0_0_0_7px_rgba(216,77,128,0.12)] sm:-left-[2.22rem]"></span>

            <div class="overflow-hidden rounded-[1.8rem] bg-white p-2 shadow-[0_18px_58px_rgba(74,39,32,0.1)] ring-1 ring-[#f0d3d9]">
              @if($media)
                <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($item['title'] ?? $post->title) }}" loading="lazy" class="h-48 w-full rounded-[1.35rem] object-cover sm:h-72">
              @endif

              <div class="px-3 pb-4 pt-3 sm:px-4 sm:pb-5">
                @if(! empty($item['time']))
                  <p class="text-[0.66rem] font-semibold uppercase tracking-[0.22em] text-[#c05779]">{{ $item['time'] }}</p>
                @endif
                @if(! empty($item['title']))
                  <h3 class="memory-heading mt-1 text-balance text-2xl font-semibold leading-tight text-[#32131c] sm:text-3xl">{{ $item['title'] }}</h3>
                @endif
                @if(! empty($item['body']))
                  <p class="mt-2 text-sm leading-6 text-[#6b554d]">{{ $item['body'] }}</p>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
