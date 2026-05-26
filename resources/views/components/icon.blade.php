@props([
    'name' => 'circle',
    'stroke' => 1.5,
    'size' => null,
])

@php
    $base = 'inline-block shrink-0';
    $sizeClass = $size ?? 'w-5 h-5';
    $classes = trim($base . ' ' . $sizeClass . ' ' . ($attributes->get('class') ?? ''));
    $svgAttrs = $attributes->except('class')->merge([
        'viewBox' => '0 0 24 24',
        'fill' => 'none',
        'stroke' => 'currentColor',
        'stroke-width' => $stroke,
        'stroke-linecap' => 'round',
        'stroke-linejoin' => 'round',
        'aria-hidden' => 'true',
        'class' => $classes,
    ]);
@endphp

<svg {{ $svgAttrs }}>
@switch($name)
    @case('mountain')
        <path d="M3 20 L8 11 L12 16 L16 8 L21 20 Z"/>
        <circle cx="16.5" cy="6" r="1.2" fill="currentColor" stroke="none"/>
        @break

    @case('camera')
        <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
        <circle cx="12" cy="13" r="4"/>
        @break

    @case('mountain-range')
        <path d="M2 19 L6 13 L9 16 L13 9 L17 14 L22 19 Z"/>
        <path d="M9 16 L11.5 13"/>
        @break

    @case('tent')
        <path d="M3 20 L12 4 L21 20 Z"/>
        <path d="M9 20 L12 14 L15 20"/>
        @break

    @case('backpack')
        <rect x="6" y="7" width="12" height="14" rx="2"/>
        <path d="M9 7 V5 a3 3 0 0 1 6 0 V7"/>
        <path d="M6 13 H18"/>
        <rect x="10" y="14" width="4" height="3" rx="0.5"/>
        @break

    @case('sleeping-bag')
        <path d="M4 5 C 4 4 5 3 6 3 L 18 3 C 19 3 20 4 20 5 L 19 19 C 19 20 18 21 17 21 L 7 21 C 6 21 5 20 5 19 Z"/>
        <path d="M9 8 H15"/>
        <path d="M8 21 V18"/>
        <path d="M16 21 V18"/>
        @break

    @case('flame')
        <path d="M12 22 C 16.5 22 19 18.5 19 14.5 C 19 10 15 8 13 4 C 13 8 10 9 9 12 C 8.3 14 7.5 15 9 17 C 9.5 14 10.5 14.5 11.5 14 C 11 16 9.5 17 10 19 C 10.5 21 11 22 12 22 Z"/>
        @break

    @case('lantern')
        <path d="M10 3 H14"/>
        <path d="M12 3 V5"/>
        <rect x="7" y="5" width="10" height="3" rx="1"/>
        <path d="M8 8 L8 17 a 4 4 0 0 0 8 0 L 16 8"/>
        <path d="M8 17 H16"/>
        <path d="M11 20 H13"/>
        <path d="M12 9 V14"/>
        @break

    @case('compass')
        <circle cx="12" cy="12" r="9"/>
        <path d="M15.5 8.5 L13 12.5 L8.5 15.5 L11 11.5 Z" fill="currentColor" stroke="none"/>
        @break

    @case('check')
        <path d="M5 12 L10 17 L19 7"/>
        @break

    @case('check-circle')
        <circle cx="12" cy="12" r="9"/>
        <path d="M8 12 L11 15 L16 9"/>
        @break

    @case('x')
        <path d="M6 6 L18 18"/>
        <path d="M18 6 L6 18"/>
        @break

    @case('x-circle')
        <circle cx="12" cy="12" r="9"/>
        <path d="M9 9 L15 15"/>
        <path d="M15 9 L9 15"/>
        @break

    @case('alert-triangle')
        <path d="M12 3 L22 20 H2 Z"/>
        <path d="M12 10 V14"/>
        <circle cx="12" cy="17.2" r="0.6" fill="currentColor" stroke="none"/>
        @break

    @case('info')
        <circle cx="12" cy="12" r="9"/>
        <path d="M12 11 V17"/>
        <circle cx="12" cy="7.8" r="0.7" fill="currentColor" stroke="none"/>
        @break

    @case('search')
        <circle cx="11" cy="11" r="6"/>
        <path d="M20 20 L15.5 15.5"/>
        @break

    @case('printer')
        <path d="M7 8 V3 H17 V8"/>
        <rect x="4" y="8" width="16" height="9" rx="1.5"/>
        <rect x="7" y="14" width="10" height="6"/>
        <circle cx="17" cy="11" r="0.7" fill="currentColor" stroke="none"/>
        @break

    @case('download')
        <path d="M12 4 V15"/>
        <path d="M7 11 L12 16 L17 11"/>
        <path d="M4 19 H20"/>
        @break

    @case('edit')
        <path d="M4 20 H8 L19 9 L15 5 L4 16 Z"/>
        <path d="M13 7 L17 11"/>
        @break

    @case('trash')
        <path d="M4 7 H20"/>
        <path d="M9 7 V5 a1 1 0 0 1 1-1 H14 a1 1 0 0 1 1 1 V7"/>
        <path d="M6 7 L7 20 a1 1 0 0 0 1 1 H16 a1 1 0 0 0 1-1 L18 7"/>
        <path d="M10 11 V17"/>
        <path d="M14 11 V17"/>
        @break

    @case('plus')
        <path d="M12 5 V19"/>
        <path d="M5 12 H19"/>
        @break

    @case('plus-circle')
        <circle cx="12" cy="12" r="9"/>
        <path d="M12 8 V16"/>
        <path d="M8 12 H16"/>
        @break

    @case('eye')
        <path d="M2 12 C 5 6 9 4 12 4 C 15 4 19 6 22 12 C 19 18 15 20 12 20 C 9 20 5 18 2 12 Z"/>
        <circle cx="12" cy="12" r="3"/>
        @break

    @case('eye-off')
        <path d="M3 3 L21 21"/>
        <path d="M10.5 6.2 C 11 6.1 11.5 6 12 6 C 15 6 19 8 22 14"/>
        <path d="M6 9 C 4.5 10.5 3.3 12.2 2 14 C 5 20 9 22 12 22 C 13.5 22 15 21.7 16.5 21"/>
        <path d="M9.9 9.9 a 3 3 0 0 0 4.2 4.2"/>
        @break

    @case('log-out')
        <path d="M9 4 H5 a2 2 0 0 0 -2 2 V18 a2 2 0 0 0 2 2 H9"/>
        <path d="M14 8 L18 12 L14 16"/>
        <path d="M18 12 H8"/>
        @break

    @case('log-in')
        <path d="M15 4 H19 a2 2 0 0 1 2 2 V18 a2 2 0 0 1 -2 2 H15"/>
        <path d="M10 16 L6 12 L10 8"/>
        <path d="M6 12 H16"/>
        @break

    @case('home')
        <path d="M3 11 L12 3 L21 11"/>
        <path d="M5 10 V20 H19 V10"/>
        <path d="M10 20 V14 H14 V20"/>
        @break

    @case('box')
        <path d="M3 7 L12 3 L21 7 V17 L12 21 L3 17 Z"/>
        <path d="M3 7 L12 11 L21 7"/>
        <path d="M12 11 V21"/>
        @break

    @case('calendar')
        <rect x="3" y="5" width="18" height="16" rx="2"/>
        <path d="M3 10 H21"/>
        <path d="M8 3 V7"/>
        <path d="M16 3 V7"/>
        <circle cx="9" cy="15" r="0.7" fill="currentColor" stroke="none"/>
        <circle cx="12" cy="15" r="0.7" fill="currentColor" stroke="none"/>
        <circle cx="15" cy="15" r="0.7" fill="currentColor" stroke="none"/>
        @break

    @case('clock')
        <circle cx="12" cy="12" r="9"/>
        <path d="M12 7 V12 L16 14"/>
        @break

    @case('map-pin')
        <path d="M19 11 C 19 16 12 22 12 22 C 12 22 5 16 5 11 a 7 7 0 0 1 14 0 Z"/>
        <circle cx="12" cy="11" r="2.5"/>
        @break

    @case('phone')
        <path d="M5 4 H9 L11 9 L8.5 10.5 a 12 12 0 0 0 5 5 L 15 13 L 20 15 V19 a 2 2 0 0 1 -2 2 A 16 16 0 0 1 3 6 a 2 2 0 0 1 2 -2 Z"/>
        @break

    @case('mail')
        <rect x="3" y="5" width="18" height="14" rx="2"/>
        <path d="M3 7 L12 13 L21 7"/>
        @break

    @case('whatsapp')
        <path d="M12 3 a 9 9 0 0 0 -7.8 13.5 L3 21 L7.7 19.6 A 9 9 0 1 0 12 3 Z"/>
        <path d="M8.5 8.5 C 8.5 8 9 7.5 9.5 7.5 H 10 L 11 10 L 10 11 a 5 5 0 0 0 3 3 L 14 13 L 16.5 14 V 14.5 C 16.5 15 16 15.5 15.5 15.5 C 13 15.5 8.5 12 8.5 8.5 Z"/>
        @break

    @case('chart-bar')
        <path d="M4 20 V8"/>
        <path d="M10 20 V4"/>
        <path d="M16 20 V12"/>
        <path d="M3 20 H21"/>
        @break

    @case('chart-line')
        <path d="M3 20 H21"/>
        <path d="M4 17 L9 11 L13 14 L20 6"/>
        <circle cx="20" cy="6" r="1.2" fill="currentColor" stroke="none"/>
        @break

    @case('chart-pie')
        <path d="M12 3 a 9 9 0 1 0 9 9 H 12 Z"/>
        <path d="M14 3.3 a 9 9 0 0 1 6.7 6.7 H 14 Z"/>
        @break

    @case('trending-up')
        <path d="M3 17 L9 11 L13 15 L21 7"/>
        <path d="M15 7 H21 V13"/>
        @break

    @case('chevron-right')
        <path d="M9 6 L15 12 L9 18"/>
        @break

    @case('chevron-left')
        <path d="M15 6 L9 12 L15 18"/>
        @break

    @case('chevron-down')
        <path d="M6 9 L12 15 L18 9"/>
        @break

    @case('chevron-up')
        <path d="M6 15 L12 9 L18 15"/>
        @break

    @case('arrow-right')
        <path d="M5 12 H19"/>
        <path d="M13 6 L19 12 L13 18"/>
        @break

    @case('arrow-up-right')
        <path d="M7 17 L17 7"/>
        <path d="M9 7 H17 V15"/>
        @break

    @case('arrow-uturn-left')
        <path d="M9 14 L4 9 L9 4"/>
        <path d="M4 9 H15 a 5 5 0 0 1 5 5 V20"/>
        @break

    @case('users')
        <circle cx="9" cy="8" r="3.5"/>
        <path d="M3 20 C 3 16 5.5 14 9 14 C 12.5 14 15 16 15 20"/>
        <path d="M16 5 a 3 3 0 0 1 0 6"/>
        <path d="M18 20 C 18 17 19.5 15.5 21.5 14.8"/>
        @break

    @case('user')
        <circle cx="12" cy="8" r="4"/>
        <path d="M4 20 C 4 16 7 14 12 14 C 17 14 20 16 20 20"/>
        @break

    @case('user-circle')
        <circle cx="12" cy="12" r="9"/>
        <circle cx="12" cy="10" r="3"/>
        <path d="M6 19 C 7 16 9 14.5 12 14.5 C 15 14.5 17 16 18 19"/>
        @break

    @case('shield')
        <path d="M12 3 L20 6 V12 C 20 17 16.5 20.5 12 22 C 7.5 20.5 4 17 4 12 V6 Z"/>
        <path d="M9 12 L11.5 14.5 L16 10"/>
        @break

    @case('shield-check')
        <path d="M12 3 L20 6 V12 C 20 17 16.5 20.5 12 22 C 7.5 20.5 4 17 4 12 V6 Z"/>
        @break

    @case('hand-wave')
        <path d="M7 13 L4 10 a 2 2 0 0 1 2.8 -2.8 L 9.5 10"/>
        <path d="M10 11 L7.5 8.5 a 2 2 0 0 1 2.8 -2.8 L 13 9"/>
        <path d="M12 10 L10 8 a 2 2 0 0 1 2.8 -2.8 L 15 8"/>
        <path d="M14 9 L13 8 a 2 2 0 0 1 2.8 -2.8 L 19 11 V 15 a 6 6 0 0 1 -12 0 V 13"/>
        @break

    @case('bell')
        <path d="M6 16 L5 17 H19 L18 16 V11 a 6 6 0 0 0 -12 0 Z"/>
        <path d="M10 20 a 2 2 0 0 0 4 0"/>
        @break

    @case('menu')
        <path d="M4 7 H20"/>
        <path d="M4 12 H20"/>
        <path d="M4 17 H20"/>
        @break

    @case('folder')
        <path d="M3 7 a 2 2 0 0 1 2 -2 H 9 L 11 7 H 19 a 2 2 0 0 1 2 2 V 17 a 2 2 0 0 1 -2 2 H 5 a 2 2 0 0 1 -2 -2 Z"/>
        @break

    @case('file-text')
        <path d="M6 3 H14 L19 8 V20 a 1 1 0 0 1 -1 1 H 6 a 1 1 0 0 1 -1 -1 V 4 a 1 1 0 0 1 1 -1 Z"/>
        <path d="M14 3 V8 H19"/>
        <path d="M8 12 H16"/>
        <path d="M8 16 H14"/>
        @break

    @case('sparkles')
        <path d="M12 3 L13.5 8.5 L19 10 L13.5 11.5 L12 17 L10.5 11.5 L5 10 L10.5 8.5 Z"/>
        <path d="M19 16 L19.6 18 L21.5 18.5 L19.6 19 L19 21 L18.4 19 L16.5 18.5 L18.4 18 Z"/>
        @break

    @case('key')
        <circle cx="8" cy="15" r="4"/>
        <path d="M11 13 L20 4"/>
        <path d="M16 8 L18 10"/>
        <path d="M14 10 L17 13"/>
        @break

    @case('shopping-bag')
        <path d="M5 7 H19 L18 21 H6 Z"/>
        <path d="M9 7 V5 a 3 3 0 0 1 6 0 V 7"/>
        @break

    @case('package-check')
        <path d="M3 7 L12 3 L21 7 V17 L12 21 L3 17 Z"/>
        <path d="M3 7 L12 11 L21 7"/>
        <path d="M9 15 L11 17 L15 13"/>
        @break

    @case('clipboard-list')
        <rect x="6" y="4" width="12" height="17" rx="1.5"/>
        <rect x="9" y="2" width="6" height="4" rx="1"/>
        <path d="M9 11 H15"/>
        <path d="M9 15 H15"/>
        <path d="M9 19 H13"/>
        @break

    @case('credit-card')
        <rect x="3" y="6" width="18" height="13" rx="2"/>
        <path d="M3 10 H21"/>
        <path d="M7 15 H10"/>
        @break

    @case('star')
        <path d="M12 3 L14.5 9.5 L21 10 L16 14.5 L17.5 21 L12 17.5 L6.5 21 L8 14.5 L3 10 L9.5 9.5 Z"/>
        @break

    @case('campfire')
        <path d="M12 11 C 14 9 13 6 12 4 C 11 6 11 7 10 8 C 9 9 9 11 12 11 Z"/>
        <path d="M4 20 L20 20"/>
        <path d="M6 20 L12 14"/>
        <path d="M18 20 L12 14"/>
        @break

    @case('moon')
        <path d="M20 14 a 8 8 0 1 1 -10 -10 a 6 6 0 0 0 10 10 Z"/>
        @break

    @case('sun')
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 3 V5"/>
        <path d="M12 19 V21"/>
        <path d="M3 12 H5"/>
        <path d="M19 12 H21"/>
        <path d="M5.6 5.6 L7 7"/>
        <path d="M17 17 L18.4 18.4"/>
        <path d="M5.6 18.4 L7 17"/>
        <path d="M17 7 L18.4 5.6"/>
        @break

    @case('lock')
        <rect x="5" y="11" width="14" height="10" rx="2"/>
        <path d="M8 11 V8 a 4 4 0 0 1 8 0 V11"/>
        @break

    @case('settings')
        <circle cx="12" cy="12" r="3"/>
        <path d="M12 2 L12 4 M12 20 L12 22 M2 12 L4 12 M20 12 L22 12 M5 5 L6.4 6.4 M17.6 17.6 L19 19 M5 19 L6.4 17.6 M17.6 6.4 L19 5"/>
        @break

    @case('circle')
    @default
        <circle cx="12" cy="12" r="9"/>
@endswitch
</svg>
