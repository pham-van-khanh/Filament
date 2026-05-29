@php($items = collect($data['items'] ?? []))

@if($items->isNotEmpty())
<section class="bg-white px-5 pb-10 pt-6" style="{{ memory_style($style) }}">
  <div class="mx-auto max-w-[760px]">
    @if($section->title)
      <h2 class="memory-heading mb-6 text-2xl font-semibold text-neutral-950">{{ $section->title }}</h2>
    @endif
    <div class="space-y-4 border-l-2 border-[#cfe0ed] pl-5 sm:pl-7">
      @foreach($items as $item)
        @php($media = $mediaById->get($item['media_id'] ?? null))
        <article class="relative overflow-visible rounded-lg bg-[#f5f8fb] p-3.5 ring-1 ring-[#dfebf3] before:absolute before:-left-[29px] before:top-7 before:h-3.5 before:w-3.5 before:rounded-full before:border-2 before:border-white before:bg-[#d84d80] sm:p-4 sm:before:-left-[37px] md:grid md:grid-cols-[176px_1fr] md:gap-5">
          @if($media)
            <img src="{{ $media->display_url }}" alt="{{ $item['title'] ?? '' }}" loading="lazy" class="mb-3 aspect-[16/10] w-full rounded-lg object-cover md:mb-0 md:h-36">
          @endif
          <div>
            @if(! empty($item['time']))
              <p class="text-xs font-semibold uppercase text-[#285b86]">{{ $item['time'] }}</p>
            @endif
            <h3 class="memory-heading mt-1.5 text-xl font-semibold text-neutral-950">{{ $item['title'] ?? '' }}</h3>
            @if(! empty($item['body']))
              <p class="mt-2 text-sm leading-6 text-neutral-600">{{ $item['body'] }}</p>
            @endif
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

