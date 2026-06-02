@extends($templateView)
@inject('renderer', 'App\Services\Rendering\SectionRenderer')

@section('title', $post->seo_title ?: $post->title)
@section('description', $post->seo_description ?: $post->excerpt)

@section('meta')
  <meta property="og:title" content="{{ $post->seo_title ?: $post->title }}">
  <meta property="og:description" content="{{ $post->seo_description ?: $post->excerpt }}">
  @if($post->ogMedia || $post->coverMedia)
    <meta property="og:image" content="{{ ($post->ogMedia ?: $post->coverMedia)->display_url }}">
  @endif
@endsection

@section('post_content')
  @php
    $visibleSections = collect($post->visibleSections);
    $musicSections = $visibleSections->filter(fn ($section) => $section->component_type === 'music' || $section->type === 'music');
    $storySections = $visibleSections->reject(fn ($section) => $section->component_type === 'music' || $section->type === 'music');
    $musicSection = $musicSections->first(fn ($section) => filled($section->url));
    $musicUrl = $post->music_url ?: $musicSection?->url;
    $hasPostMusic = filled($musicUrl);
    $musicTitle = $post->music_title ?: $musicSection?->headline ?: 'Soundtrack của kỷ niệm';
    $musicArtist = $post->music_artist ?: $musicSection?->subtitle;
    $publishedLine = $post->published_at?->format('d/m/Y');
  @endphp

  @isset($isPreview)
    <div class="fixed bottom-24 left-1/2 z-50 -translate-x-1/2 rounded-full bg-[#f1c36d] px-4 py-2 text-sm font-semibold text-[#32131c] shadow-lg">
      Preview mode
    </div>
  @endisset

  <div
    class="memory-public-detail"
    @if($hasPostMusic)
      x-data="{ playing: false, startMemoryMusic() { const audio = this.$refs.memoryAudio; if (! audio) return; audio.volume = 0.82; audio.play().catch(() => {}); }, toggleMemoryMusic() { const audio = this.$refs.memoryAudio; if (! audio) return; audio.paused ? audio.play().catch(() => {}) : audio.pause(); } }"
      x-init="$nextTick(() => startMemoryMusic())"
    @endif
  >
    @if($hasPostMusic)
      <div class="memory-floating-player" aria-label="Trình phát nhạc của kỷ niệm">
        <button
          type="button"
          x-on:click="toggleMemoryMusic()"
          x-bind:aria-pressed="playing ? 'true' : 'false'"
          x-bind:class="playing ? 'is-playing' : ''"
          class="memory-floating-player__button"
          aria-label="Bật hoặc tạm dừng nhạc"
          title="{{ $musicTitle }}"
        >
          <x-ui-icon x-show="!playing" name="music" class="h-5 w-5" />
          <span x-cloak x-show="playing" class="flex h-5 items-end gap-0.5" aria-hidden="true">
            <i class="block h-2 w-1 animate-pulse rounded bg-current"></i>
            <i class="block h-5 w-1 animate-pulse rounded bg-current [animation-delay:120ms]"></i>
            <i class="block h-3.5 w-1 animate-pulse rounded bg-current [animation-delay:240ms]"></i>
          </span>
        </button>
      </div>

      <audio
        x-ref="memoryAudio"
        src="{{ $musicUrl }}"
        autoplay
        loop
        preload="auto"
        x-on:play="playing = true"
        x-on:pause="playing = false"
        x-on:ended="playing = false"
      ></audio>
    @endif

    <div class="memory-story-flow">
      @foreach($storySections as $section)
        {!! $renderer->render($section, $post, $mediaById) !!}
      @endforeach
    </div>

    <section class="relative overflow-hidden bg-[#fff8f3] px-4 py-10 sm:px-5 sm:py-14">
      <div class="pointer-events-none absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#fffaf5] to-transparent"></div>

      <div class="relative mx-auto grid max-w-6xl gap-5 lg:grid-cols-[minmax(0,0.82fr)_minmax(360px,1fr)] lg:items-end">
        <div class="max-w-2xl">
          <p class="font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">Gửi một dấu nhỏ</p>
          <h2 class="memory-heading mt-2 text-balance text-4xl font-semibold leading-tight text-[#32131c] sm:text-5xl">
            Kỷ niệm này còn mở cho những cảm xúc rất riêng.
          </h2>
          <p class="mt-4 max-w-xl text-sm leading-6 text-[#7b6258]">
            {{ $post->reactions_count }} cảm xúc · {{ $post->approved_comments_count }} bình luận
            @if($publishedLine)
              · {{ $publishedLine }}
            @endif
          </p>
        </div>

        <div class="rounded-[2rem] border border-[#ead7ca] bg-white/82 p-3 shadow-[0_24px_90px_rgba(74,39,32,0.08)] backdrop-blur sm:p-4">
          <div class="grid grid-cols-3 gap-2">
            @foreach(['like' => ['label' => 'Thích', 'icon' => 'thumb-up'], 'love' => ['label' => 'Yêu', 'icon' => 'heart'], 'wow' => ['label' => 'Wow', 'icon' => 'sparkle']] as $type => $reaction)
              <form method="POST" action="{{ route('memories.reactions.store', $post->slug) }}">
                @csrf
                <input type="hidden" name="reaction_type" value="{{ $type }}">
                <button type="submit" class="group inline-flex min-h-[4.5rem] w-full flex-col items-center justify-center gap-2 rounded-[1.35rem] bg-[#fff8f3] px-2 py-3 text-sm font-semibold text-[#6f3b32] ring-1 ring-[#ead7ca] transition duration-200 hover:-translate-y-0.5 hover:bg-white hover:text-[#b83265] hover:shadow-[0_14px_36px_rgba(184,50,101,0.12)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#e6a4ba] active:translate-y-0">
                  <x-ui-icon :name="$reaction['icon']" class="h-5 w-5 transition group-hover:scale-110" />
                  {{ $reaction['label'] }}
                </button>
              </form>
            @endforeach
          </div>

          <button
            type="button"
            aria-label="Chia sẻ kỷ niệm"
            x-data
            x-on:click="navigator.share ? navigator.share({title: @js($post->title), url: location.href}) : navigator.clipboard?.writeText(location.href)"
            class="mt-2 inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-[1.35rem] border border-[#d9a2a9] bg-[#812744] px-4 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-[#6f203a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#e6a4ba] active:translate-y-px"
          >
            <x-ui-icon name="share" class="h-5 w-5" />
            Chia sẻ kỷ niệm
          </button>
        </div>
      </div>
    </section>

    <section class="bg-[#fff8f3] px-4 pb-12 pt-4 sm:px-5 sm:pb-16">
      <div class="mx-auto grid max-w-6xl gap-5 lg:grid-cols-[minmax(0,0.92fr)_minmax(360px,0.78fr)]">
        <div class="rounded-[2rem] border border-[#ead7ca] bg-white p-4 shadow-[0_24px_80px_rgba(74,39,32,0.07)] sm:p-6">
          <div class="mb-5 flex items-end justify-between gap-4">
            <div>
              <p class="font-['Dancing_Script'] text-2xl leading-none text-[#c05779]">Lời nhắn còn lại</p>
              <h2 class="memory-heading mt-1 text-3xl font-semibold leading-tight text-[#32131c] sm:text-4xl">Bình luận</h2>
            </div>
            <span class="rounded-full bg-[#f8e4dd] px-3 py-1.5 text-xs font-semibold text-[#8b4b42]">{{ $post->approved_comments_count }}</span>
          </div>

          <div class="space-y-4">
            @forelse($post->approvedComments as $comment)
              <article class="grid grid-cols-[2.5rem_1fr] gap-3">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-[#f3d5c5] text-sm font-bold text-[#812744]">
                  {{ str($comment->name)->substr(0, 2)->upper() }}
                </div>
                <div class="rounded-[1.5rem] rounded-tl-sm bg-[#fff8f3] px-4 py-3 ring-1 ring-[#f0dfd5]">
                  <p class="font-semibold text-[#32131c]">{{ $comment->name }}</p>
                  <p class="mt-1 text-sm leading-6 text-[#6b554d]">{{ $comment->content }}</p>
                </div>
              </article>
            @empty
              <p class="rounded-[1.5rem] border border-dashed border-[#e6cfc4] bg-[#fff8f3] px-5 py-6 text-sm leading-6 text-[#7b6258]">
                Chưa có bình luận. Hãy để lại một lời nhắn nhỏ cho kỷ niệm này.
              </p>
            @endforelse
          </div>

          <form method="POST" action="{{ route('memories.comments.store', $post->slug) }}" class="mt-6 border-t border-[#ead7ca] pt-5">
            @csrf
            <div class="grid gap-3 sm:grid-cols-2">
              <label class="grid gap-1.5 text-xs font-semibold text-[#7b6258]">
                Tên hiển thị
                <input name="name" required autocomplete="name" placeholder="Tên hiển thị…" class="min-h-12 rounded-2xl border border-[#ead7ca] bg-[#fffaf5] px-4 py-3 text-sm font-medium text-[#32131c] outline-none placeholder:text-[#ab9389] focus:border-[#d84d80] focus-visible:ring-2 focus-visible:ring-[#f4cad8]">
              </label>
              <label class="grid gap-1.5 text-xs font-semibold text-[#7b6258]">
                Email tùy chọn
                <input name="email" type="email" autocomplete="email" spellcheck="false" placeholder="Email tùy chọn…" class="min-h-12 rounded-2xl border border-[#ead7ca] bg-[#fffaf5] px-4 py-3 text-sm font-medium text-[#32131c] outline-none placeholder:text-[#ab9389] focus:border-[#d84d80] focus-visible:ring-2 focus-visible:ring-[#f4cad8]">
              </label>
            </div>
            <label class="mt-3 grid gap-1.5 text-xs font-semibold text-[#7b6258]">
              Cảm nhận
              <textarea name="content" required rows="4" placeholder="Viết cảm nhận hoặc lời nhắn của bạn về kỷ niệm này..." class="w-full rounded-2xl border border-[#ead7ca] bg-[#fffaf5] px-4 py-3 text-sm font-medium leading-6 text-[#32131c] outline-none placeholder:text-[#ab9389] focus:border-[#d84d80] focus-visible:ring-2 focus-visible:ring-[#f4cad8]"></textarea>
            </label>
            <button type="submit" class="mt-3 inline-flex min-h-12 items-center justify-center rounded-full bg-[#812744] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#6f203a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#e6a4ba] active:translate-y-px">
              Gửi bình luận
            </button>
          </form>
        </div>

        <aside class="rounded-[2rem] border border-[#eed2dc] bg-[#fbe8ef] p-4 shadow-[0_24px_80px_rgba(129,39,68,0.08)] sm:p-6 lg:sticky lg:top-6 lg:self-start">
          <p class="font-['Dancing_Script'] text-2xl leading-none text-[#b83265]">Chỉ gửi riêng</p>
          <h3 class="memory-heading mt-1 text-3xl font-semibold leading-tight text-[#812744]">Một lời nhắn không cần công khai.</h3>
          <p class="mt-3 text-sm leading-6 text-[#7b4d5d]">Tin nhắn riêng chỉ được gửi tới admin của kỷ niệm này.</p>

          <form method="POST" action="{{ route('memories.messages.store', $post->slug) }}" class="mt-5">
            @csrf
            <div class="grid gap-3">
              <input name="name" required autocomplete="name" aria-label="Tên của bạn" placeholder="Tên của bạn…" class="min-h-12 rounded-2xl border border-white/90 bg-white/82 px-4 py-3 text-sm font-medium text-[#32131c] outline-none placeholder:text-[#9d7581] focus:border-[#d84d80] focus-visible:ring-2 focus-visible:ring-white">
              <input name="email" type="email" autocomplete="email" spellcheck="false" aria-label="Email nếu muốn" placeholder="Email nếu muốn…" class="min-h-12 rounded-2xl border border-white/90 bg-white/82 px-4 py-3 text-sm font-medium text-[#32131c] outline-none placeholder:text-[#9d7581] focus:border-[#d84d80] focus-visible:ring-2 focus-visible:ring-white">
              <textarea name="message" required rows="5" aria-label="Tin nhắn riêng" placeholder="Tin nhắn này chỉ admin đọc…" class="w-full rounded-2xl border border-white/90 bg-white/82 px-4 py-3 text-sm font-medium leading-6 text-[#32131c] outline-none placeholder:text-[#9d7581] focus:border-[#d84d80] focus-visible:ring-2 focus-visible:ring-white"></textarea>
            </div>
            <button type="submit" class="mt-3 inline-flex min-h-12 w-full items-center justify-center rounded-full border border-[#812744]/20 bg-white px-5 py-3 text-sm font-semibold text-[#812744] transition hover:border-[#812744]/40 hover:bg-[#fff8fb] focus:outline-none focus-visible:ring-2 focus-visible:ring-white active:translate-y-px">
              Gửi riêng
            </button>
          </form>
        </aside>
      </div>
    </section>

    @if($related->isNotEmpty())
      <section class="bg-[#f7eee8] px-4 py-12 sm:px-5 sm:py-16">
        <div class="mx-auto max-w-6xl">
          <div class="mb-6 max-w-2xl">
            <p class="font-['Dancing_Script'] text-3xl leading-none text-[#c05779]">Đi tiếp một chút</p>
            <div class="mt-2 flex items-end justify-between gap-4">
              <h2 class="memory-heading text-balance text-4xl font-semibold leading-tight text-[#32131c] sm:text-5xl">Kỷ niệm liên quan</h2>
              <a href="{{ route('memories.index') }}" class="hidden shrink-0 rounded-full border border-[#d9a2a9] px-4 py-2 text-sm font-semibold text-[#812744] transition hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[#e6a4ba] sm:inline-flex">Xem tất cả</a>
            </div>
          </div>

          <div class="-mx-4 flex snap-x gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] sm:mx-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:overflow-visible sm:px-0 sm:pb-0">
            @foreach($related as $item)
              <article class="w-[76%] shrink-0 snap-center sm:w-auto">
                <x-memory-card :post="$item" />
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

  </div>
@endsection
