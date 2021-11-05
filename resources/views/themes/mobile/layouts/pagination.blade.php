@if ($paginator->hasPages())
    <!-- Pagination -->
    <div class="">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="disabled"><i class="material-icons vm">chevron_left</i></span>
        @else
            <span class="">
                <a href="{{ $paginator->previousPageUrl() }}">
                    <i class="material-icons vm">chevron_left</i>
                </a>
            </span>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active p-2 border-left">{{ $page }}</span>
                    @elseif (($page == $paginator->currentPage() + 1 || $page == $paginator->currentPage() + 2) || $page == $paginator->lastPage())
                        <span class="p-2 border-left {{ $page == $paginator->lastPage() ? 'border-right' : '' }}"><a href="{{ $url }}">{{ $page }}</a></span>
                    @elseif ($page == $paginator->lastPage() - 1)
                        <span class="disabled p-2 border-left"><i class="material-icons vm">more_horiz</i></span>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <span class="">
                <a href="{{ $paginator->nextPageUrl() }}">
                    <i class="material-icons">chevron_right</i>
                </a>
            </span>
        @else
            <span class="disabled"><i class="material-icons">chevron_right</i></span>
        @endif
    </div>
    <!-- Pagination -->
@endif
