@php
  $items = collect($data['items'] ?? []);
@endphp

@if($items->isNotEmpty())
  <section class="bg-[#fffaf5] px-4 py-10 sm:px-5 sm:py-16" style="{{ memory_style($style) }}">
    <div class="mx-auto max-w-5xl">
      @if($section->title || $section->subtitle)
        <div class="mb-7 max-w-2xl">
          @if($section->title)
            <h2 class="memory-heading text-3xl font-semibold leading-tight text-[#32131c] sm:text-4xl">{{ $section->title }}</h2>
          @endif
          @if($section->subtitle)
            <p class="mt-2 text-sm leading-6 text-[#8d7168]">{{ $section->subtitle }}</p>
          @endif
        </div>
      @endif

      <div class="relative space-y-5 before:absolute before:left-4 before:top-2 before:h-[calc(100%-1rem)] before:w-px before:bg-[#d9b9aa] sm:space-y-7 sm:before:left-1/2">
        @foreach($items as $item)
          @php
            $media = $mediaById->get($item['media_id'] ?? null);
          @endphp
          <article class="relative grid gap-4 pl-11 sm:grid-cols-2 sm:gap-8 sm:pl-0">
            <span aria-hidden="true" class="absolute left-[10px] top-4 z-10 h-3 w-3 rounded-full border-2 border-[#fffaf5] bg-[#d84d80] shadow-[0_0_0_6px_rgba(216,77,128,0.12)] sm:left-1/2 sm:-ml-1.5"></span>

            <div class="{{ $loop->odd ? 'sm:order-1 sm:pr-8' : 'sm:order-2 sm:pl-8' }}">
              @if($media)
                <div class="overflow-hidden rounded-[1.75rem] bg-[#eadbd1] shadow-[0_18px_70px_rgba(74,39,32,0.1)]">
                  <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($item['title'] ?? $post->title) }}" loading="lazy" class="aspect-[4/3] w-full object-cover">
                </div>
              @endif
            </div>

            <div class="{{ $loop->odd ? 'sm:order-2 sm:pl-8' : 'sm:order-1 sm:pr-8 sm:text-right' }}">
              <div class="rounded-[1.75rem] border border-[#ead7ca] bg-[#fffaf5] p-4 shadow-sm sm:p-5">
                @if(! empty($item['time']))
                  <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#b75b72]">{{ $item['time'] }}</p>
                @endif
                @if(! empty($item['title']))
                  <h3 class="memory-heading mt-2 text-2xl font-semibold leading-tight text-[#32131c]">{{ $item['title'] }}</h3>
                @endif
                @if(! empty($item['body']))
                  <p class="mt-2 line-clamp-3 text-sm leading-6 text-[#7b6258]">{{ $item['body'] }}</p>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
