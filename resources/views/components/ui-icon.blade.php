@props(['name'])

<svg {{ $attributes->class(['inline-block shrink-0']) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
  @switch($name)
    @case('arrow-left')
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m6-7-7 7 7 7"/>
      @break
    @case('camera')
      <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h3l1.4-2h7.2l1.4 2h3v10H4z"/>
      <circle cx="12" cy="13" r="3.3"/>
      @break
    @case('chevron-right')
      <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
      @break
    @case('heart')
      <path stroke-linecap="round" stroke-linejoin="round" d="M20.8 8.7c0 5.2-8.8 10.1-8.8 10.1S3.2 13.9 3.2 8.7a4.5 4.5 0 0 1 8-2.8 4.5 4.5 0 0 1 8 2.8Z"/>
      @break
    @case('images')
      <rect x="3.5" y="5" width="14" height="14" rx="2"/>
      <path stroke-linecap="round" stroke-linejoin="round" d="M17.5 8h3v12h-13v-1M6.5 16l3.7-4 2.8 2.7 1.8-1.8 2.7 3.1"/>
      <circle cx="13.2" cy="9.4" r="1"/>
      @break
    @case('map-pin')
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s6.25-5.45 6.25-11A6.25 6.25 0 0 0 5.75 10c0 5.55 6.25 11 6.25 11Z"/>
      <circle cx="12" cy="10" r="2.2"/>
      @break
    @case('music')
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 18V6l10-2v11"/>
      <ellipse cx="6.5" cy="18" rx="2.5" ry="2"/>
      <ellipse cx="16.5" cy="15" rx="2.5" ry="2"/>
      @break
    @case('plane')
      <path stroke-linecap="round" stroke-linejoin="round" d="M21 3 3 10.4l7.2 2.2L13 20l2.3-6.2L21 3Z"/>
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.2 12.6 15.3 13.8"/>
      @break
    @case('search')
      <circle cx="10.5" cy="10.5" r="6.5"/>
      <path stroke-linecap="round" d="m16 16 5 5"/>
      @break
    @case('share')
      <circle cx="18" cy="5" r="2.5"/>
      <circle cx="6" cy="12" r="2.5"/>
      <circle cx="18" cy="19" r="2.5"/>
      <path stroke-linecap="round" d="m8.3 10.8 7.4-4.4m-7.4 6.8 7.4 4.4"/>
      @break
    @case('sparkle')
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5 14.5 9l5.5 3-5.5 3-2.5 5.5L9.5 15 4 12l5.5-3L12 3.5Z"/>
      @break
    @case('star')
      <path stroke-linecap="round" stroke-linejoin="round" d="m12 3.5 2.7 5.6 6.1.9-4.4 4.3 1 6.2-5.4-2.9-5.4 2.9 1-6.2L3.2 10l6.1-.9L12 3.5Z"/>
      @break
    @case('sun')
      <circle cx="12" cy="12" r="3.5"/>
      <path stroke-linecap="round" d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1m-8.6 8.6-2.1 2.1"/>
      @break
    @case('tag')
      <path stroke-linecap="round" stroke-linejoin="round" d="M20 13.2 13.2 20 4 10.8V4h6.8L20 13.2Z"/>
      <circle cx="8.2" cy="8.2" r="1"/>
      @break
    @case('thumb-up')
      <path stroke-linecap="round" stroke-linejoin="round" d="M7 10.5V20H4V10.5h3Zm0 0 4.2-7c1.4.5 2.1 1.7 1.8 3.2l-.5 2.3H19a2 2 0 0 1 1.9 2.6L18.6 19a2 2 0 0 1-1.9 1.4H7"/>
      @break
    @case('user')
      <circle cx="12" cy="8" r="3.4"/>
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 20a7 7 0 0 1 14 0"/>
      @break
    @default
      <circle cx="12" cy="12" r="8"/>
  @endswitch
</svg>
