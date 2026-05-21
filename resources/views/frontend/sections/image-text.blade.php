@php($media = $mediaById->get($data['media_id'] ?? null))

<section class="memory-section" style="{{ memory_style($style) }}">
  <div class="memory-container grid gap-10 md:grid-cols-2 md:items-center">
    @if($media)
      <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? '' }}" loading="lazy" class="memory-image h-full max-h-[720px] w-full object-cover">
    @endif
    <div class="memory-prose !w-full">
      {!! clean_html($data['body'] ?? '') !!}
    </div>
  </div>
</section>

