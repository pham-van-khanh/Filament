@php
  $media = $mediaById->get($data['media_id'] ?? null);
  $caption = $data['caption'] ?? null;
@endphp

@if($media)
  <section class="memory-section-frame bg-[#fffaf5] px-4 py-10 sm:px-5 sm:py-16" style="{{ memory_style($style) }}">
    <figure class="mx-auto max-w-6xl">
      @if($section->title || $section->subtitle)
        <div class="mb-5 max-w-2xl">
          @if($section->title)
            <h2 class="memory-heading text-balance text-3xl font-semibold leading-tight text-[#32131c] sm:text-5xl">
              {{ $section->title }}
            </h2>
          @endif
          @if($section->subtitle)
            <p class="mt-3 text-sm leading-6 text-[#7b6258] sm:text-base sm:leading-7">{{ $section->subtitle }}</p>
          @endif
        </div>
      @endif

      <div class="group relative overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_28px_95px_rgba(74,39,32,0.14)] ring-1 ring-[#ead7ca]">
        <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($caption ?? $section->title ?? $post->title) }}" loading="lazy" class="max-h-[88svh] w-full object-cover transition duration-700 group-hover:scale-[1.015]">
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-[#241218]/28 to-transparent"></div>
      </div>

      @if($caption)
        <figcaption class="memory-caption mx-auto mt-4 max-w-2xl px-2 text-center">
          {{ $caption }}
        </figcaption>
      @endif
    </figure>
  </section>
@endif
