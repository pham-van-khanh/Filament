@php
  $enabled = $data['enabled'] ?? true;
  $autoplay = $data['autoplay'] ?? true;
  $loop = $data['loop'] ?? true;

  $title = $data['title'] ?? 'Our Song';
  $artist = $data['artist'] ?? 'chuaminh.vn';
  $src = $data['src'] ?? null;
  $note = $data['note'] ?? null;
@endphp

@if($enabled && $src)
  <section class="memory-music-section px-4 pb-8">
    <div class="mx-auto max-w-md rounded-3xl border border-white/40 bg-white/80 p-4 shadow-xl backdrop-blur">
      <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-pink-100 text-2xl">
          🎵
        </div>

        <div class="min-w-0 flex-1">
          <p class="truncate text-sm font-semibold text-gray-900">
            {{ $title }}
          </p>

          <p class="truncate text-xs text-gray-500">
            {{ $artist }}
          </p>

          @if($note)
            <p class="mt-1 text-xs text-gray-400">
              {{ $note }}
            </p>
          @endif
        </div>

        <button
          type="button"
          class="music-toggle rounded-full bg-gray-900 px-4 py-2 text-xs font-semibold text-white"
        >
          Play
        </button>
      </div>

      <audio
        class="memory-audio mt-3 w-full"
        src="{{ $src }}"
        controls
        @if($autoplay) autoplay @endif
        @if($loop) loop @endif
      ></audio>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const audio = document.querySelector('.memory-audio');
      const button = document.querySelector('.music-toggle');

      if (!audio || !button) return;

      audio.play().catch(() => {
        button.textContent = 'Play';
      });

      button.addEventListener('click', function () {
        if (audio.paused) {
          audio.play();
          button.textContent = 'Pause';
        } else {
          audio.pause();
          button.textContent = 'Play';
        }
      });

      audio.addEventListener('play', function () {
        button.textContent = 'Pause';
      });

      audio.addEventListener('pause', function () {
        button.textContent = 'Play';
      });
    });
  </script>
@endif
