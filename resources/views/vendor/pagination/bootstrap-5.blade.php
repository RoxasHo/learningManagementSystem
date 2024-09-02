@if ($paginator->hasPages())
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/pagination_style.css') }}">
    @endpush
    <nav class="pagination-container">
        {{-- Page Numbers and Links --}}
        <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
            {{-- Previous Link --}}
            <div class="pagination-prev-next">
                @if ($paginator->onFirstPage())
                    <span class="disabled">@lang('pagination.previous')</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                @endif
            </div>

            {{-- Page Numbers --}}
            <ul class="pagination-pages">
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled"><span>{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>

            {{-- Next Link --}}
            <div class="pagination-prev-next">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                @else
                    <span class="disabled">@lang('pagination.next')</span>
                @endif
            </div>
        </div>

        {{-- "Showing results..." text --}}
        <div class="pagination-info">
            <p class="small text-muted">
                {!! __('Showing') !!}
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>
    </nav>
@endif
