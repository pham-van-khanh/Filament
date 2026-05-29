@php($media = $mediaById->get($data['media_id'] ?? null))

@if($media)
  <section class="bg-white px-5 pb-10 pt-6" style="{{ memory_style($style) }}">
    <figure class="mx-auto max-w-[760px]">
      <img src="{{ $media->display_url }}" alt="{{ $media->alt ?? $data['caption'] ?? '' }}" loading="lazy" class="memory-image mx-auto max-h-[82vh] w-full object-cover">
      @if(! empty($data['caption']))
        <figcaption class="mx-auto mt-4 max-w-2xl text-center text-sm font-medium leading-6 text-neutral-600">{{ $data['caption'] }}</figcaption>
      @endif
    </figure>
  </section>
@endif

