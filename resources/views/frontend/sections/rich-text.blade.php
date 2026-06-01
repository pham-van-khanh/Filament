<section class="bg-[#fffaf5] px-4 py-10 sm:px-5 sm:py-14" style="{{ memory_style($style) }}">
  <div class="mx-auto max-w-[820px]">
    @if($section->title)
      <p class="mb-3 font-['Dancing_Script'] text-2xl text-[#c05779]">{{ $section->title }}</p>
    @endif

    <div class="memory-prose rounded-[2rem] border border-[#ead7ca] bg-white/76 p-5 shadow-[0_18px_70px_rgba(74,39,32,0.07)] sm:p-7">
      {!! clean_html($data['html'] ?? '') !!}
    </div>
  </div>
</section>
