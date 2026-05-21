@extends('frontend.layouts.app')

@section('title', 'Thu vien anh - '.config('app.name'))

@section('content')
  <section class="bg-[#fbfaf7] px-5 py-10">
    <div class="mx-auto max-w-6xl">
      <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[#d84d80]">Photo journal</p>
      <h1 class="mt-2 text-4xl font-bold text-neutral-950 md:text-6xl">Thu vien anh</h1>
      <div class="mt-8 columns-2 gap-3 space-y-3 md:columns-4 md:gap-5 md:space-y-5" data-lightbox>
        @foreach($media as $item)
          <a href="{{ $item->display_url }}" data-pswp-width="{{ $item->width ?: 1600 }}" data-pswp-height="{{ $item->height ?: 1000 }}" class="group block break-inside-avoid overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
            <img src="{{ $item->display_url }}" alt="{{ $item->alt }}" loading="lazy" class="w-full object-cover transition duration-700 group-hover:scale-105">
          </a>
        @endforeach
      </div>
      <div class="mt-10">
        {{ $media->links() }}
      </div>
    </div>
  </section>
@endsection
