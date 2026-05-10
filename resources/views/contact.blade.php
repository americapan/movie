@extends('layouts.app')

@php $activeNav = 'contact'; @endphp

@section('title', '联系我们 - ' . config('app.name', '光影流年'))

@section('meta')
    <meta name="description" content="联系光影流年团队，获取帮助与支持。提供电子邮件、微信公众号、在线反馈等多种联系方式，工作日24小时内回复。">
    <link rel="canonical" href="{{ url('/contact') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="联系我们 - {{ config('app.name', '光影流年') }}">
    <meta property="og:description" content="联系光影流年团队，获取帮助与支持。有任何问题、建议或合作意向，我们随时欢迎你的来信。">
    <meta property="og:url" content="{{ url('/contact') }}">
    <meta property="og:site_name" content="{{ config('app.name', '光影流年') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="联系我们 - {{ config('app.name', '光影流年') }}">
    <meta name="twitter:description" content="联系光影流年团队，获取帮助与支持">
@endsection

@section('styles')
        .input-field {
            background: #141311; border: 1px solid #262422; transition: border-color 0.3s, box-shadow 0.3s;
        }
        .input-field:focus {
            outline: none; border-color: rgba(201,169,110,0.5); box-shadow: 0 0 0 3px rgba(201,169,110,0.06);
        }
@endsection

@section('ld_json')
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "ContactPage",
                    "name": "联系我们 - {{ config('app.name', '光影流年') }}",
                    "url": "{{ url('/contact') }}",
                    "description": "联系光影流年团队，获取帮助与支持"
                },
                {
                    "@type": "Organization",
                    "name": "{{ config('app.name', '光影流年') }}",
                    "url": "{{ url('/') }}",
                    "contactPoint": {
                        "@type": "ContactPoint",
                        "contactType": "customer service",
                        "email": "contact@moviehub.com"
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
                <p class="text-xs tracking-[0.2em] text-[#C9A96E] uppercase mb-4">Get In Touch</p>
                <h1 class="font-serif text-4xl md:text-6xl font-black leading-tight mb-6">期待与你<br>相遇</h1>
                <p class="text-[#8b8478] text-lg max-w-xl mx-auto leading-relaxed">
                    有任何问题、建议或合作意向？我们随时欢迎你的来信。期待与你共同打造更好的观影体验。
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
                <div class="lg:col-span-3">
                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-8">
                        <h2 class="font-serif text-2xl font-bold mb-8 border-b border-[#262422]/50 pb-4">发送消息</h2>
                        <form id="contactForm" class="space-y-5" onsubmit="return validateContactForm(event)">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs text-[#8b8478] mb-2 ml-1 font-medium">姓名</label>
                                    <input id="contactName" type="text" class="input-field w-full rounded-xl px-4 py-3 text-sm text-[#e3ded5] placeholder-[#524e47]" placeholder="你的名字">
                                </div>
                                <div>
                                    <label class="block text-xs text-[#8b8478] mb-2 ml-1 font-medium">邮箱</label>
                                    <input id="contactEmail" type="email" class="input-field w-full rounded-xl px-4 py-3 text-sm text-[#e3ded5] placeholder-[#524e47]" placeholder="your@email.com">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-[#8b8478] mb-2 ml-1 font-medium">主题</label>
                                <input type="text" class="input-field w-full rounded-xl px-4 py-3 text-sm text-[#e3ded5] placeholder-[#524e47]" placeholder="消息主题">
                            </div>
                            <div>
                                <label class="block text-xs text-[#8b8478] mb-2 ml-1 font-medium">消息内容</label>
                                <textarea id="contactMessage" class="input-field w-full rounded-xl px-4 py-3 text-sm text-[#e3ded5] placeholder-[#524e47] resize-none h-36" placeholder="请描述你想告诉我们的内容..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-[#C9A96E] hover:bg-[#B8943F] text-[#080808] py-3.5 rounded-xl font-semibold text-sm tracking-wide transition-all">
                                发送消息
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-5">
                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-[#C9A96E]/10 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold mb-1">电子邮件</h3>
                            <p class="text-sm text-[#8b8478]">contact@moviehub.com</p>
                            <p class="text-xs text-[#8b8478]/60 mt-0.5">工作日 24 小时内回复</p>
                        </div>
                    </div>

                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-[#C9A96E]/10 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.858 15.355-5.858 21.213 0"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold mb-1">微信公众号</h3>
                            <p class="text-sm text-[#8b8478]">光影流年</p>
                            <p class="text-xs text-[#8b8478]/60 mt-0.5">扫码关注，获取每日影视推荐</p>
                        </div>
                    </div>

                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-[#C9A96E]/10 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-[#C9A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold mb-1">在线反馈</h3>
                            <p class="text-sm text-[#8b8478]">通过以上表单提交</p>
                            <p class="text-xs text-[#8b8478]/60 mt-0.5">描述你的问题，我们会尽快处理</p>
                        </div>
                    </div>

                    <div class="bg-[#141311] rounded-2xl border border-[#262422] p-6">
                        <h3 class="text-sm font-semibold mb-4">常见问题</h3>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="text-sm text-[#8b8478] cursor-pointer hover:text-[#e3ded5] transition-colors list-none flex items-center justify-between">
                                    如何查找特定影片？
                                    <svg class="w-4 h-4 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </summary>
                                <p class="text-xs text-[#8b8478]/70 mt-2 leading-relaxed">您可以在影视库页面使用搜索框，或通过类型标签筛选来查找感兴趣的影片。</p>
                            </details>
                            <details class="group">
                                <summary class="text-sm text-[#8b8478] cursor-pointer hover:text-[#e3ded5] transition-colors list-none flex items-center justify-between">
                                    资源多久更新一次？
                                    <svg class="w-4 h-4 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </summary>
                                <p class="text-xs text-[#8b8478]/70 mt-2 leading-relaxed">我们的系统每天凌晨自动采集最新影视资源，确保内容的时效性。</p>
                            </details>
                            <details class="group">
                                <summary class="text-sm text-[#8b8478] cursor-pointer hover:text-[#e3ded5] transition-colors list-none flex items-center justify-between">
                                    资源链接失效了怎么办？
                                    <svg class="w-4 h-4 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </summary>
                                <p class="text-xs text-[#8b8478]/70 mt-2 leading-relaxed">请通过右侧的联系方式告知我们，我们会尽快更新有效链接。</p>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
