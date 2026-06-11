@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-between gap-2">
        @if ($paginator->onFirstPage())
            <span class="inline-flex cursor-not-allowed items-center rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-400">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a
                href="{{ $paginator->previousPageUrl() }}"
                rel="prev"
                class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition duration-200 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            >
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
                rel="next"
                class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition duration-200 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            >
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex cursor-not-allowed items-center rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-400">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
