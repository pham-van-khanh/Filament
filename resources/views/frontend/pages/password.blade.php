@extends('frontend.layouts.app')

@section('title', 'Protected memory - '.config('app.name'))

@section('content')
  <section class="flex min-h-screen items-center justify-center bg-neutral-950 px-5 py-24 text-white">
    <form method="POST" action="{{ route('memories.password', $post->slug) }}" class="w-full max-w-md rounded-3xl border border-white/10 bg-white/10 p-8 backdrop-blur-xl">
      @csrf
      <p class="text-sm uppercase tracking-[0.3em] text-white/45">Protected</p>
      <h1 class="mt-3 text-3xl font-semibold">{{ $post->title }}</h1>
      <p class="mt-3 text-white/60">Enter the password to open this memory.</p>
      <input type="password" name="password" class="mt-6 w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-white" autofocus>
      @error('password')
        <p class="mt-3 text-sm text-red-300">{{ $message }}</p>
      @enderror
      <button class="mt-5 w-full rounded-2xl bg-white px-4 py-3 font-semibold text-black">Open memory</button>
    </form>
  </section>
@endsection

