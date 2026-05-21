@php
  $ids = collect($data['media_ids'] ?? []);
  $more = (int) ($data['more_count'] ?? 0);
@endphp

<section class="bg-white px-5 pb-8">
  <div class="mx-auto max-w-[760px]">
    <h2 class="mb-4 text-xl font-semibold text-neutral-950">{{ $section->title ?: 'Tat ca anh' }}</h2>
    <div class="grid h-[300px] grid-cols-2 grid-rows-2 gap-1 overflow-hidden rounded-2xl md:h-[420px]" data-lightbox>
      @foreach($ids->take(3) as $id)
        @php($media = $mediaById->get($id))
        @if($media)
          <a href="{{ $media->display_url }}" data-pswp-width="{{ $media->width ?: 1600 }}" data-pswp-height="{{ $media->height ?: 1000 }}" class="{{ $loop->first ? 'row-span-2' : '' }} group relative overflow-hidden bg-[#e7f2fb]">
            <img src="{{ $media->display_url }}" alt="{{ $media->alt }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
            @if($loop->last && $more > 0)
              <span class="absolute inset-0 grid place-items-center bg-black/42 text-2xl font-semibold text-white">+{{ $more }}</span>
            @endif
          </a>
        @endif
      @endforeach
    </div>
  </div>
</section>
