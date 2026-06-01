@php
  $enabled = $data['enabled'] ?? true;
  $loop = $data['loop'] ?? true;

  $title = $data['title'] ?: 'Our Song';
  $artist = $data['artist'] ?: 'chuaminh.vn';
  $src = $data['src'] ?? $data['url'] ?? null;
  $note = $data['note'] ?? $data['caption'] ?? null;
@endphp

@if($enabled && $src)
  <section class="memory-music-section relative overflow-hidden bg-[#241218] px-4 py-12 text-white sm:px-5 sm:py-16">
    @if($post->coverMedia)
      <img src="{{ $post->coverMedia->display_url }}" alt="" aria-hidden="true" loading="lazy" class="absolute inset-0 h-full w-full object-cover opacity-20 blur-sm scale-105">
    @endif
    <div class="absolute inset-0 bg-gradient-to-b from-[#241218]/76 via-[#241218]/92 to-[#14090d]"></div>

    <div
      x-data="{ playing: false, toggle() { const audio = this.$refs.audio; audio.paused ? audio.play() : audio.pause(); } }"
      class="relative mx-auto max-w-[820px]"
    >
      <div class="rounded-[2rem] border border-white/12 bg-white/10 p-5 shadow-[0_24px_90px_rgba(0,0,0,0.28)] backdrop-blur-xl sm:p-7">
        <div class="grid gap-5 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center">
          <div class="min-w-0">
            <p class="font-['Dancing_Script'] text-3xl text-[#f3bdca]">Soundtrack</p>
            <h2 class="memory-heading mt-2 truncate text-4xl font-semibold leading-tight text-white sm:text-5xl">{{ $title }}</h2>
            <p class="mt-2 truncate text-sm font-medium text-white/68">{{ $artist }}</p>
            @if($note)
              <p class="mt-3 max-w-xl text-sm leading-6 text-white/58">{{ $note }}</p>
            @endif
          </div>

          <button type="button" x-on:click="toggle()" class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full bg-white px-5 py-3 text-sm font-semibold text-[#812744] transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#f4cad8]">
            <x-ui-icon x-show="!playing" name="music" class="h-5 w-5" />
            <span x-show="playing" class="flex h-5 items-end gap-0.5" aria-hidden="true">
              <i class="block h-2 w-1 animate-pulse rounded bg-[#d84d80]"></i>
              <i class="block h-5 w-1 animate-pulse rounded bg-[#d84d80] [animation-delay:120ms]"></i>
              <i class="block h-3.5 w-1 animate-pulse rounded bg-[#d84d80] [animation-delay:240ms]"></i>
            </span>
            <span x-text="playing ? 'Tạm dừng' : 'Phát nhạc'"></span>
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
    </div>
  </section>
@endif
