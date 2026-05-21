@php($items = collect($data['items'] ?? []))

<section class="memory-section" style="{{ memory_style($style) }}">
  <div class="memory-container">
    @if($section->title)
      <h2 class="memory-heading mb-10 text-3xl font-semibold">{{ $section->title }}</h2>
    @endif
    <div class="space-y-6 border-l border-[var(--memory-accent)]/40 pl-6">
      @foreach($items as $item)
        @php($media = $mediaById->get($item['media_id'] ?? null))
        <article class="memory-card grid gap-5 p-5 md:grid-cols-[220px_1fr]">
          @if($media)
            <img src="{{ $media->display_url }}" alt="{{ $item['title'] ?? '' }}" loading="lazy" class="h-44 w-full rounded-xl object-cover">
          @endif
          <div>
            @if(! empty($item['time']))
              <p class="text-sm font-semibold text-[var(--memory-accent)]">{{ $item['time'] }}</p>
            @endif
            <h3 class="memory-heading mt-2 text-2xl font-semibold">{{ $item['title'] ?? '' }}</h3>
            @if(! empty($item['body']))
              <p class="mt-3 leading-7 text-[var(--memory-muted)]">{{ $item['body'] }}</p>
            @endif
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

