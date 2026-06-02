@php
  $title = $data['title'] ?? null;
  $body = $data['body'] ?? null;
@endphp

@if($title || $body)
  <section class="memory-section-frame bg-[#fffaf5] px-4 py-14 sm:px-5 sm:py-20" style="{{ memory_style($style) }}">
    <div class="mx-auto max-w-3xl text-center">
      <p class="font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">Lời kết</p>
      @if($title)
        <h2 class="memory-heading mx-auto mt-3 text-balance text-4xl font-semibold leading-tight text-[#32131c] sm:text-6xl">
          {{ $title }}
        </h2>
      @endif
      @if($body)
        <div class="memory-prose mt-7 !w-full !max-w-none text-left">
          {!! clean_html($body) !!}
        </div>
      @endif
    </div>
  </section>
@endif
