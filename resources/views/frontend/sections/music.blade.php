@php
  $enabled = $data['enabled'] ?? true;
  $loop = $data['loop'] ?? true;

  $title = $data['title'] ?? $post->music_title ?? 'Soundtrack của kỷ niệm';
  $artist = $data['artist'] ?? $post->music_artist ?? null;
  $src = $data['src'] ?? $data['url'] ?? $post->music_url ?? null;
  $note = $data['note'] ?? $data['caption'] ?? null;
  $sectionStyle = collect($style ?? [])
    ->map(fn ($value, $key) => "{$key}: {$value}")
    ->implode('; ');
@endphp

@if($enabled && filled($src))
  <section
    class="memory-music-section memory-soundtrack-section memory-section-frame relative overflow-hidden px-4 py-12 sm:px-5 sm:py-18"
    aria-labelledby="music-section-{{ $section->id }}"
    @if($sectionStyle) style="{{ $sectionStyle }}" @endif
  >
    @if($post->coverMedia)
      <img
        src="{{ $post->coverMedia->display_url }}"
        alt=""
        aria-hidden="true"
        loading="lazy"
        width="{{ $post->coverMedia->width ?: 1600 }}"
        height="{{ $post->coverMedia->height ?: 1000 }}"
        class="memory-soundtrack-section__backdrop"
      >
    @endif

    <div
      x-data="{ playing: false, toggle() { const audio = this.$refs.audio; if (! audio) return; audio.paused ? audio.play().catch(() => {}) : audio.pause(); } }"
      class="relative mx-auto grid max-w-6xl gap-5 lg:grid-cols-[minmax(0,0.72fr)_minmax(320px,0.5fr)] lg:items-end"
    >
      <div class="min-w-0">
        <p class="font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">Bản nhạc ở lại</p>
        <h2 id="music-section-{{ $section->id }}" class="memory-heading mt-2 max-w-3xl text-balance text-4xl font-semibold leading-tight text-[#32131c] sm:text-5xl">
          {{ $title }}
        </h2>
        @if($artist)
          <p class="mt-3 truncate text-sm font-semibold text-[#8b4b42] sm:text-base">{{ $artist }}</p>
        @endif
        @if($note)
          <p class="mt-4 max-w-xl text-sm leading-6 text-[#7b6258]">{{ $note }}</p>
        @endif
      </div>

      <div class="memory-soundtrack-card" x-bind:class="playing ? 'is-playing' : ''">
        <div class="memory-soundtrack-card__disc" aria-hidden="true">
          <span></span>
        </div>

        <div class="min-w-0 flex-1">
          <p class="text-xs font-semibold text-[#9c6a5f]">Soundtrack</p>
          <p class="mt-1 truncate text-base font-semibold text-[#32131c]">{{ $title }}</p>
          @if($artist)
            <p class="mt-0.5 truncate text-sm text-[#7b6258]">{{ $artist }}</p>
          @endif
        </div>

        <button
          type="button"
          x-on:click="toggle()"
          x-bind:aria-pressed="playing ? 'true' : 'false'"
          class="memory-soundtrack-card__button"
          aria-label="Bật hoặc tạm dừng nhạc"
        >
          <x-ui-icon x-show="!playing" name="music" class="h-5 w-5" />
          <span x-cloak x-show="playing" class="flex h-5 items-end gap-0.5" aria-hidden="true">
            <i class="block h-2 w-1 animate-pulse rounded bg-current"></i>
            <i class="block h-5 w-1 animate-pulse rounded bg-current [animation-delay:120ms]"></i>
            <i class="block h-3.5 w-1 animate-pulse rounded bg-current [animation-delay:240ms]"></i>
          </span>
          <span class="ml-2 hidden text-sm font-semibold sm:inline" x-text="playing ? 'Tạm dừng' : 'Phát nhạc'"></span>
        </button>
      </div>

      <audio
        x-ref="audio"
        src="{{ $src }}"
        preload="metadata"
        @if($loop) loop @endif
        x-on:play="playing = true"
        x-on:pause="playing = false"
        x-on:ended="playing = false"
      ></audio>
    </div>
  </section>
@endif
