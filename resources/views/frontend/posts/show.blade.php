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
  @isset($isPreview)
    <div class="fixed bottom-24 left-1/2 z-50 -translate-x-1/2 rounded-full bg-amber-300 px-4 py-2 text-sm font-semibold text-black shadow-lg">
      Preview mode
    </div>
  @endisset

  @foreach($post->visibleSections as $section)
    {!! $renderer->render($section, $post, $mediaById) !!}
  @endforeach

  <section class="border-y border-black/10 bg-white">
    <div class="mx-auto max-w-[760px] px-5 py-5">
      <div class="flex items-center justify-between gap-3">
        <div class="flex gap-3">
          @foreach(['love' => 'Thich', 'like' => 'Yeu', 'wow' => 'Wow'] as $type => $label)
            <form method="POST" action="{{ route('memories.reactions.store', $post->slug) }}">
              @csrf
              <input type="hidden" name="reaction_type" value="{{ $type }}">
              <button class="rounded-2xl border border-black/15 bg-white px-4 py-3 text-sm font-semibold text-neutral-700 hover:border-[#e34d86] hover:text-[#e34d86]">□ {{ $label }}</button>
            </form>
          @endforeach
        </div>
        <button type="button" onclick="navigator.share?.({title: @js($post->title), url: location.href})" class="rounded-2xl px-3 py-3 text-neutral-500">□</button>
      </div>
      <p class="mt-3 text-sm text-neutral-500">{{ $post->reactions_count }} cam xuc · {{ $post->approved_comments_count }} binh luan</p>
    </div>
  </section>

  <section class="bg-[#fbfaf7] px-5 py-8">
    <div class="mx-auto max-w-[760px]">
      <h2 class="mb-5 text-xl font-semibold text-neutral-950">Binh luan</h2>

      <div class="space-y-4">
        @foreach($post->approvedComments as $comment)
          <div class="flex gap-3">
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[#e4f2ff] text-sm font-bold text-[#0F4C81]">{{ str($comment->name)->substr(0, 2)->upper() }}</div>
            <div class="rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-black/5">
              <p class="font-semibold text-neutral-900">{{ $comment->name }}</p>
              <p class="mt-1 text-neutral-700">{{ $comment->content }}</p>
            </div>
          </div>
        @endforeach
      </div>

      <form method="POST" action="{{ route('memories.comments.store', $post->slug) }}" class="mt-6 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-black/5">
        @csrf
        <div class="grid gap-3 md:grid-cols-2">
          <input name="name" required placeholder="Ten cua ban" class="rounded-2xl border border-black/10 px-4 py-3 outline-none focus:border-[#e34d86]">
          <input name="email" type="email" placeholder="Email neu muon" class="rounded-2xl border border-black/10 px-4 py-3 outline-none focus:border-[#e34d86]">
        </div>
        <textarea name="content" required rows="3" placeholder="Viet binh luan..." class="mt-3 w-full rounded-2xl border border-black/10 px-4 py-3 outline-none focus:border-[#e34d86]"></textarea>
        <button class="mt-3 rounded-full bg-[#812744] px-5 py-3 text-sm font-semibold text-white">Gui binh luan</button>
      </form>

      <form method="POST" action="{{ route('memories.messages.store', $post->slug) }}" class="mt-5 rounded-3xl bg-[#f8dde8] p-4">
        @csrf
        <h3 class="font-semibold text-[#812744]">Gui loi nhan rieng</h3>
        <div class="mt-3 grid gap-3 md:grid-cols-2">
          <input name="name" required placeholder="Ten cua ban" class="rounded-2xl border border-white/80 px-4 py-3 outline-none">
          <input name="email" type="email" placeholder="Email neu muon" class="rounded-2xl border border-white/80 px-4 py-3 outline-none">
        </div>
        <textarea name="message" required rows="3" placeholder="Tin nhan chi admin doc..." class="mt-3 w-full rounded-2xl border border-white/80 px-4 py-3 outline-none"></textarea>
        <button class="mt-3 rounded-full border border-[#812744]/25 bg-white px-5 py-3 text-sm font-semibold text-[#812744]">Gui rieng</button>
      </form>
    </div>
  </section>

  @if(data_get($post->settings, 'music.enabled'))
    <button x-data="{ playing: false }" x-on:click="playing = ! playing" class="fixed right-4 top-5 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-white/90 text-[#812744] shadow-xl backdrop-blur md:top-24" title="Bat/tat nhac nen">
      <span x-show="!playing">♪</span>
      <span x-show="playing" class="flex items-end gap-0.5">
        <i class="block h-3 w-1 animate-pulse rounded bg-[#e34d86]"></i>
        <i class="block h-5 w-1 animate-pulse rounded bg-[#e34d86] [animation-delay:120ms]"></i>
        <i class="block h-4 w-1 animate-pulse rounded bg-[#e34d86] [animation-delay:240ms]"></i>
      </span>
    </button>
  @endif

  @if($related->isNotEmpty())
    <section class="bg-white px-5 py-10">
      <div class="mx-auto max-w-5xl">
        <div class="mb-5 flex items-center justify-between">
          <h2 class="text-2xl font-semibold">Ky niem lien quan</h2>
          <a href="{{ route('memories.index') }}" class="font-semibold text-[#e34d86]">Xem tat ca</a>
        </div>
        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
          @foreach($related as $item)
            <x-memory-card :post="$item" />
          @endforeach
        </div>
      </div>
    </section>
  @endif
@endsection
