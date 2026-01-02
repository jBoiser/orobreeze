<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(ellipse at center,
                    rgba(255, 255, 255, 0.08),
                    transparent 60%),
                linear-gradient(to bottom,
                    #062a4d,
                    #0b4f8a,
                    #0e63a9);
            background-attachment: fixed;
        }

        .frost-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-glow {
            filter: drop-shadow(0 0 15px rgba(103, 232, 249, 0.3));
        }

        .snow-layer {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }

        .snow-layer span {
            position: absolute;
            top: -10%;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            filter: blur(1px);
            animation: snow-fall linear infinite;
        }

        /* Snowflake variations */
        .snow-layer span:nth-child(1) {
            left: 5%;
            animation-duration: 14s;
        }

        .snow-layer span:nth-child(2) {
            left: 15%;
            animation-duration: 18s;
            width: 4px;
            height: 4px;
        }

        .snow-layer span:nth-child(3) {
            left: 25%;
            animation-duration: 12s;
        }

        .snow-layer span:nth-child(4) {
            left: 35%;
            animation-duration: 20s;
        }

        .snow-layer span:nth-child(5) {
            left: 45%;
            animation-duration: 16s;
        }

        .snow-layer span:nth-child(6) {
            left: 55%;
            animation-duration: 19s;
            width: 3px;
            height: 3px;
        }

        .snow-layer span:nth-child(7) {
            left: 65%;
            animation-duration: 15s;
        }

        .snow-layer span:nth-child(8) {
            left: 75%;
            animation-duration: 22s;
        }

        .snow-layer span:nth-child(9) {
            left: 85%;
            animation-duration: 13s;
        }

        .snow-layer span:nth-child(10) {
            left: 95%;
            animation-duration: 17s;
        }

        @keyframes snow-fall {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            100% {
                transform: translateY(120vh) translateX(40px);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="text-white min-h-svh flex items-center justify-center overflow-y-auto overflow-x-hidden py-10 md:py-0">

    <div class="snow-layer">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="relative z-10 max-w-4xl w-full px-6 text-center">

        <div class="mb-5 flex flex-col items-center">
            <div class="logo-glow mb-4">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo" 
                 class="h-40 md:h-[250px] w-auto transition-all"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            
            {{-- Fallback SVG (hidden by default) --}}
            <div style="display:none" class="bg-white/10 p-5 rounded-full border border-white/20">
                <svg class="w-12 h-12 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
            <div class="h-px w-20 bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-50"></div>
        </div>

        <h1 class="text-3xl md:text-6xl font-extrabold text-cyan-300 uppercase tracking-[0.15em] mb-3 leading-tight">
            Coming Soon
        </h1>

        <h2 class="text-xl md:text-3xl font-bold mb-6 tracking-tight leading-tight opacity-90">
            Innovating Air Comfort for
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-cyan-100">
                Homes and Businesses.
            </span>
        </h2>

        <p class="text-base md:text-lg text-blue-100/70 max-w mx-auto mb-10 leading-relaxed font-light">
            We’re building a better way for you to stay cool! <br> Our upcoming site will feature smarter HVAC solutions for families and businesses alike.
        </p>

        <div class="frost-card p-6 md:p-10 rounded-3xl shadow-2xl max-w-md mx-auto">
            <div class="absolute -top-10 -right-10 w-20 h-20 bg-cyan-400/10 rounded-full blur-2xl"></div>

            <h3 class="text-sm font-semibold mb-6 text-blue-200">Connect with our team</h3>
            <div class="flex flex-col space-y-4">
                @livewire('consultation-modal')
            </div>
        </div>

        <div class="mt-12 md:mt-16 flex flex-col md:flex-row items-center justify-center gap-6 md:gap-10 text-blue-100/50 text-sm">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="p-2 rounded-lg bg-white/5 border border-white/10 group-hover:border-cyan-400/50 transition-colors">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <span class="group-hover:text-blue-100 transition-colors font-medium">Contact Support</span>
            </div>
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="p-2 rounded-lg bg-white/5 border border-white/10 group-hover:border-cyan-400/50 transition-colors">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="group-hover:text-blue-100 transition-colors font-medium text-center">Cagayan De Oro City, PH</span>
            </div>
        </div>
    </div>

</body>

</html>