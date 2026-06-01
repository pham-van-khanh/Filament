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
    $musicUrl = $post->music_url;
    $musicEnabled = $post->music_enabled;
  @endphp

  @isset($isPreview)
    <div class="fixed bottom-24 left-1/2 z-50 -translate-x-1/2 rounded-full bg-[#f1c36d] px-4 py-2 text-sm font-semibold text-[#32131c] shadow-lg">
      Preview mode
    </div>
  @endisset

  @foreach($storySections as $section)
    {!! $renderer->render($section, $post, $mediaById) !!}
  @endforeach

  <section class="bg-[#f8f0ea] px-4 py-8 sm:px-5 sm:py-10">
    <div class="mx-auto max-w-[820px]">
      <div class="rounded-[2rem] border border-[#ead7ca] bg-[#fffaf5] p-3 shadow-[0_20px_70px_rgba(74,39,32,0.08)] sm:p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <div class="grid grid-cols-3 gap-2 sm:flex sm:flex-wrap">
            @foreach(['like' => ['label' => 'Thích', 'icon' => 'thumb-up'], 'love' => ['label' => 'Yêu', 'icon' => 'heart'], 'wow' => ['label' => 'Wow', 'icon' => 'sparkle']] as $type => $reaction)
              <form method="POST" action="{{ route('memories.reactions.store', $post->slug) }}">
                @csrf
                <input type="hidden" name="reaction_type" value="{{ $type }}">
                <button class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-2xl border border-[#ead7ca] bg-white px-3 py-3 text-sm font-semibold text-[#6f3b32] transition hover:-translate-y-0.5 hover:border-[#d84d80] hover:text-[#b83265] focus:outline-none focus:ring-2 focus:ring-[#e6a4ba] sm:w-auto sm:px-4">
                  <x-ui-icon :name="$reaction['icon']" class="h-4 w-4" />
                  {{ $reaction['label'] }}
                </button>
              </form>
            @endforeach
          </div>

          <button type="button" aria-label="Chia sẻ kỷ niệm" onclick="navigator.share?.({title: @js($post->title), url: location.href})" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-2xl border border-[#ead7ca] bg-white px-4 py-3 text-sm font-semibold text-[#6f3b32] transition hover:border-[#d84d80] hover:text-[#b83265] focus:outline-none focus:ring-2 focus:ring-[#e6a4ba]">
            <x-ui-icon name="share" class="h-5 w-5" />
            Chia sẻ
          </button>
        </div>

        <p class="mt-4 px-1 text-sm font-medium text-[#8d7168]">
          {{ $post->reactions_count }} cảm xúc · {{ $post->approved_comments_count }} bình luận
        </p>
      </div>
    </div>
  </section>

  <section class="bg-[#fffaf5] px-4 py-10 sm:px-5 sm:py-14">
    <div class="mx-auto max-w-[820px]">
      <div class="mb-6 flex items-end justify-between gap-4">
        <div>
          <p class="font-['Dancing_Script'] text-2xl text-[#c05779]">Lời nhắn còn lại</p>
          <h2 class="memory-heading mt-1 text-3xl font-semibold leading-tight text-[#32131c] sm:text-4xl">Bình luận</h2>
        </div>
        <span class="rounded-full bg-[#f8e4dd] px-3 py-1.5 text-xs font-semibold text-[#8b4b42]">{{ $post->approved_comments_count }}</span>
      </div>

      <div class="space-y-4">
        @forelse($post->approvedComments as $comment)
          <article class="flex gap-3">
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[#f3d5c5] text-sm font-bold text-[#812744]">
              {{ str($comment->name)->substr(0, 2)->upper() }}
            </div>
            <div class="rounded-3xl rounded-tl-sm border border-[#f0dfd5] bg-white px-4 py-3 shadow-sm">
              <p class="font-semibold text-[#32131c]">{{ $comment->name }}</p>
              <p class="mt-1 text-sm leading-6 text-[#6b554d]">{{ $comment->content }}</p>
            </div>
          </article>
        @empty
          <p class="rounded-3xl border border-dashed border-[#e6cfc4] bg-[#fff6f0] px-5 py-6 text-sm leading-6 text-[#7b6258]">
            Chưa có bình luận. Hãy để lại một lời nhắn nhỏ cho kỷ niệm này.
          </p>
        @endforelse
      </div>

      <form method="POST" action="{{ route('memories.comments.store', $post->slug) }}" class="mt-7 rounded-[2rem] border border-[#ead7ca] bg-white p-4 shadow-[0_18px_60px_rgba(74,39,32,0.07)] sm:p-5">
        @csrf
        <div class="grid gap-3 sm:grid-cols-2">
          <input name="name" required placeholder="Tên hiển thị" class="min-h-12 rounded-2xl border border-[#ead7ca] bg-[#fffaf5] px-4 py-3 text-sm outline-none placeholder:text-[#ab9389] focus:border-[#d84d80] focus:ring-2 focus:ring-[#f4cad8]">
          <input name="email" type="email" placeholder="Email tùy chọn" class="min-h-12 rounded-2xl border border-[#ead7ca] bg-[#fffaf5] px-4 py-3 text-sm outline-none placeholder:text-[#ab9389] focus:border-[#d84d80] focus:ring-2 focus:ring-[#f4cad8]">
        </div>
        <textarea name="content" required rows="3" placeholder="Viết cảm nhận hoặc lời nhắn của bạn về kỷ niệm này..." class="mt-3 w-full rounded-2xl border border-[#ead7ca] bg-[#fffaf5] px-4 py-3 text-sm leading-6 outline-none placeholder:text-[#ab9389] focus:border-[#d84d80] focus:ring-2 focus:ring-[#f4cad8]"></textarea>
        <button class="mt-3 inline-flex min-h-12 items-center justify-center rounded-full bg-[#812744] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#6f203a] focus:outline-none focus:ring-2 focus:ring-[#e6a4ba]">
          Gửi bình luận
        </button>
      </form>

      <form method="POST" action="{{ route('memories.messages.store', $post->slug) }}" class="mt-5 rounded-[2rem] border border-[#eed2dc] bg-[#fbe8ef] p-4 sm:p-5">
        @csrf
        <h3 class="memory-heading text-2xl font-semibold text-[#812744]">Gửi lời nhắn riêng</h3>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
          <input name="name" required placeholder="Tên của bạn" class="min-h-12 rounded-2xl border border-white/90 bg-white/80 px-4 py-3 text-sm outline-none focus:border-[#d84d80] focus:ring-2 focus:ring-white">
          <input name="email" type="email" placeholder="Email nếu muốn" class="min-h-12 rounded-2xl border border-white/90 bg-white/80 px-4 py-3 text-sm outline-none focus:border-[#d84d80] focus:ring-2 focus:ring-white">
        </div>
        <textarea name="message" required rows="3" placeholder="Tin nhắn này chỉ admin đọc..." class="mt-3 w-full rounded-2xl border border-white/90 bg-white/80 px-4 py-3 text-sm leading-6 outline-none focus:border-[#d84d80] focus:ring-2 focus:ring-white"></textarea>
        <button class="mt-3 inline-flex min-h-12 items-center justify-center rounded-full border border-[#812744]/20 bg-white px-5 py-3 text-sm font-semibold text-[#812744] transition hover:border-[#812744]/40 hover:bg-[#fff8fb] focus:outline-none focus:ring-2 focus:ring-white">
          Gửi riêng
        </button>
      </form>
    </div>
  </section>

  @if($related->isNotEmpty())
    <section class="bg-[#f8f0ea] px-4 py-10 sm:px-5 sm:py-14">
      <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex items-end justify-between gap-4">
          <div>
            <p class="font-['Dancing_Script'] text-2xl text-[#c05779]">Đi tiếp một chút</p>
            <h2 class="memory-heading text-3xl font-semibold text-[#32131c] sm:text-4xl">Kỷ niệm liên quan</h2>
          </div>
          <a href="{{ route('memories.index') }}" class="hidden rounded-full border border-[#d9a2a9] px-4 py-2 text-sm font-semibold text-[#812744] transition hover:bg-white sm:inline-flex">Xem tất cả</a>
        </div>
        <div class="-mx-4 flex snap-x gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] sm:mx-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:overflow-visible sm:px-0 sm:pb-0">
          @foreach($related as $item)
            <div class="w-[72%] shrink-0 snap-center sm:w-auto">
              <x-memory-card :post="$item" />
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @foreach($musicSections as $section)
    {!! $renderer->render($section, $post, $mediaById) !!}
  @endforeach

  @if($musicEnabled && $musicUrl)
    <section class="memory-music-section relative overflow-hidden bg-[#241218] px-4 py-12 text-white sm:px-5 sm:py-16">
      @if($post->coverMedia)
        <img src="{{ $post->coverMedia->display_url }}" alt="" aria-hidden="true" loading="lazy" class="absolute inset-0 h-full w-full object-cover opacity-25 blur-sm scale-105">
      @endif
      <div class="absolute inset-0 bg-gradient-to-b from-[#241218]/70 via-[#241218]/92 to-[#14090d]"></div>

      <div
        x-data="{ playing: false, toggle() { const audio = this.$refs.audio; audio.paused ? audio.play() : audio.pause(); } }"
        class="relative mx-auto max-w-[820px]"
      >
        <div class="grid gap-6 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
          <div>
            <p class="font-['Dancing_Script'] text-3xl text-[#f3bdca]">Soundtrack</p>
            <h2 class="memory-heading mt-2 text-4xl font-semibold leading-tight text-white sm:text-5xl">
              {{ $post->music_title ?: 'Bài nhạc của kỷ niệm này' }}
            </h2>
            @if($post->music_artist)
              <p class="mt-3 text-base text-white/68">{{ $post->music_artist }}</p>
            @endif
          </div>

          <button type="button" x-on:click="toggle()" class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full bg-white px-5 py-3 text-sm font-semibold text-[#812744] shadow-[0_18px_60px_rgba(0,0,0,0.28)] transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#f4cad8]" title="{{ $post->music_title ?: 'Bật/tắt nhạc nền' }}">
            <x-ui-icon x-show="!playing" name="music" class="h-5 w-5" />
            <span x-show="playing" class="flex h-5 items-end gap-0.5" aria-hidden="true">
              <i class="block h-2 w-1 animate-pulse rounded bg-[#d84d80]"></i>
              <i class="block h-5 w-1 animate-pulse rounded bg-[#d84d80] [animation-delay:120ms]"></i>
              <i class="block h-3.5 w-1 animate-pulse rounded bg-[#d84d80] [animation-delay:240ms]"></i>
            </span>
            <span x-text="playing ? 'Tạm dừng' : 'Phát nhạc'"></span>
          </button>
        </div>

        <audio x-ref="audio" src="{{ $musicUrl }}" loop preload="metadata" x-on:play="playing = true" x-on:pause="playing = false" x-on:ended="playing = false"></audio>
      </div>
    </section>
  @endif
@endsection
