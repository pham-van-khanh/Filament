@if(! empty($data['html']))
  <section class="memory-section-frame bg-[#fffaf5] px-4 py-11 sm:px-5 sm:py-20" style="{{ memory_style($style) }}">
    <div class="mx-auto max-w-[860px]">
      @if($section->title)
        <p class="mb-4 font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">{{ $section->title }}</p>
      @endif

      <div class="memory-prose !w-full !max-w-none border-l border-[#d9b9aa] pl-5 sm:pl-7">
        {!! clean_html($data['html']) !!}
      </div>
    </div>
  </section>
@endif
