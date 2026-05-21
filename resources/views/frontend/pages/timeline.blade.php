@extends('frontend.layouts.app')

@section('title', 'Dong thoi gian - '.config('app.name'))

@section('content')
  <section class="bg-[#fbfaf7] px-5 py-10">
    <div class="mx-auto max-w-5xl">
      <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[#d84d80]">Life chapters</p>
      <h1 class="mt-2 text-4xl font-bold text-neutral-950 md:text-6xl">Dong thoi gian</h1>
      <div class="mt-10 space-y-12">
        @foreach($groups as $year => $posts)
          <div class="grid gap-5 md:grid-cols-[140px_1fr]">
            <h2 class="text-4xl font-bold text-[#812744]">{{ $year }}</h2>
            <div class="space-y-4 border-l border-black/10 pl-5">
              @foreach($posts as $post)
                <a href="{{ route('memories.show', $post->slug) }}" class="grid grid-cols-[92px_1fr] gap-4 rounded-3xl bg-white p-3 shadow-sm ring-1 ring-black/5">
                  <div class="aspect-square overflow-hidden rounded-2xl bg-[#e7f2fb]">
                    @if($post->coverMedia)
                      <img src="{{ $post->coverMedia->display_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                    @endif
                  </div>
                  <div class="min-w-0 py-1">
                    <p class="text-sm font-semibold text-neutral-500">{{ $post->memory_date?->format('d/m') }}</p>
                    <h3 class="mt-1 truncate text-xl font-semibold">{{ $post->title }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm text-neutral-600">{{ $post->excerpt }}</p>
                  </div>
                </a>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
