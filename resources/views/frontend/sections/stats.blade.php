@php
  $items = collect($data['items'] ?? []);
  $count = $items->count();
  $isSlider = $count >= 5;
@endphp

@if($items->isNotEmpty())
  <section class="memory-section-frame relative z-20 -mt-10 bg-transparent px-4 pb-9 pt-0 sm:px-5 sm:pb-14">
    <div class="mx-auto max-w-6xl">
      <div @class([
        'memory-stats-strip',
        '-mx-4 flex snap-x gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] sm:-mx-5 sm:px-5' => $isSlider,
        'flex flex-wrap justify-center gap-3 sm:gap-4' => ! $isSlider,
      ])>
        @foreach($items as $item)
          <article @class([
            'memory-stat-card rounded-[1.75rem] border border-[#f0d3d9] bg-white/90 px-4 py-5 text-center shadow-[0_16px_44px_rgba(129,39,68,0.08)]',
            'min-w-[9.75rem] snap-center sm:min-w-[11rem]' => $isSlider,
            'w-[calc(50%_-_0.375rem)] max-w-[10.5rem] sm:w-[10.75rem]' => ! $isSlider,
          ])>
            <div class="memory-heading text-[2.45rem] font-semibold leading-none text-[#812744]">{{ $item[0] ?? '' }}</div>
            <div class="mt-2 text-[0.66rem] font-semibold uppercase tracking-[0.13em] text-[#9a7b72]">{{ $item[1] ?? '' }}</div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
