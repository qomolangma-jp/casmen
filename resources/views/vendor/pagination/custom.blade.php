@if ($paginator->hasPages())
    <div class="paging-btns">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            {{-- <span class="disabled" aria-disabled="true">&laquo;</span> --}}
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="active" href="#">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
            {{-- <span class="disabled" aria-disabled="true">&raquo;</span> --}}
        @endif
    </div>
@endif
