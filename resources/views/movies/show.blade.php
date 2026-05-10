@extends('layouts.app')

@php $activeNav = 'movies'; @endphp

@section('title', $movie->title . ' - ' . config('app.name', '光影流年'))

@section('meta')
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($movie->detail?->synopsis ?? $movie->title, 150) }}">
    <link rel="canonical" href="{{ url('/movies/' . $movie->id . '.html') }}">
    <meta property="og:type" content="video.movie">
    <meta property="og:title" content="{{ $movie->title }} - {{ config('app.name', '光影流年') }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($movie->detail?->synopsis ?? $movie->title, 150) }}">
    <meta property="og:url" content="{{ url('/movies/' . $movie->id . '.html') }}">
    @if($movie->poster_url)
    <meta property="og:image" content="{{ $movie->poster_url }}">
    @endif
    <meta property="og:site_name" content="{{ config('app.name', '光影流年') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $movie->title }} - {{ config('app.name', '光影流年') }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($movie->detail?->synopsis ?? $movie->title, 150) }}">
    @if($movie->poster_url)
    <meta name="twitter:image" content="{{ $movie->poster_url }}">
    @endif
@endsection

@section('nav_back')
    <a href="{{ url('/movies') }}" class="text-[#8b8478] hover:text-[#e3ded5] transition-colors flex items-center gap-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
@endsection

@section('styles')
        .hero-poster { box-shadow: 0 40px 100px -20px rgba(201,169,110,0.15); }
        .resource-link:hover { background: rgba(201,169,110,0.08); border-color: rgba(201,169,110,0.3); }
        .card-hover { transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .card-hover:hover { transform: translateY(-4px); border-color: rgba(201,169,110,0.25); }
@endsection

@section('ld_json')
    @php
    $ldJson = [
        '@context' => 'https://schema.org',
        '@type' => 'Movie',
        'name' => $movie->title,
        'url' => url('/movies/' . $movie->id . '.html'),
    ];
    if ($movie->poster_url) $ldJson['image'] = $movie->poster_url;
    if ($movie->detail?->director) $ldJson['director'] = ['@type' => 'Person', 'name' => $movie->detail->director];
    if ($movie->detail?->synopsis) $ldJson['description'] = \Illuminate\Support\Str::limit($movie->detail->synopsis, 200);
    if ($movie->detail?->genre) {
        $genres = array_values(array_filter(array_map('trim', explode(' ', str_replace(['/', '|', ','], ' ', $movie->detail->genre)))));
        if ($genres) $ldJson['genre'] = $genres;
    }
    if ($movie->detail?->language) $ldJson['inLanguage'] = $movie->detail->language;
    if ($movie->detail?->country) $ldJson['countryOfOrigin'] = ['@type' => 'Country', 'name' => $movie->detail->country];
    if ($movie->detail?->release_date) $ldJson['datePublished'] = $movie->detail->release_date;
    if ($movie->detail?->casts) {
        $casts = array_slice(array_map('trim', explode(',', $movie->detail->casts)), 0, 5);
        if ($casts) $ldJson['actor'] = array_map(fn($c) => ['@type' => 'Person', 'name' => $c], $casts);
    }
    if ($movie->douban_rating) $ldJson['aggregateRating'] = ['@type' => 'AggregateRating', 'ratingValue' => (string)number_format($movie->douban_rating, 1), 'bestRating' => '10'];
    @endphp
    <script type="application/ld+json">{!! json_encode($ldJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_APOS) !!}</script>
@endsection

@section('content')
    <section class="pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-16">
                <div class="lg:w-[340px] shrink-0">
                    <div class="hero-poster sticky top-24 rounded-2xl overflow-hidden bg-[#141311]">
                        <div class="aspect-[2/3] bg-[#1a1816] overflow-hidden">
                            @if($movie->poster_url)
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover" referrerpolicy="no-referrer" onerror="this.style.display='none'">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-[#262422]">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        @if($movie->douban_rating)
                        <div class="inline-flex items-center gap-2 bg-[#141311] border border-[#262422] rounded-xl px-4 py-2.5">
                            <span class="text-xs text-[#8b8478]">豆瓣评分</span>
                            <span class="text-[#C9A96E] font-bold text-lg">{{ number_format($movie->douban_rating, 1) }}</span>
                        </div>
                        @endif
                        @if($movie->imdb_rating)
                        <div class="inline-flex items-center gap-2 bg-[#141311] border border-[#262422] rounded-xl px-4 py-2.5">
                            <span class="text-xs text-[#8b8478]">IMDb</span>
                            <span class="text-[#C9A96E] font-bold text-lg">{{ number_format($movie->imdb_rating, 1) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex-1 min-w-0 space-y-8">
                    <div>
                        <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-2">Movie Detail</p>
                        <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">{{ $movie->title }}</h1>
                        @if($movie->publish_date)
                        <p class="text-[#8b8478] mt-2">{{ $movie->publish_date->format('Y-m-d') }}</p>
                        @endif
                    </div>

                    @if($movie->detail && ($movie->detail->director || $movie->detail->casts || $movie->detail->genre))
                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6">
                        <h2 class="font-serif text-xl font-bold mb-5 border-b border-[#262422]/50 pb-3">基本信息</h2>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                            @if($movie->detail->director)
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-[#8b8478] mb-1">导演</dt>
                                <dd class="text-sm font-medium">{{ $movie->detail->director }}</dd>
                            </div>
                            @endif
                            @if($movie->detail->writers)
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-[#8b8478] mb-1">编剧</dt>
                                <dd class="text-sm">{{ $movie->detail->writers }}</dd>
                            </div>
                            @endif
                            @if($movie->detail->casts)
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-[#8b8478] mb-1">主演</dt>
                                <dd class="text-sm leading-relaxed">{{ $movie->detail->casts }}</dd>
                            </div>
                            @endif
                            @if($movie->detail->genre)
                            <div>
                                <dt class="text-xs text-[#8b8478] mb-1">类型</dt>
                                <dd class="text-sm">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(explode(' ', str_replace(['/', '|', ','], ' ', $movie->detail->genre)) as $g)
                                        @if(trim($g))
                                        <span class="px-2 py-0.5 rounded-full border border-[#262422] text-xs text-[#8b8478]">{{ trim($g) }}</span>
                                        @endif
                                        @endforeach
                                    </div>
                                </dd>
                            </div>
                            @endif
                            @if($movie->detail->country)
                            <div>
                                <dt class="text-xs text-[#8b8478] mb-1">制片国家/地区</dt>
                                <dd class="text-sm">{{ $movie->detail->country }}</dd>
                            </div>
                            @endif
                            @if($movie->detail->language)
                            <div>
                                <dt class="text-xs text-[#8b8478] mb-1">语言</dt>
                                <dd class="text-sm">{{ $movie->detail->language }}</dd>
                            </div>
                            @endif
                            @if($movie->detail->release_date)
                            <div>
                                <dt class="text-xs text-[#8b8478] mb-1">上映日期</dt>
                                <dd class="text-sm">{{ $movie->detail->release_date }}</dd>
                            </div>
                            @endif
                            @if($movie->detail->runtime)
                            <div>
                                <dt class="text-xs text-[#8b8478] mb-1">片长</dt>
                                <dd class="text-sm">{{ $movie->detail->runtime }}</dd>
                            </div>
                            @endif
                            @if($movie->detail->imdb_id)
                            <div>
                                <dt class="text-xs text-[#8b8478] mb-1">IMDb ID</dt>
                                <dd class="text-sm font-mono">{{ $movie->detail->imdb_id }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif

                    @if($movie->detail?->synopsis)
                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6">
                        <h2 class="font-serif text-xl font-bold mb-4 border-b border-[#262422]/50 pb-3">剧情简介</h2>
                        <p class="text-sm leading-relaxed text-[#c5c0b6] whitespace-pre-line">{{ $movie->detail->synopsis }}</p>
                    </div>
                    @endif

                    @if($movie->detail?->download_resources && count($movie->detail->download_resources) > 0)
                    @php
                        $hasValidUrl = false;
                        foreach($movie->detail->download_resources as $resource) {
                            $u = is_array($resource) ? ($resource['url'] ?? '#') : $resource;
                            if ($u && $u !== '#') { $hasValidUrl = true; break; }
                        }
                    @endphp
                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6">
                        <h2 class="font-serif text-xl font-bold mb-5 border-b border-[#262422]/50 pb-3">下载资源</h2>
                        @if(!$hasValidUrl)
                        <p class="text-sm text-[#8b8478] py-4">所有下载资源暂未更新地址，请稍后再来</p>
                        @endif
                        <div class="space-y-2">
                            @foreach($movie->detail->download_resources as $resource)
                            @php
                                $name = is_array($resource) ? ($resource['name'] ?? '下载链接') : $resource;
                                $url = is_array($resource) ? ($resource['url'] ?? '#') : $resource;
                            @endphp
                            @if($url && $url !== '#')
                            <a href="{{ $url }}" target="_blank" rel="nofollow noopener" class="flex items-center gap-3 bg-[#1a1816] rounded-xl px-5 py-4 transition-all border border-[#262422] group active:scale-[0.98]">
                                <span class="text-sm font-medium group-hover:text-[#C9A96E] transition-colors flex-1">{{ $name }}</span>
                                <svg class="w-4 h-4 text-[#8b8478] group-hover:text-[#C9A96E] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            @else
                            <span class="flex items-center gap-3 bg-[#1a1816] rounded-xl px-5 py-4 border border-[#262422] opacity-50 cursor-not-allowed">
                                <span class="text-sm font-medium text-[#8b8478] flex-1">{{ $name }}（暂无下载地址）</span>
                            </span>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($related->count())
    <section class="py-16 bg-[#0c0c0a] border-t border-[#262422]/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-2">You May Also Like</p>
                <h2 class="font-serif text-3xl font-bold">相关推荐</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($related as $item)
                <a href="{{ url('/movies/' . $item->id . '.html') }}" class="card-hover bg-[#141311] rounded-xl overflow-hidden border border-[#262422] group">
                    <div class="aspect-[3/4] bg-[#1a1816] overflow-hidden">
                        @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" referrerpolicy="no-referrer" onerror="this.style.display='none'">
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="text-xs font-medium line-clamp-1 group-hover:text-[#C9A96E] transition-colors">{{ $item->title }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
