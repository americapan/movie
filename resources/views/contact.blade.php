<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>联系我们 - {{ config('app.name', '光影流年') }}</title>
    <meta name="description" content="联系光影流年团队，获取帮助与支持。提供电子邮件、微信公众号、在线反馈等多种联系方式，工作日24小时内回复。">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/contact') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="联系我们 - {{ config('app.name', '光影流年') }}">
    <meta property="og:description" content="联系光影流年团队，获取帮助与支持。有任何问题、建议或合作意向，我们随时欢迎你的来信。">
    <meta property="og:url" content="{{ url('/contact') }}">
    <meta property="og:site_name" content="{{ config('app.name', '光影流年') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="联系我们 - {{ config('app.name', '光影流年') }}">
    <meta name="twitter:description" content="联系光影流年团队，获取帮助与支持">
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
        body { background-color: #080808; font-family: 'Inter', 'Noto Sans SC', sans-serif; }
        .font-serif { font-family: 'Playfair Display', 'Noto Serif SC', serif; }
        .grain::before {
            content: ''; position: fixed; inset: 0; z-index: 9999; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: 0.6;
        }
        .input-field {
            background: #141311; border: 1px solid #262422; transition: border-color 0.3s, box-shadow 0.3s;
        }
        .input-field:focus {
            outline: none; border-color: rgba(201,169,110,0.5); box-shadow: 0 0 0 3px rgba(201,169,110,0.06);
        }
    </style>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
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
</head>
<body class="grain text-[#e3ded5] min-h-screen">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#080808]/80 backdrop-blur-xl border-b border-[#262422]/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <span class="font-serif text-2xl font-black tracking-tight text-[#e3ded5] group-hover:text-[#C9A96E] transition-colors">光影流年</span>
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium tracking-wide text-[#8b8478]">
                <a href="{{ url('/') }}" class="hover:text-[#e3ded5] transition-colors">首页</a>
                <a href="{{ url('/movies') }}" class="hover:text-[#e3ded5] transition-colors">影视库</a>
                <a href="{{ url('/about') }}" class="hover:text-[#e3ded5] transition-colors">关于我们</a>
                <a href="{{ url('/contact') }}" class="text-[#C9A96E]">联系我们</a>
            </div>
            <button id="menuBtn" class="md:hidden text-[#e3ded5] p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobileMenu" class="hidden md:hidden px-6 pb-4 space-y-3 text-sm text-[#8b8478]">
            <a href="{{ url('/') }}" class="block">首页</a>
            <a href="{{ url('/movies') }}" class="block">影视库</a>
            <a href="{{ url('/about') }}" class="block">关于我们</a>
            <a href="{{ url('/contact') }}" class="block text-[#C9A96E]">联系我们</a>
        </div>
    </nav>
    <script>
        document.getElementById('menuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>

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
                                <p class="text-xs text-[#8b8478]/70 mt-2 leading-relaxed">如遇链接失效，请通过联系表单告知我们，我们会尽快更新有效链接。</p>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
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
        function validateContactForm(event) {
            const name = document.getElementById('contactName');
            const email = document.getElementById('contactEmail');
            const message = document.getElementById('contactMessage');

            document.querySelectorAll('.field-error').forEach(el => el.remove());
            [name, email, message].forEach(el => el.classList.remove('border-red-500'));

            let valid = true;

            if (!name.value.trim()) {
                showError(name, '请输入姓名');
                valid = false;
            }

            if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                showError(email, '请输入有效的邮箱地址');
                valid = false;
            }

            if (!message.value.trim()) {
                showError(message, '请输入消息内容');
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
                return false;
            }

            alert('消息已收到！我们会尽快回复。');
            return true;
        }

        function showError(el, msg) {
            el.classList.add('border-red-500');
            const error = document.createElement('p');
            error.className = 'field-error text-red-500 text-xs mt-1';
            error.textContent = msg;
            el.parentNode.appendChild(error);
        }
    </script>
</body>
</html>
