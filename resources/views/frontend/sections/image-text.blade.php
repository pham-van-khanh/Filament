@php
  $media = $mediaById->get($data['media_id'] ?? null);
  $imageOrder = ($section->layout ?? '') === 'text_first' ? 'md:order-2' : 'md:order-1';
  $textOrder = ($section->layout ?? '') === 'text_first' ? 'md:order-1' : 'md:order-2';
@endphp

<section class="memory-section-frame bg-[#f8f0ea] px-4 py-12 sm:px-5 sm:py-20" style="{{ memory_style($style) }}">
  <div class="memory-container grid gap-7 md:grid-cols-[minmax(0,1.05fr)_minmax(300px,0.95fr)] md:items-center md:gap-12">
    @if($media)
      <figure class="relative {{ $imageOrder }}">
        <div class="group relative overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_28px_95px_rgba(74,39,32,0.14)] ring-1 ring-[#ead7ca]">
          <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($data['caption'] ?? $section->title ?? $post->title) }}" loading="lazy" class="aspect-[4/5] w-full object-cover transition duration-700 group-hover:scale-[1.025] sm:aspect-[5/4] md:aspect-[4/5]">
        </div>
        @if(! empty($data['caption']))
          <figcaption class="memory-caption mt-3 px-1">{{ $data['caption'] }}</figcaption>
        @endif
      </figure>
    @endif

    <div class="{{ $textOrder }}">
      @if($section->title)
        <p class="font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">{{ $section->title }}</p>
      @endif

      <div class="memory-prose !mx-0 mt-4 !w-full !max-w-none">
        {!! clean_html($data['body'] ?? '') !!}
      </div>
    </div>
  </div>
</section>
