@inject('tokenMerger', 'App\Services\Rendering\DesignTokenMerger')

@extends('frontend.layouts.app')

@section('body_class', 'template-soft-diary')

@section('content')
  <article class="memory-page" style="{{ $tokenMerger->inlineCssVariables($post) }}">
    @yield('post_content')
  </article>
@endsection
