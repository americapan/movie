@extends('layouts.app')

@php $activeNav = 'home'; @endphp

@section('title', config('app.name', '光影流年') . ' - 发现好电影')

@section('meta')
    <meta name="description" content="每日精选高分影视资源，发现属于你的光影时刻">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name', '光影流年') }} - 发现好电影">
    <meta property="og:description" content="每日精选高分影视资源，发现属于你的光影时刻">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="{{ config('app.name', '光影流年') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name', '光影流年') }} - 发现好电影">
    <meta name="twitter:description" content="每日精选高分影视资源，发现属于你的光影时刻">
@endsection

@section('tailwind_config_extend')
, colors: { gold: { 400: '#C9A96E', 500: '#B8943F', 600: '#9A7B2F' }, cinema: { bg: '#080808', surface: '#141311', border: '#262422', text: '#e3ded5', muted: '#8b8478' } }
@endsection

@section('styles')
    .hero-gradient { background: radial-gradient(ellipse 70% 60% at 50% 30%, rgba(201,169,110,0.08) 0%, transparent 60%); }
    .card-hover { transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 60px -15px rgba(201,169,110,0.12); border-color: rgba(201,169,110,0.25); }
    .text-stroke { -webkit-text-stroke: 1px rgba(201,169,110,0.2); }
    .reveal { animation: reveal 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both; }
    @@keyframes reveal { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .reveal-1 { animation-delay: 0.1s; } .reveal-2 { animation-delay: 0.2s; } .reveal-3 { animation-delay: 0.3s; } .reveal-4 { animation-delay: 0.4s; } .reveal-5 { animation-delay: 0.5s; }
@endsection

@section('ld_json')
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@graph": [
                {
                    "@@type": "WebSite",
                    "name": "{{ config('app.name', '光影流年') }}",
                    "url": "{{ url('/') }}",
                    "description": "每日精选高分影视资源，发现属于你的光影时刻",
                    "potentialAction": {
                        "@@type": "SearchAction",
                        "target": {
                            "@@type": "EntryPoint",
                            "urlTemplate": "{{ url('/movies') }}?search={search_term_string}"
                        },
                        "query-input": "required name=search_term_string"
                    }
                },
                {
                    "@@type": "Organization",
                    "name": "{{ config('app.name', '光影流年') }}",
                    "url": "{{ url('/') }}",
                    "description": "每日精选全球高分影视资源，致力于为影视爱好者打造优质的内容发现平台"
                }
            ]
        }
    </script>
@endsection

@section('content')
<section class="relative pt-24 pb-16 overflow-hidden hero-gradient">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center min-h-[70vh]">
            <div class="space-y-8 reveal">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-[#C9A96E]/20 bg-[#C9A96E]/5 text-[#C9A96E] text-xs tracking-[0.2em] uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C9A96E] animate-pulse"></span>
                    {{ $totalCount }}+ 部精选影片
                </div>
                <h1 class="font-serif text-5xl md:text-7xl font-black leading-[0.95] tracking-tight">
                    <span class="block text-[#e3ded5]">每一帧</span>
                    <span class="block text-[#C9A96E] italic">都是故事</span>
                </h1>
                <p class="text-lg text-[#8b8478] leading-relaxed max-w-lg">
                    从经典到新锐，精心收录全球高分影视作品。每日更新，为你打开光影世界的大门。
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ url('/movies') }}" class="inline-flex items-center gap-2 bg-[#C9A96E] hover:bg-[#B8943F] text-[#080808] px-8 py-3.5 rounded-full font-semibold text-sm tracking-wide transition-all">
                        探索影视库
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ url('/about') }}" class="inline-flex items-center gap-2 border border-[#262422] hover:border-[#C9A96E]/50 text-[#e3ded5] px-8 py-3.5 rounded-full font-medium text-sm tracking-wide transition-all">
                        了解更多
                    </a>
                </div>
            </div>
            <div class="relative reveal reveal-2">
                <div class="grid grid-cols-2 gap-3">
                    @foreach($heroMovies as $index => $movie)
                    <a href="{{ url('/movies/' . $movie->id . '.html') }}" class="relative overflow-hidden rounded-2xl {{ $index === 0 ? 'col-span-2 row-span-2' : '' }} group">
                        <div class="aspect-[3/4] bg-[#141311] overflow-hidden">
                            @if($movie->poster_url)
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" referrerpolicy="no-referrer" onerror="this.style.display='none'">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#080808] via-transparent to-transparent opacity-80"></div>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-sm font-semibold text-white line-clamp-2 leading-tight">{{ $movie->title }}</h3>
                            @if($movie->douban_rating)
                            <span class="inline-flex items-center gap-1 mt-1.5 text-xs text-[#C9A96E]">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ number_format($movie->douban_rating, 1) }}
                            </span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<div class="border-t border-[#262422]/50"></div>

@if($latestMovies->count())
<section class="py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10 reveal">
            <div>
                <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-3">Latest Arrivals</p>
                <h2 class="font-serif text-4xl md:text-5xl font-bold tracking-tight">最新收录</h2>
            </div>
            <a href="{{ url('/movies') }}" class="hidden sm:inline-flex items-center gap-2 text-sm text-[#8b8478] hover:text-[#C9A96E] transition-colors group">
                查看全部
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($latestMovies as $movie)
            <a href="{{ url('/movies/' . $movie->id . '.html') }}" class="card-hover bg-[#141311] rounded-2xl overflow-hidden border border-[#262422] group">
                <div class="aspect-[3/4] bg-[#1a1816] overflow-hidden relative">
                    @if($movie->poster_url)
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" referrerpolicy="no-referrer" onerror="this.style.display='none'">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-[#262422]">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>
                    </div>
                    @endif
                    @if($movie->douban_rating)
                    <div class="absolute top-3 right-3 bg-[#C9A96E] text-[#080808] text-xs font-bold px-2 py-1 rounded-lg">
                        {{ number_format($movie->douban_rating, 1) }}
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-sm line-clamp-2 leading-snug mb-1 group-hover:text-[#C9A96E] transition-colors">{{ $movie->title }}</h3>
                    @if($movie->publish_date)
                    <p class="text-xs text-[#8b8478]">{{ $movie->publish_date->format('Y') }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($topRated->count())
<section class="py-20 bg-[#0c0c0a]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10 reveal">
            <div>
                <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-3">Top Rated</p>
                <h2 class="font-serif text-4xl md:text-5xl font-bold tracking-tight">高分推荐</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($topRated as $movie)
            <a href="{{ url('/movies/' . $movie->id . '.html') }}" class="card-hover bg-[#141311] rounded-2xl overflow-hidden border border-[#262422] group">
                <div class="aspect-[3/4] bg-[#1a1816] overflow-hidden relative">
                    @if($movie->poster_url)
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" referrerpolicy="no-referrer" onerror="this.style.display='none'">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[#080808]/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                        <div class="flex items-center gap-1 text-[#C9A96E] text-xs">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="font-bold">{{ number_format($movie->douban_rating, 1) }}</span>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <h3 class="text-xs font-medium line-clamp-1 group-hover:text-[#C9A96E] transition-colors">{{ $movie->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($recentAdded->count())
<section class="py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-10 reveal">
            <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-3">Recently Added</p>
            <h2 class="font-serif text-4xl md:text-5xl font-bold tracking-tight">持续更新</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
            @foreach($recentAdded as $movie)
            <a href="{{ url('/movies/' . $movie->id . '.html') }}" class="card-hover bg-[#141311] rounded-2xl overflow-hidden border border-[#262422] group flex items-center gap-4 p-3">
                <div class="w-16 h-20 rounded-xl overflow-hidden bg-[#1a1816] shrink-0">
                    @if($movie->poster_url)
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover" loading="lazy" referrerpolicy="no-referrer" onerror="this.style.display='none'">
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-medium line-clamp-2 leading-snug group-hover:text-[#C9A96E] transition-colors">{{ $movie->title }}</h3>
                    <div class="flex items-center gap-2 mt-1">
                        @if($movie->douban_rating)
                        <span class="text-xs text-[#C9A96E] font-semibold">{{ number_format($movie->douban_rating, 1) }}</span>
                        @endif
                        @if($movie->publish_date)
                        <span class="text-xs text-[#8b8478]">{{ $movie->publish_date->format('Y-m-d') }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-20 bg-[#0c0c0a] border-t border-[#262422]/50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center reveal">
            <div>
                <p class="font-serif text-4xl md:text-5xl font-black text-[#C9A96E]">{{ $totalCount }}+</p>
                <p class="text-xs text-[#8b8478] mt-2 tracking-wide">精选影片</p>
            </div>
            <div>
                <p class="font-serif text-4xl md:text-5xl font-black text-[#e3ded5]">4K</p>
                <p class="text-xs text-[#8b8478] mt-2 tracking-wide">高清画质</p>
            </div>
            <div>
                <p class="font-serif text-4xl md:text-5xl font-black text-[#e3ded5]">24H</p>
                <p class="text-xs text-[#8b8478] mt-2 tracking-wide">每日更新</p>
            </div>
            <div>
                <p class="font-serif text-4xl md:text-5xl font-black text-[#e3ded5]">∞</p>
                <p class="text-xs text-[#8b8478] mt-2 tracking-wide">网盘资源</p>
            </div>
        </div>
    </div>
</section>
@endsection
