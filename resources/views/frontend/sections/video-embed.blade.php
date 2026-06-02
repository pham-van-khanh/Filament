@php
  $video = $mediaById->get($data['media_id'] ?? null);
  $url = $data['url'] ?? '';
  $frameClass = match ($data['layout'] ?? null) {
      'square' => 'aspect-square max-w-[640px]',
      'vertical' => 'aspect-[9/16] max-w-[460px]',
      default => 'aspect-video max-w-5xl',
  };

  if (str_contains($url, 'youtube.com/watch?v=')) {
      $url = str_replace('watch?v=', 'embed/', $url);
  }
@endphp

@if($video || $url)
  <section class="memory-section-frame bg-[#f7eee8] px-4 py-11 sm:px-5 sm:py-20" style="{{ memory_style($style) }}">
    <figure class="mx-auto max-w-6xl">
      @if($section->title || $section->subtitle)
        <div class="mx-auto mb-5 max-w-3xl text-center sm:mb-7">
          @if($section->title)
            <h2 class="memory-heading text-balance text-3xl font-semibold leading-tight text-[#32131c] sm:text-5xl">{{ $section->title }}</h2>
          @endif
          @if($section->subtitle)
            <p class="mt-3 text-sm leading-6 text-[#7b6258] sm:text-base sm:leading-7">{{ $section->subtitle }}</p>
          @endif
        </div>
      @endif

      <div class="{{ $frameClass }} mx-auto overflow-hidden rounded-[2rem] bg-[#14090d] shadow-[0_28px_100px_rgba(20,9,13,0.25)] ring-1 ring-[#ead7ca]">
        @if($video)
          <video src="{{ $video->display_url }}" class="h-full w-full object-cover" controls playsinline preload="metadata"></video>
        @else
          <iframe src="{{ $url }}" title="{{ $data['caption'] ?? $section->title ?? $post->title }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
        @endif
      </div>
      @if(! empty($data['caption']))
        <figcaption class="memory-caption mx-auto mt-4 max-w-2xl text-center">{{ $data['caption'] }}</figcaption>
      @endif
    </figure>
  </section>
@endif
