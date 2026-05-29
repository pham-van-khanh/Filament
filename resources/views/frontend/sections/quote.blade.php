<section class="bg-white px-5 pb-10 pt-1">
  <div class="mx-auto max-w-[760px] rounded-2xl bg-[#f3f7fb] p-6 shadow-sm ring-1 ring-[#d9e5f1]">
    <blockquote class="memory-heading text-lg font-semibold leading-8 text-neutral-900 sm:text-xl sm:leading-9">
      "{{ $data['quote'] ?? '' }}"
    </blockquote>
    @if(! empty($data['author']))
      <p class="mt-5 flex items-center gap-2 text-sm font-semibold text-[#285b86]">
        <span aria-hidden="true" class="h-px w-4 bg-[#75a3c9]"></span>
        {{ $data['author'] }}
      </p>
    @endif
  </div>
</section>
