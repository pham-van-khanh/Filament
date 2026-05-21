@php
  $url = $data['url'] ?? '';
  if (str_contains($url, 'youtube.com/watch?v=')) {
      $url = str_replace('watch?v=', 'embed/', $url);
  }
@endphp

<section class="memory-section" style="{{ memory_style($style) }}">
  <figure class="memory-container">
    <div class="aspect-video overflow-hidden rounded-[var(--memory-image-radius)] bg-black">
      <iframe src="{{ $url }}" title="{{ $data['caption'] ?? $post->title }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
    </div>
    @if(! empty($data['caption']))
      <figcaption class="mt-4 text-center text-sm text-[var(--memory-muted)]">{{ $data['caption'] }}</figcaption>
    @endif
  </figure>
</section>

