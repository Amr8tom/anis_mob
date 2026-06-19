@if ($paginator->hasPages())
    @php
        // RTL portal: "previous" sits on the right, "next" on the left.
        $window = method_exists($paginator, 'onEachSide') ? null : null;
    @endphp
    <nav class="uw-pagination" role="navigation" aria-label="تنقل بين الصفحات">
        <div class="uw-pagination__summary">
            @php
                $first = $paginator->firstItem();
                $last  = $paginator->lastItem();
            @endphp
            عرض
            <strong>{{ $first ? number_format($first) : 0 }}</strong>
            –
            <strong>{{ $last ? number_format($last) : 0 }}</strong>
            من
            <strong>{{ number_format($paginator->total()) }}</strong>
            نتيجة
        </div>

        <ul class="uw-pagination__list">
            {{-- Previous (right side in RTL) --}}
            @if ($paginator->onFirstPage())
                <li class="uw-page uw-page--disabled" aria-disabled="true">
                    <span class="uw-page__link"><i class="fa-solid fa-angle-right"></i></span>
                </li>
            @else
                <li class="uw-page">
                    <a class="uw-page__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="السابق">
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                </li>
            @endif

            {{-- Numbered links with ellipsis --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="uw-page uw-page--disabled" aria-disabled="true">
                        <span class="uw-page__link uw-page__ellipsis">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="uw-page uw-page--active" aria-current="page">
                                <span class="uw-page__link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="uw-page">
                                <a class="uw-page__link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next (left side in RTL) --}}
            @if ($paginator->hasMorePages())
                <li class="uw-page">
                    <a class="uw-page__link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="التالي">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                </li>
            @else
                <li class="uw-page uw-page--disabled" aria-disabled="true">
                    <span class="uw-page__link"><i class="fa-solid fa-angle-left"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@elseif ($paginator->total() > 0)
    {{-- Single page: still show the result count for context --}}
    <nav class="uw-pagination uw-pagination--single" aria-label="ملخص النتائج">
        <div class="uw-pagination__summary">
            <strong>{{ number_format($paginator->total()) }}</strong> نتيجة
        </div>
    </nav>
@endif
