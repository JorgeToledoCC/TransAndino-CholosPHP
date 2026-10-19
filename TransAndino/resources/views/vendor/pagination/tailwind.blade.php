@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginación" class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-stone-500">
            Mostrando
            <span class="font-semibold text-vino-800">{{ $paginator->firstItem() }}</span>
            a
            <span class="font-semibold text-vino-800">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-semibold text-vino-800">{{ $paginator->total() }}</span>
            resultados
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="grid h-9 w-9 place-items-center rounded-lg border border-stone-200 text-stone-300" aria-disabled="true">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="grid h-9 w-9 place-items-center rounded-lg border border-stone-200 text-vino-700 transition hover:bg-vino-50">&lsaquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="grid h-9 w-9 place-items-center text-stone-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="grid h-9 w-9 place-items-center rounded-lg bg-vino-700 font-semibold text-white shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="grid h-9 w-9 place-items-center rounded-lg border border-stone-200 text-stone-600 transition hover:bg-vino-50">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="grid h-9 w-9 place-items-center rounded-lg border border-stone-200 text-vino-700 transition hover:bg-vino-50">&rsaquo;</a>
            @else
                <span class="grid h-9 w-9 place-items-center rounded-lg border border-stone-200 text-stone-300" aria-disabled="true">&rsaquo;</span>
            @endif
        </div>
    </nav>
@endif
