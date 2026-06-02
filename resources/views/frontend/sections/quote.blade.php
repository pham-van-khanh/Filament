@if(! empty($data['quote']))
  <section class="memory-section-frame bg-[#f8f0ea] px-4 py-12 sm:px-5 sm:py-20">
    <div class="mx-auto max-w-5xl">
      <figure class="relative px-1 py-4 sm:px-6 sm:py-8">
        <span aria-hidden="true" class="absolute -left-1 -top-8 font-['Playfair_Display'] text-[8rem] leading-none text-[#e4c9bb] sm:-left-4 sm:text-[13rem]">&ldquo;</span>
        <blockquote class="memory-heading relative max-w-4xl text-balance text-3xl font-semibold leading-tight text-[#32131c] sm:text-6xl">
          {{ $data['quote'] }}
        </blockquote>

        @if(! empty($data['author']))
          <figcaption class="relative mt-7 flex items-center gap-3 text-sm font-semibold text-[#8b4b42]">
            <span aria-hidden="true" class="h-px w-12 bg-[#c59b61]"></span>
            {{ $data['author'] }}
          </figcaption>
        @endif
      </figure>
    </div>
  </section>
@endif
