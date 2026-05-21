@extends('frontend.layouts.app')

@section('title', 'Bo suu tap - '.config('app.name'))

@section('content')
  <section class="bg-[#fbfaf7] px-4 py-8 md:px-6 md:py-14">
    <div class="mx-auto max-w-6xl">
      <div class="mb-8 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[#d84d80]">Visual memories</p>
          <h1 class="mt-2 text-4xl font-bold tracking-tight text-neutral-950 md:text-6xl">Bo suu tap</h1>
        </div>
        <form action="{{ route('memories.index') }}" class="flex gap-2 rounded-full border border-black/10 bg-white p-1 shadow-sm">
          <input name="q" value="{{ request('q') }}" placeholder="Tim ky niem..." class="min-w-0 flex-1 rounded-full px-4 py-3 text-sm outline-none md:w-72">
          <button class="rounded-full bg-[#812744] px-5 py-3 text-sm font-semibold text-white">Tim</button>
        </form>
      </div>

      <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-5">
        @forelse($posts as $post)
          <x-memory-card :post="$post" />
        @empty
          <p class="col-span-full rounded-3xl bg-white p-8 text-neutral-500">Chua co ky niem cong khai nao.</p>
        @endforelse
      </div>

      <div class="mt-10">
        {{ $posts->links() }}
      </div>
    </div>
  </section>
@endsection
