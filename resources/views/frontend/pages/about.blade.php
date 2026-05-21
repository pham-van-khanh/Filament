@extends('frontend.layouts.app')

@section('title', 'Ve chung minh - '.config('app.name'))

@section('content')
  <section class="min-h-screen bg-[#fbfaf7] px-5 py-10">
    <div class="mx-auto max-w-4xl overflow-hidden rounded-[28px] bg-white shadow-sm ring-1 ring-black/5">
      <div class="bg-[#812744] px-6 py-16 text-center text-white">
        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-white/65">chuaminh.vn</p>
        <h1 class="mt-4 font-serif text-5xl font-bold">Noi giu nhung ky niem cua hai dua</h1>
      </div>
      <div class="px-6 py-8 text-lg leading-8 text-neutral-700">
        <p>Day khong phai blog chu dai hay mang xa hoi. Day la mot visual memory website: moi bai viet la mot album, mot tam thiep dien tu, hoac mot trang nho co anh lam trung tam.</p>
        <p class="mt-5">Admin co the chon template, them block, upload anh, luu nhap va publish. Nguoi xem co the xem anh fullscreen, tha cam xuc, binh luan va gui loi nhan rieng.</p>
      </div>
    </div>
  </section>
@endsection
