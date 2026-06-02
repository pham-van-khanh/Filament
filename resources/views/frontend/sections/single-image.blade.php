@php
  $media = $mediaById->get($data['media_id'] ?? null);
  $caption = $data['caption'] ?? null;
@endphp

@if($media)
  <section class="memory-section-frame bg-[#fffaf5] px-4 py-16 sm:px-5 sm:py-24" style="{{ memory_style($style) }}">
    <figure class="mx-auto max-w-6xl">
      @if($section->title || $section->subtitle)
        <div class="mb-6 max-w-2xl">
          <p class="text-[0.68rem] font-semibold uppercase tracking-[0.28em] text-[#c05779]">Khoảnh khắc yêu thích</p>
          @if($section->title)
            <h2 class="memory-heading mt-2 max-w-[12ch] text-balance text-[2.05rem] font-semibold leading-[1.05] text-[#32131c] sm:max-w-2xl sm:text-5xl">
              {{ $section->title }}
            </h2>
          @endif
          @if($section->subtitle)
            <p class="mt-3 text-sm leading-6 text-[#7b6258] sm:text-base sm:leading-7">{{ $section->subtitle }}</p>
          @endif
        </div>
      @endif

      <div class="group relative overflow-hidden rounded-[2rem] bg-white p-2 shadow-[0_24px_86px_rgba(74,39,32,0.11)] ring-1 ring-[#f0d3d9]">
        <div class="overflow-hidden rounded-[1.5rem] bg-[#eadbd1]">
          <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($caption ?? $section->title ?? $post->title) }}" loading="lazy" class="h-[260px] w-full object-cover transition duration-700 group-hover:scale-[1.02] sm:h-[560px]">
        </div>
      </div>

      @if($caption)
        <figcaption class="memory-caption mx-auto mt-5 max-w-[19rem] px-2 text-center sm:max-w-2xl">
          {{ $caption }}
        </figcaption>
      @endif
    </figure>
  </section>
@endif
