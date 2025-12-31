@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-primary justify-content-center">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @php
                            $isCurrent = $page == $paginator->currentPage();
                            $isFirstOrLast = $page == 1 || $page == $paginator->lastPage();
                            $isAdjacent = abs($page - $paginator->currentPage()) <= 1;
                            // Show on mobile if: Current, First/Last, or Adjacent. Otherwise hidden on mobile.
                            // Actually, standard practice for mobile is often just Current + Adjacent. 
                            // Let's hide if NOT (Current OR Adjacent OR First OR Last).
                            $visibilityClass = ($isCurrent || $isFirstOrLast || $isAdjacent) ? '' : 'd-none d-md-block';
                        @endphp
                        @if ($isCurrent)
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item {{ $visibilityClass }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
