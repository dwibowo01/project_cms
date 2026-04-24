<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'CMS-Project') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|bebas-neue:400" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

@php
    $centralDomain = collect(config('tenancy.central_domains'))->first(fn($d) => !filter_var($d, FILTER_VALIDATE_IP));
    $scheme = request()->getScheme();
@endphp

<body
    style="background:#fff;height:100vh;display:flex;flex-direction:column;overflow:hidden;font-family:'Instrument Sans',sans-serif;margin:0;">

    {{-- Header --}}
    <header
        style="display:flex;align-items:flex-start;justify-content:space-between;padding:2rem 2.5rem 1rem;flex-shrink:0;">
        <div>
            <p style="font-weight:600;line-height:1;color:#000;font-size:1.2vw;margin:0;">Welcome To</p>
            <p style="font-weight:600;line-height:1.2;color:#000;font-size:2.3vw;margin:0;">CMS - Project Management
            </p>
        </div>
        <a href="{{ route('login') }}"
            style="flex-shrink:0;margin-left:2rem;margin-top:0.25rem;background:#000000;color:#fff;font-size:0.875rem;font-weight:600;padding:0.75rem 1.5rem;white-space:nowrap;text-decoration:none;display:inline-block;border-radius:0.5rem;"
            onmouseover="this.style.background='#34a9ec'" onmouseout="this.style.background='#000'">
            Login
        </a>
    </header>

    {{-- Tenant Selection Panels --}}
    <main style="flex:1;position:relative;min-height:0;">

        {{-- DOCKING --}}
        <a href="{{ config('app.url') }}/login?tenant=docking"
            style="position:absolute;inset:0;background:url('{{ asset('Website_Slider.jpg') }}') center/cover no-repeat;clip-path:polygon(0% 0%, 45.6% 0%, 15.2% 100%, 0% 100%);text-decoration:none;"
            onmouseover="this.style.background='linear-gradient(rgba(52,169,236,0.55),rgba(52,169,236,0.55)),url({{ asset('Website_Slider.jpg') }}) center/cover no-repeat'"
            onmouseout="this.style.background='url({{ asset('Website_Slider.jpg') }}) center/cover no-repeat'">
            <span
                style="position:absolute;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:5vw;left:5%;top:55%;transform:translateY(-50%);letter-spacing:0.05em;pointer-events:none;">
                DOCKING
            </span>
        </a>

        {{-- NEW BUILDING --}}
        <a href="{{ config('app.url') }}/login?tenant=new-building"
            style="position:absolute;inset:0;background:url('{{ asset('Website_Slider.jpg') }}') center/cover no-repeat;clip-path:polygon(47.2% 0%, 83.3% 0%, 52.9% 100%, 16.8% 100%);text-decoration:none;"
            onmouseover="this.style.background='linear-gradient(rgba(52,169,236,0.55),rgba(52,169,236,0.55)),url({{ asset('Website_Slider.jpg') }}) center/cover no-repeat'"
            onmouseout="this.style.background='url({{ asset('Website_Slider.jpg') }}) center/cover no-repeat'">
            <span
                style="position:absolute;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:5vw;left:38%;top:55%;transform:translateY(-50%);letter-spacing:0.05em;pointer-events:none;">
                NEW BUILDING
            </span>
        </a>
        {{-- <div title="Coming Soon"
            style="position:absolute;inset:0;background:#000000;clip-path:polygon(47.2% 0%, 83.3% 0%, 52.9% 100%, 16.8% 100%);opacity:0.4;cursor:not-allowed;">
            <span
                style="position:absolute;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:5vw;left:38%;top:55%;transform:translateY(-50%);letter-spacing:0.05em;pointer-events:none;">
                NEW BUILDING
            </span>
        </div> --}}

        {{-- SITE --}}
        <a href="{{ config('app.url') }}/login?tenant=site"
            style="position:absolute;inset:0;background:url('{{ asset('Website_Slider.jpg') }}') center/cover no-repeat;clip-path:polygon(84.8% 0%, 100% 0%, 100% 100%, 54.4% 100%);text-decoration:none;"
            onmouseover="this.style.background='linear-gradient(rgba(52,169,236,0.55),rgba(52,169,236,0.55)),url({{ asset('Website_Slider.jpg') }}) center/cover no-repeat'"
            onmouseout="this.style.background='url({{ asset('Website_Slider.jpg') }}) center/cover no-repeat'">
            <span
                style="position:absolute;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:5vw;left:80%;top:55%;transform:translateY(-50%);letter-spacing:0.05em;pointer-events:none;">
                SITE
            </span>
        </a>

    </main>

    {{-- Footer --}}
    <footer style="flex-shrink:0;padding:0.75rem 2.5rem;text-align:right;">
        <span style="font-size:0.875rem;color:#4b5563;">&copy;Caputra-2026</span>
    </footer>

</body>

</html>
