<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="google-adsense-account" content="ca-pub-7575258986593674">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', config('app.name', '光影流年'))</title>
    @yield('meta')
    <meta name="robots" content="index, follow">
    <script src="https://cdn.tailwindcss.com/3.4.17" data-suppress-cdn-warning></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Noto+Serif+SC:wght@400;600;700;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Playfair Display"', '"Noto Serif SC"', 'serif'],
                        body: ['"Inter"', '"Noto Sans SC"', 'sans-serif'],
                    }
                    @yield('tailwind_config_extend')
                }
            }
        }
    </script>
    <style>
        body { background-color: #080808; font-family: 'Inter', 'Noto Sans SC', sans-serif; }
        .font-serif { font-family: 'Playfair Display', 'Noto Serif SC', serif; }
        .grain::before {
            content: ''; position: fixed; inset: 0; z-index: 9999; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: 0.6;
        }
        @yield('styles')
    </style>
    @yield('ld_json')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7575258986593674" crossorigin="anonymous"></script>
</head>
<body class="grain text-[#e3ded5] min-h-screen">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#080808]/80 backdrop-blur-xl border-b border-[#262422]/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                @hasSection('nav_back')
                    @yield('nav_back')
                @endif
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <span class="font-serif text-2xl font-black tracking-tight text-[#e3ded5] group-hover:text-[#C9A96E] transition-colors">光影流年</span>
                </a>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium tracking-wide text-[#8b8478]">
                <a href="{{ url('/') }}" class="{{ ($activeNav ?? '') === 'home' ? 'text-[#C9A96E]' : 'hover:text-[#e3ded5]' }} transition-colors">首页</a>
                <a href="{{ url('/movies') }}" class="{{ ($activeNav ?? '') === 'movies' ? 'text-[#C9A96E]' : 'hover:text-[#e3ded5]' }} transition-colors">影视库</a>
                <a href="{{ url('/about') }}" class="{{ ($activeNav ?? '') === 'about' ? 'text-[#C9A96E]' : 'hover:text-[#e3ded5]' }} transition-colors">关于我们</a>
                <a href="{{ url('/contact') }}" class="{{ ($activeNav ?? '') === 'contact' ? 'text-[#C9A96E]' : 'hover:text-[#e3ded5]' }} transition-colors">联系我们</a>
            </div>
            <button id="menuBtn" class="md:hidden text-[#e3ded5] p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobileMenu" class="hidden md:hidden px-6 pb-4 space-y-3 text-sm text-[#8b8478]">
            <a href="{{ url('/') }}" class="block {{ ($activeNav ?? '') === 'home' ? 'text-[#C9A96E]' : '' }}">首页</a>
            <a href="{{ url('/movies') }}" class="block {{ ($activeNav ?? '') === 'movies' ? 'text-[#C9A96E]' : '' }}">影视库</a>
            <a href="{{ url('/about') }}" class="block {{ ($activeNav ?? '') === 'about' ? 'text-[#C9A96E]' : '' }}">关于我们</a>
            <a href="{{ url('/contact') }}" class="block {{ ($activeNav ?? '') === 'contact' ? 'text-[#C9A96E]' : '' }}">联系我们</a>
        </div>
    </nav>

    <script>
        document.getElementById('menuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>

    @yield('content')

    <footer class="border-t border-[#262422]/50 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <p class="font-serif text-2xl font-black text-[#e3ded5] mb-3">光影流年</p>
                    <p class="text-sm text-[#8b8478] leading-relaxed max-w-sm">每日精选全球高分影视资源，为你打造专属的光影世界。</p>
                </div>
                <div>
                    <p class="text-xs tracking-[0.15em] text-[#e3ded5] uppercase mb-4 font-semibold">导航</p>
                    <div class="space-y-2 text-sm text-[#8b8478]">
                        <a href="{{ url('/') }}" class="block hover:text-[#e3ded5] transition-colors">首页</a>
                        <a href="{{ url('/movies') }}" class="block hover:text-[#e3ded5] transition-colors">影视库</a>
                        <a href="{{ url('/about') }}" class="block hover:text-[#e3ded5] transition-colors">关于我们</a>
                        <a href="{{ url('/contact') }}" class="block hover:text-[#e3ded5] transition-colors">联系我们</a>
                    </div>
                </div>
                <div>
                    <p class="text-xs tracking-[0.15em] text-[#e3ded5] uppercase mb-4 font-semibold">联系</p>
                    <div class="space-y-2 text-sm text-[#8b8478]">
                        <p>contact@moviehub.com</p>
                        <p>关注公众号获取更多资源</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-[#262422]/30 pt-6 text-center text-xs text-[#8b8478]">
                &copy; {{ date('Y') }} 光影流年. All rights reserved.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
