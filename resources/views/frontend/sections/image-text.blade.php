@php
  $media = $mediaById->get($data['media_id'] ?? null);
  $imageOrder = ($section->layout ?? '') === 'text_first' ? 'md:order-2' : 'md:order-1';
  $textOrder = ($section->layout ?? '') === 'text_first' ? 'md:order-1' : 'md:order-2';
@endphp

<section class="bg-[#f8f0ea] px-4 py-10 sm:px-5 sm:py-16" style="{{ memory_style($style) }}">
  <div class="memory-container grid gap-6 md:grid-cols-[minmax(0,1.08fr)_minmax(320px,0.92fr)] md:items-center md:gap-10">
    @if($media)
      <figure class="relative {{ $imageOrder }}">
        <div class="absolute -bottom-4 -right-4 hidden h-28 w-28 rounded-full bg-[#c59b61]/18 md:block"></div>
        <div class="relative overflow-hidden rounded-[2rem] bg-[#eadbd1] shadow-[0_24px_90px_rgba(74,39,32,0.14)]">
          <img src="{{ $media->display_url }}" alt="{{ $media->alt ?: ($data['caption'] ?? $section->title ?? $post->title) }}" loading="lazy" class="aspect-[4/5] w-full object-cover sm:aspect-[5/4] md:aspect-[4/5]">
        </div>
        @if(! empty($data['caption']))
          <figcaption class="mt-3 px-2 text-sm font-medium leading-6 text-[#8d7168]">{{ $data['caption'] }}</figcaption>
        @endif
      </figure>
    @endif

    <div class="{{ $textOrder }} rounded-[2rem] border border-[#ead7ca] bg-[#fffaf5]/88 p-5 shadow-[0_18px_70px_rgba(74,39,32,0.08)] backdrop-blur md:p-7">
      @if($section->title)
        <p class="font-['Dancing_Script'] text-2xl text-[#c05779]">{{ $section->title }}</p>
      @endif

      <div class="memory-prose !mx-0 !w-full !max-w-none">
        {!! clean_html($data['body'] ?? '') !!}
      </div>
    </div>
  </div>
</section>
