@if ($paginator->hasPages())
<nav role="navigation" class="flex items-center gap-1">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#262422] text-[#524e47] cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#262422] text-[#8b8478] hover:border-[#C9A96E]/50 hover:text-[#e3ded5] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="w-10 h-10 flex items-center justify-center text-[#524e47] text-sm">...</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#C9A96E] text-[#080808] text-sm font-semibold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#262422] text-[#8b8478] text-sm hover:border-[#C9A96E]/50 hover:text-[#e3ded5] transition-all">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#262422] text-[#8b8478] hover:border-[#C9A96E]/50 hover:text-[#e3ded5] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    @else
        <span class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#262422] text-[#524e47] cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    @endif
</nav>
@endif
