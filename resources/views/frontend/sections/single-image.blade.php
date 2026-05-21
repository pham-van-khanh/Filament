@php($media = $mediaById->get($data['media_id'] ?? null))

@if($media)
  <section class="memory-section" style="{{ memory_style($style) }}">
    <figure class="memory-container">
      <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $data['caption'] ?? '' }}" loading="lazy" class="memory-image mx-auto max-h-[82vh] w-full object-cover">
      @if(! empty($data['caption']))
        <figcaption class="mx-auto mt-4 max-w-2xl text-center text-sm text-[var(--memory-muted)]">{{ $data['caption'] }}</figcaption>
      @endif
    </figure>
  </section>
@endif

