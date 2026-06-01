@php
  $media = $mediaById->get($data['media_id'] ?? null);
@endphp

@if($media)
  <section class="bg-[#fffaf5] px-4 py-8 sm:px-5 sm:py-12" style="{{ memory_style($style) }}">
    <figure class="mx-auto max-w-6xl">
      <div class="overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_24px_90px_rgba(74,39,32,0.12)]">
        <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($data['caption'] ?? $post->title) }}" loading="lazy" class="max-h-[86svh] w-full object-cover">
      </div>

      @if(! empty($data['caption']))
        <figcaption class="mx-auto mt-4 max-w-2xl px-2 text-center text-sm font-medium leading-6 text-[#7b6258]">
          {{ $data['caption'] }}
        </figcaption>
      @endif
    </figure>
  </section>
@endif
