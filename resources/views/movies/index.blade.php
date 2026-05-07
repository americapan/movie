<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>影视库 - {{ config('app.name', '光影流年') }}</title>
    <meta name="description" content="浏览 {{ $totalCount }}+ 部精选影视作品">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/movies') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="影视库 - {{ config('app.name', '光影流年') }}">
    <meta property="og:description" content="浏览 {{ $totalCount }}+ 部精选影视作品，涵盖动作、喜剧、科幻、爱情等多种类型">
    <meta property="og:url" content="{{ url('/movies') }}">
    <meta property="og:site_name" content="{{ config('app.name', '光影流年') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="影视库 - {{ config('app.name', '光影流年') }}">
    <meta name="twitter:description" content="浏览 {{ $totalCount }}+ 部精选影视作品">
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
                }
            }
        }
    </script>
    <style>
        * { scroll-behavior: smooth; }
        body { background-color: #080808; font-family: 'Inter', 'Noto Sans SC', sans-serif; }
        .font-serif { font-family: 'Playfair Display', 'Noto Serif SC', serif; }
        .grain::before {
            content: ''; position: fixed; inset: 0; z-index: 9999; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: 0.6;
        }
        .card-hover { transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 60px -15px rgba(201,169,110,0.12); border-color: rgba(201,169,110,0.25); }
        .genre-active { color: #C9A96E; border-color: rgba(201,169,110,0.3); background: rgba(201,169,110,0.08); }
    </style>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "ItemList",
            "name": "影视库 - {{ config('app.name', '光影流年') }}",
            "url": "{{ url('/movies') }}",
            "numberOfItems": "{{ $movies->total() }}",
            "itemListElement": [
                @foreach($movies as $i => $item)
                {
                    "@type": "ListItem",
                    "position": "{{ $i + 1 }}",
                    "item": {
                        "@type": "Movie",
                        "name": "{{ $item->title }}",
                        "url": "{{ url('/movies/' . $item->id . '.html') }}"
                        @if($item->poster_url)
                        ,"image": "{{ $item->poster_url }}"
                        @endif
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    </script>
</head>
<body class="grain text-[#e3ded5] min-h-screen">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#080808]/80 backdrop-blur-xl border-b border-[#262422]/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <span class="font-serif text-2xl font-black tracking-tight text-[#e3ded5] group-hover:text-[#C9A96E] transition-colors">光影流年</span>
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium tracking-wide text-[#8b8478]">
                <a href="{{ url('/') }}" class="hover:text-[#e3ded5] transition-colors">首页</a>
                <a href="{{ url('/movies') }}" class="text-[#C9A96E]">影视库</a>
                <a href="{{ url('/about') }}" class="hover:text-[#e3ded5] transition-colors">关于我们</a>
                <a href="{{ url('/contact') }}" class="hover:text-[#e3ded5] transition-colors">联系我们</a>
            </div>
            <button id="menuBtn" class="md:hidden text-[#e3ded5] p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobileMenu" class="hidden md:hidden px-6 pb-4 space-y-3 text-sm text-[#8b8478]">
            <a href="{{ url('/') }}" class="block">首页</a>
            <a href="{{ url('/movies') }}" class="block text-[#C9A96E]">影视库</a>
            <a href="{{ url('/about') }}" class="block">关于我们</a>
            <a href="{{ url('/contact') }}" class="block">联系我们</a>
        </div>
    </nav>
    <script>
        document.getElementById('menuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>

    <section class="pt-24 pb-8">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-3">Movie Library</p>
                    <h1 class="font-serif text-4xl md:text-5xl font-bold tracking-tight">影视库</h1>
                    <p class="text-[#8b8478] mt-2">共收录 <span class="text-[#e3ded5] font-semibold">{{ number_format($totalCount) }}</span> 部作品，持续更新中</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b8478]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" placeholder="搜索影片..." class="bg-[#141311] border border-[#262422] rounded-xl pl-10 pr-4 py-2.5 text-sm text-[#e3ded5] placeholder-[#8b8478] focus:outline-none focus:border-[#C9A96E]/50 transition-colors w-48">
                    </div>
                </div>
            </div>

            @if($genres->count())
            <div class="flex flex-wrap gap-2 mb-4 pb-6 border-b border-[#262422]/50">
                @foreach($genres as $genre)
                <a href="?genre={{ urlencode($genre) }}" class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('genre') === $genre ? 'border-[#C9A96E] text-[#C9A96E] bg-[#C9A96E]/10' : 'border-[#262422] text-[#8b8478] hover:border-[#C9A96E]/50 hover:text-[#e3ded5] cursor-pointer' }}">{{ $genre }}</a>
                @endforeach
            </div>
            @if(request('genre'))
            <div class="mb-8">
                <a href="?" class="inline-flex items-center gap-1 text-xs text-[#8b8478] hover:text-[#e3ded5] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    清除筛选
                </a>
            </div>
            @endif
            @endif
        </div>
    </section>

    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-6">
            @if($movies->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($movies as $movie)
                <a href="{{ url('/movies/' . $movie->id . '.html') }}" class="card-hover bg-[#141311] rounded-2xl overflow-hidden border border-[#262422] group">
                    <div class="aspect-[3/4] bg-[#1a1816] overflow-hidden relative">
                        @if($movie->poster_url)
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" referrerpolicy="no-referrer" onerror="this.style.display='none'">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-[#262422]">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#080808]/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @if($movie->douban_rating)
                        <div class="absolute top-3 right-3 bg-[#C9A96E] text-[#080808] text-xs font-bold px-2 py-1 rounded-lg shadow-lg">
                            {{ number_format($movie->douban_rating, 1) }}
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h2 class="font-medium text-sm line-clamp-2 leading-snug mb-2 group-hover:text-[#C9A96E] transition-colors">{{ $movie->title }}</h2>
                        <div class="flex items-center gap-2 text-xs text-[#8b8478]">
                            @if($movie->publish_date)
                            <span>{{ $movie->publish_date->format('Y') }}</span>
                            @endif
                            @if($movie->imdb_rating)
                            <span class="text-[#C9A96E]">IMDB {{ number_format($movie->imdb_rating, 1) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div id="noResults" class="hidden text-center py-20">
                <svg class="w-16 h-16 mx-auto text-[#262422] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-[#8b8478] text-lg">未找到相关影片</p>
                <p class="text-[#8b8478]/60 text-sm mt-1">请尝试其他关键词</p>
            </div>

            <div class="mt-12 flex justify-center">
                <div class="flex gap-2">
                    {{ $movies->onEachSide(1)->links('pagination.default') }}
                </div>
            </div>
            @else
            <div class="text-center py-20">
                <svg class="w-16 h-16 mx-auto text-[#262422] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                <p class="text-[#8b8478] text-lg">暂无影片数据</p>
                <p class="text-[#8b8478]/60 text-sm mt-1">影片正在采集中，请稍后访问</p>
            </div>
            @endif
        </div>
    </section>

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

    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            let hasVisible = false;
            document.querySelectorAll('.card-hover').forEach(function(card) {
                const title = (card.querySelector('h2') || {}).textContent || '';
                if (title.toLowerCase().includes(query)) {
                    card.style.display = '';
                    hasVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });
            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.classList.toggle('hidden', hasVisible || query === '');
            }
        });
    </script>
</body>
</html>
