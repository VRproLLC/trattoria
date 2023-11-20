@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <img src="{{asset('image/bi_arrow-left-short.svg')}}" alt="">
                </li>
            @else
                <li data-id-page="{{ $paginator->currentPage() - 1 }}"  class="button_archive_pagination" style="cursor: pointer">
                    <img src="{{asset('image/bi_arrow-left-short.svg')}}" alt="">
                </li>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="current" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li data-id-page="{{ $page }}"  class="button_archive_pagination" style="cursor: pointer"><span>{{ $page }}</span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li data-id-page="{{ $paginator->currentPage() + 1 }}"  class="button_archive_pagination" style="cursor: pointer">
                    <img src="{{asset('image/bi_arrow-right-short.svg')}}" alt="">
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <img src="{{asset('image/bi_arrow-right-short.svg')}}" alt="">
                </li>
            @endif
        </ul>
    </nav>
@endif
