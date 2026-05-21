@extends('frontend.layouts.app')

@section('title', 'Archive - '.config('app.name'))

@section('content')
  <section class="bg-neutral-950 px-5 pb-14 pt-32 text-white">
    <div class="mx-auto max-w-5xl">
      <p class="text-sm uppercase tracking-[0.3em] text-white/45">Archive</p>
      <h1 class="mt-4 text-5xl font-semibold">By year</h1>
    </div>
  </section>
  <section class="bg-white px-5 py-16">
    <div class="mx-auto max-w-5xl space-y-12">
      @foreach($groups as $year => $posts)
        <div>
          <h2 class="text-3xl font-semibold">{{ $year }}</h2>
          <div class="mt-5 divide-y divide-neutral-200">
            @foreach($posts as $post)
              <a href="{{ route('memories.show', $post->slug) }}" class="flex items-center justify-between gap-6 py-4">
                <span class="font-medium">{{ $post->title }}</span>
                <span class="text-sm text-neutral-500">{{ $post->memory_date?->format('M d') }}</span>
              </a>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </section>
@endsection

