@if ($paginator->hasPages())
    <div class="paging-btns">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            {{-- <a class="disabled" aria-disabled="true"><span>&laquo;</span></a> --}}
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
        @endif

        {{-- First Page --}}
        @if ($paginator->currentPage() > 2)
            <a href="{{ $paginator->url(1) }}">1</a>
            @if ($paginator->currentPage() > 3)
                <span class="dots">...</span>
            @endif
        @endif

        {{-- Current Page and Adjacent --}}
        @if ($paginator->currentPage() > 1)
            <a href="{{ $paginator->url($paginator->currentPage() - 1) }}">{{ $paginator->currentPage() - 1 }}</a>
        @endif

        <a class="active" aria-current="page">{{ $paginator->currentPage() }}</a>

        @if ($paginator->currentPage() < $paginator->lastPage())
            <a href="{{ $paginator->url($paginator->currentPage() + 1) }}">{{ $paginator->currentPage() + 1 }}</a>
        @endif

        {{-- Last Page --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 1)
            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                <span class="dots">...</span>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
        @else
            {{-- <a class="disabled" aria-disabled="true"><span>&raquo;</span></a> --}}
        @endif
    </div>
@endif
