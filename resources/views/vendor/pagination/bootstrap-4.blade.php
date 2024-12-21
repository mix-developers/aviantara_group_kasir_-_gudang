@php
    $currentPage = $paginator->currentPage();
    $totalPages = $paginator->lastPage();
    $pageLinks = 5; // Number of visible page links
    $halfLinks = floor($pageLinks / 2); // To display 2 pages before and after the current page

    // Set the start and end page based on the current page
    $startPage = max(1, $currentPage - $halfLinks);
    $endPage = min($totalPages, $currentPage + $halfLinks);

    // Adjust if the current page is near the start or the end of the pagination
    if ($currentPage <= $halfLinks) {
        $endPage = min($pageLinks, $totalPages);
    }
    if ($currentPage >= $totalPages - $halfLinks) {
        $startPage = max($totalPages - $pageLinks + 1, 1);
    }
@endphp

@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Display previous pages, current page, and next pages --}}
            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Show the last page link if necessary --}}
            @if ($totalPages > $pageLinks && $endPage < $totalPages)
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($totalPages) }}">{{ $totalPages }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
