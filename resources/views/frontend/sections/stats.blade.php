@php
  $items = collect($data['items'] ?? []);
@endphp

@if($items->isNotEmpty())
  <section class="bg-[#fffaf5] px-4 py-8 sm:px-5 sm:py-12">
    <div class="mx-auto max-w-5xl overflow-hidden rounded-[2rem] border border-[#ead7ca] bg-white/78 shadow-[0_18px_70px_rgba(74,39,32,0.07)]">
      <div class="grid grid-cols-2 divide-x divide-y divide-[#ead7ca] sm:grid-cols-4 sm:divide-y-0">
        @foreach($items as $item)
          <div class="px-3 py-5 text-center">
            <div class="memory-heading text-3xl font-semibold leading-none text-[#812744]">{{ $item[0] ?? '' }}</div>
            <div class="mt-2 text-xs font-semibold uppercase tracking-[0.14em] text-[#8d7168]">{{ $item[1] ?? '' }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endif
