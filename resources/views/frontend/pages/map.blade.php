@extends('frontend.layouts.app')

@section('title', 'Ban do ky niem - '.config('app.name'))

@section('content')
  <section class="min-h-screen bg-[#fbfaf7] px-5 py-10">
    <div class="mx-auto max-w-5xl">
      <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[#d84d80]">Memory map</p>
      <h1 class="mt-2 text-4xl font-bold text-neutral-950">Ban do ky niem</h1>
      <div class="mt-8 overflow-hidden rounded-[24px] bg-white shadow-sm ring-1 ring-black/5">
        <div class="grid min-h-[420px] place-items-center bg-[linear-gradient(135deg,#e4f2ff,#fce8f0,#dff2d8)] p-8 text-center">
          <div>
            <div class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-full bg-white/75 text-[#812744] shadow">
              <x-ui-icon name="map-pin" class="h-8 w-8" />
            </div>
            <h2 class="text-2xl font-semibold">Sap co ban do dia diem</h2>
            <p class="mt-2 max-w-md text-neutral-600">Cac memory da co location se duoc gan toa do de xem lai hanh trinh cua hai dua.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
