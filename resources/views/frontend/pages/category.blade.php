@extends('frontend.layouts.app')

@section('title', $category->name.' - '.config('app.name'))

@section('content')
  <section class="bg-neutral-950 px-5 pb-14 pt-32 text-white">
    <div class="mx-auto max-w-7xl">
      <p class="text-sm uppercase tracking-[0.3em] text-white/45">Category</p>
      <h1 class="mt-4 text-5xl font-semibold">{{ $category->name }}</h1>
      @if($category->description)
        <p class="mt-4 max-w-2xl text-white/70">{{ $category->description }}</p>
      @endif
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

