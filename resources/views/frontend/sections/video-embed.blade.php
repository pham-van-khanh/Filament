@php
  $video = $mediaById->get($data['media_id'] ?? null);
  $url = $data['url'] ?? '';
  $frameClass = match ($data['layout'] ?? null) {
      'square' => 'aspect-square max-w-[640px]',
      'vertical' => 'aspect-[9/16] max-w-[420px]',
      default => 'aspect-video',
  };

  if (str_contains($url, 'youtube.com/watch?v=')) {
      $url = str_replace('watch?v=', 'embed/', $url);
  }
@endphp

@if($video || $url)
  <section class="memory-section" style="{{ memory_style($style) }}">
    <figure class="memory-container">
      <div class="{{ $frameClass }} mx-auto overflow-hidden rounded-[var(--memory-image-radius)] bg-black">
        @if($video)
          <video src="{{ $video->display_url }}" class="h-full w-full object-cover" controls playsinline preload="metadata"></video>
        @else
          <iframe src="{{ $url }}" title="{{ $data['caption'] ?? $post->title }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
        @endif
      </div>
      @if(! empty($data['caption']))
        <figcaption class="mt-4 text-center text-sm text-[var(--memory-muted)]">{{ $data['caption'] }}</figcaption>
      @endif
    </figure>
  </section>
@endif
