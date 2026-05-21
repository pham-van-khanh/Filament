<section class="memory-section" style="{{ memory_style($style) }}">
  <div class="memory-container text-center">
    <p class="text-sm uppercase tracking-[0.3em] text-[var(--memory-muted)]">End note</p>
    <h2 class="memory-heading mx-auto mt-4 max-w-3xl text-4xl font-semibold">{{ $data['title'] ?? 'The end' }}</h2>
    @if(! empty($data['body']))
      <div class="memory-prose mt-8">
        {!! clean_html($data['body']) !!}
      </div>
    @endif
  </div>
</section>

