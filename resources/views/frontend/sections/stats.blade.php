@php
  $items = collect($data['items'] ?? []);
@endphp

@if($items->isNotEmpty())
  <section class="memory-section-frame bg-[#fff8f3] px-4 py-8 sm:px-5 sm:py-12">
    <div class="mx-auto max-w-6xl">
      <div class="-mx-4 flex snap-x gap-3 overflow-x-auto px-4 pb-1 [scrollbar-width:none] sm:mx-0 sm:grid sm:grid-cols-4 sm:gap-0 sm:overflow-hidden sm:rounded-[2rem] sm:border sm:border-[#ead7ca] sm:bg-white/70 sm:px-0 sm:pb-0 sm:shadow-[0_18px_70px_rgba(74,39,32,0.06)]">
        @foreach($items as $item)
          <article class="min-w-[42%] snap-center rounded-[1.5rem] border border-[#ead7ca] bg-white/86 px-4 py-5 text-center shadow-sm sm:min-w-0 sm:rounded-none sm:border-0 sm:border-r sm:border-[#ead7ca] sm:bg-transparent sm:shadow-none sm:last:border-r-0">
            <div class="memory-heading text-4xl font-semibold leading-none text-[#812744]">{{ $item[0] ?? '' }}</div>
            <div class="mt-2 text-xs font-semibold uppercase tracking-[0.14em] text-[#8d7168]">{{ $item[1] ?? '' }}</div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
