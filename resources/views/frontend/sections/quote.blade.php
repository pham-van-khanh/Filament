@if(! empty($data['quote']))
  <section class="bg-[#f8f0ea] px-4 py-10 sm:px-5 sm:py-16">
    <div class="mx-auto max-w-5xl">
      <figure class="relative overflow-hidden rounded-[2rem] border border-[#ead7ca] bg-[#fffaf5] px-5 py-8 shadow-[0_24px_90px_rgba(74,39,32,0.1)] sm:px-9 sm:py-12">
        <span aria-hidden="true" class="absolute -right-4 -top-10 font-['Playfair_Display'] text-[9rem] leading-none text-[#ead7ca]/70 sm:text-[12rem]">“</span>
        <blockquote class="memory-heading relative max-w-3xl text-balance text-3xl font-semibold leading-tight text-[#32131c] sm:text-5xl">
          {{ $data['quote'] }}
        </blockquote>

        @if(! empty($data['author']))
          <figcaption class="relative mt-6 flex items-center gap-3 text-sm font-semibold text-[#8b4b42]">
            <span aria-hidden="true" class="h-px w-10 bg-[#c59b61]"></span>
            {{ $data['author'] }}
          </figcaption>
        @endif
      </figure>
    </div>
  </section>
@endif
