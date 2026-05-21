<section class="bg-white px-5 pb-8">
  <div class="mx-auto max-w-[760px] rounded-2xl p-5" style="background: color-mix(in srgb, var(--memory-accent) 15%, white)">
    <blockquote class="memory-heading text-xl font-semibold leading-9 text-[var(--memory-text)]">
      "{{ $data['quote'] ?? '' }}"
    </blockquote>
    @if(! empty($data['author']))
      <p class="mt-4 text-sm font-semibold text-[var(--memory-accent)]">□ {{ $data['author'] }}</p>
    @endif
  </div>
</section>
