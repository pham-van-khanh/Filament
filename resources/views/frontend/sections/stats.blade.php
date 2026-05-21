@php($items = collect($data['items'] ?? []))

<section class="bg-white">
  <div class="mx-auto grid max-w-[760px] grid-cols-4 divide-x divide-black/10 border-b border-black/10">
    @foreach($items as $item)
      <div class="px-2 py-3 text-center">
        <div class="text-2xl font-semibold text-neutral-950">{{ $item[0] ?? '' }}</div>
        <div class="text-xs font-medium text-neutral-500">{{ $item[1] ?? '' }}</div>
      </div>
    @endforeach
  </div>
</section>
