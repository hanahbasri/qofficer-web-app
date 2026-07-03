@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="d-flex align-items-center justify-content-between">
        {{-- Info teks: Menampilkan X – Y dari Z --}}
        <div class="pagination-info">
            @if ($paginator->firstItem())
                Menampilkan <strong>{{ $paginator->firstItem() }}</strong> – <strong>{{ $paginator->lastItem() }}</strong> dari <strong>{{ $paginator->total() }}</strong> data
            @else
                Menampilkan 0 data
            @endif
        </div>

        {{-- Tombol navigasi halaman --}}
        <div class="pagination-links">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled" aria-disabled="true">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-btn" aria-label="Halaman Sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Titik-titik pemisah --}}
                @if (is_string($element))
                    <span class="page-btn dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Link halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn" aria-label="Ke halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-btn" aria-label="Halaman Berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="page-btn disabled" aria-disabled="true">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
