@extends('frontend.layouts.app')

@section('title', 'Search - '.config('app.name'))

@section('content')
  <section class="bg-neutral-950 px-5 pb-14 pt-32 text-white">
    <div class="mx-auto max-w-7xl">
      <p class="text-sm uppercase tracking-[0.3em] text-white/45">Search</p>
      <h1 class="mt-4 text-5xl font-semibold">Results for "{{ $term }}"</h1>
    </div>
  </section>

  <section class="bg-[#f7f7f4] px-5 py-16">
    <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
      @foreach($posts as $post)
        <x-memory-card :post="$post" />
      @endforeach
    </div>
    <div class="mx-auto mt-10 max-w-7xl">{{ $posts->links() }}</div>
  </section>
@endsection

