<section class="bg-[#fffaf5] px-4 py-12 sm:px-5 sm:py-16" style="{{ memory_style($style) }}">
  <div class="mx-auto max-w-3xl text-center">
    <p class="font-['Dancing_Script'] text-3xl text-[#c05779]">End note</p>
    <h2 class="memory-heading mx-auto mt-3 text-balance text-4xl font-semibold leading-tight text-[#32131c] sm:text-6xl">
      {{ $data['title'] ?? 'The end' }}
    </h2>
    @if(! empty($data['body']))
      <div class="memory-prose mt-7 rounded-[2rem] border border-[#ead7ca] bg-white/76 p-5 text-left shadow-[0_18px_70px_rgba(74,39,32,0.07)] sm:p-7">
        {!! clean_html($data['body']) !!}
      </div>
    @endif
  </div>
</section>
