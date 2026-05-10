@extends('layouts.app')

@php $activeNav = 'about'; @endphp

@section('title', '关于我们 - ' . config('app.name', '光影流年'))

@section('meta')
    <meta name="description" content="光影流年 - 你的私人影院，收录 {{ number_format($totalCount) }}+ 部优质影视作品，每日精选全球高分影视资源">
    <link rel="canonical" href="{{ url('/about') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="关于我们 - {{ config('app.name', '光影流年') }}">
    <meta property="og:description" content="光影流年 - 收录 {{ number_format($totalCount) }}+ 部优质影视作品，每日精选全球高分资源。海量资源、精选高分、极速体验、实时更新。">
    <meta property="og:url" content="{{ url('/about') }}">
    <meta property="og:site_name" content="{{ config('app.name', '光影流年') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="关于我们 - {{ config('app.name', '光影流年') }}">
    <meta name="twitter:description" content="光影流年 - 收录 {{ number_format($totalCount) }}+ 部优质影视作品，每日精选全球高分资源">
@endsection

@section('styles')
        .timeline-line { position: absolute; left: 23px; top: 0; bottom: 0; width: 1px; background: linear-gradient(to bottom, #C9A96E, transparent); }
@endsection

@section('ld_json')
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@graph": [
                {
                    "@@type": "AboutPage",
                    "name": "关于我们 - {{ config('app.name', '光影流年') }}",
                    "url": "{{ url('/about') }}",
                    "description": "光影流年致力于为影视爱好者打造一个纯粹、优质的内容发现平台",
                    "about": {
                        "@@type": "Organization",
                        "name": "{{ config('app.name', '光影流年') }}",
                        "url": "{{ url('/') }}",
                        "description": "每日精选全球高分影视资源"
                    }
                }
            ]
        }
    </script>
@endsection

@section('content')
    <section class="pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-4">About Us</p>
                <h1 class="font-serif text-4xl md:text-6xl font-black leading-tight mb-6">用光影<br>连接每一次心动</h1>
                <p class="text-[#8b8478] text-lg max-w-2xl mx-auto leading-relaxed">
                    我们相信每一部好电影都是一次灵魂的对话。光影流年致力于为影视爱好者打造一个纯粹、优质的内容发现平台。
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
                <div class="bg-[#141311] rounded-2xl border border-[#262422] p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#C9A96E]/10 flex items-center justify-center mb-5">
                        <svg class="w-8 h-8 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold mb-3">海量资源</h3>
                    <p class="text-sm text-[#8b8478] leading-relaxed">收录 {{ number_format($totalCount) }}+ 部优质影视作品，涵盖各类题材，每日持续更新，满足不同口味的观影需求。</p>
                </div>
                <div class="bg-[#141311] rounded-2xl border border-[#262422] p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#C9A96E]/10 flex items-center justify-center mb-5">
                        <svg class="w-8 h-8 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold mb-3">精选高分</h3>
                    <p class="text-sm text-[#8b8478] leading-relaxed">我们严格筛选高分影视作品，呈现豆瓣、IMDb 等权威评分，帮助你快速发现有品质的精彩内容。</p>
                </div>
                <div class="bg-[#141311] rounded-2xl border border-[#262422] p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#C9A96E]/10 flex items-center justify-center mb-5">
                        <svg class="w-8 h-8 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold mb-3">极速体验</h3>
                    <p class="text-sm text-[#8b8478] leading-relaxed">流畅的浏览体验，清晰的影视资料展示。提供多种网盘下载链接，一键获取你想要的资源。</p>
                </div>
                <div class="bg-[#141311] rounded-2xl border border-[#262422] p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#C9A96E]/10 flex items-center justify-center mb-5">
                        <svg class="w-8 h-8 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold mb-3">实时更新</h3>
                    <p class="text-sm text-[#8b8478] leading-relaxed">自动化采集系统每日定时更新，确保内容新鲜度。新上映、新资源，第一时间呈现给你。</p>
                </div>
            </div>

            <div class="relative">
                <div class="text-center mb-12">
                    <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-3">Our Journey</p>
                    <h2 class="font-serif text-3xl md:text-4xl font-bold">我们的历程</h2>
                </div>
                <div class="relative pl-[60px]">
                    <div class="timeline-line"></div>
                    <div class="space-y-10">
                        <div class="relative">
                            <div class="absolute left-[-60px] w-[46px] h-[46px] rounded-full bg-[#C9A96E]/20 border-2 border-[#C9A96E] flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6">
                                <span class="text-xs text-[#C9A96E] font-semibold">2024</span>
                                <h3 class="font-serif text-lg font-bold mt-1 mb-2">项目启动</h3>
                                <p class="text-sm text-[#8b8478] leading-relaxed">怀揣对电影的热爱，光影流年项目正式启动。从零开始搭建数据采集、存储和展示的全链路系统。</p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute left-[-60px] w-[46px] h-[46px] rounded-full bg-[#C9A96E]/20 border-2 border-[#C9A96E] flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6">
                                <span class="text-xs text-[#C9A96E] font-semibold">2025</span>
                                <h3 class="font-serif text-lg font-bold mt-1 mb-2">持续增长</h3>
                                <p class="text-sm text-[#8b8478] leading-relaxed">影视库收录 {{ number_format($totalCount) }}+ 部作品，完善分类筛选功能，优化用户体验。建立起稳定的自动化采集和更新机制。</p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute left-[-60px] w-[46px] h-[46px] rounded-full bg-[#C9A96E]/20 border-2 border-[#C9A96E] flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6">
                                <span class="text-xs text-[#C9A96E] font-semibold">2026</span>
                                <h3 class="font-serif text-lg font-bold mt-1 mb-2">全新启航</h3>
                                <p class="text-sm text-[#8b8478] leading-relaxed">全面焕新品牌视觉与用户体验。引入更智能的内容推荐算法，致力于成为最好的影视资源发现平台。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
