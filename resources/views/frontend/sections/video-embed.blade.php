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
  <section class="bg-[#f8f0ea] px-4 py-10 sm:px-5 sm:py-16" style="{{ memory_style($style) }}">
    <figure class="mx-auto max-w-6xl">
      <div class="{{ $frameClass }} mx-auto overflow-hidden rounded-[2rem] bg-[#14090d] shadow-[0_24px_90px_rgba(20,9,13,0.22)]">
        @if($video)
          <video src="{{ $video->display_url }}" class="h-full w-full object-cover" controls playsinline preload="metadata"></video>
        @else
          <iframe src="{{ $url }}" title="{{ $data['caption'] ?? $post->title }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
        @endif
      </div>
      @if(! empty($data['caption']))
        <figcaption class="mx-auto mt-4 max-w-2xl text-center text-sm font-medium leading-6 text-[#7b6258]">{{ $data['caption'] }}</figcaption>
      @endif
    </figure>
  </section>
@endif
