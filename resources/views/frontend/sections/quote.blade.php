@if(! empty($data['quote']))
  <section class="memory-section-frame bg-[#f8f0ea] px-4 py-16 sm:px-5 sm:py-24">
    <div class="mx-auto max-w-5xl">
      <figure class="relative overflow-hidden rounded-[2rem] bg-[#fff5f7] p-7 shadow-[0_18px_70px_rgba(129,39,68,0.08)] ring-1 ring-[#f0d3d9] sm:p-10">
        <span aria-hidden="true" class="absolute -top-7 left-5 font-['Playfair_Display'] text-[8.5rem] leading-none text-[#efc9d3]/72 sm:text-[13rem]">&ldquo;</span>
        <blockquote class="memory-heading relative text-balance text-[1.85rem] font-semibold leading-[1.08] text-[#32131c] sm:text-6xl">
          {{ $data['quote'] }}
        </blockquote>

        @if(! empty($data['author']))
          <figcaption class="relative mt-7 flex items-center gap-3 text-[0.7rem] font-semibold uppercase tracking-[0.22em] text-[#b75b72]">
            <span aria-hidden="true" class="h-px w-10 bg-[#c59b61]"></span>
            {{ $data['author'] }}
          </figcaption>
        @endif
      </figure>
    </div>
  </section>
@endif
