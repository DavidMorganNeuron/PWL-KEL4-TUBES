{{-- PODS PAGINATION: komponen navigasi halaman dengan tema Pod's --}}
@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" style="display:flex; align-items:center; justify-content:space-between; padding:0.75rem 1.5rem; background:#FBF6EE; border-top:1px solid #EDE0CC; flex-wrap:wrap; gap:0.75rem;">
    {{-- info jumlah --}}
    <div>
        <p style="font-size:0.75rem; color:#A08060; font-weight:300;">
            Menampilkan
            <span style="font-weight:600; color:#1C0F0A;">{{ $paginator->firstItem() }}</span>
            –
            <span style="font-weight:600; color:#1C0F0A;">{{ $paginator->lastItem() }}</span>
            dari
            <span style="font-weight:600; color:#1C0F0A;">{{ $paginator->total() }}</span>
            data
        </p>
    </div>

    {{-- tombol navigasi --}}
    <div style="display:flex; align-items:center; gap:0.25rem;">
        {{-- previous --}}
        @if ($paginator->onFirstPage())
        <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid #EDE0CC; background:#F5F0E8; color:#C2B09A; font-size:0.75rem; font-weight:600; cursor:not-allowed;" aria-disabled="true" aria-label="Sebelumnya">
            ‹
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid #EDE0CC; background:#FFFFFF; color:#3D1F0F; font-size:0.75rem; font-weight:600; text-decoration:none; cursor:pointer; transition:all 0.15s;"
            onmouseover="this.style.borderColor='#C8813B'; this.style.color='#C8813B';"
            onmouseout="this.style.borderColor='#EDE0CC'; this.style.color='#3D1F0F';"
            aria-label="Sebelumnya">
            ‹
        </a>
        @endif

        {{-- halaman --}}
        @foreach ($elements as $element)
            {{-- separator --}}
            @if (is_string($element))
            <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; font-size:0.75rem; color:#A08060; font-weight:400;">{{ $element }}</span>
            @endif

            {{-- tombol halaman --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 0.375rem; border-radius:8px; background:#C8813B; color:#1C0F0A; font-size:0.75rem; font-weight:700; cursor:default;" aria-current="page" aria-label="Halaman {{ $page }}">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $url }}" style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 0.375rem; border-radius:8px; border:1px solid transparent; background:transparent; color:#3D1F0F; font-size:0.75rem; font-weight:500; text-decoration:none; cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.borderColor='rgba(200,129,59,0.3)'; this.style.background='rgba(200,129,59,0.06)';"
                        onmouseout="this.style.borderColor='transparent'; this.style.background='transparent';"
                        aria-label="Ke halaman {{ $page }}">
                        {{ $page }}
                    </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- next --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid #EDE0CC; background:#FFFFFF; color:#3D1F0F; font-size:0.75rem; font-weight:600; text-decoration:none; cursor:pointer; transition:all 0.15s;"
            onmouseover="this.style.borderColor='#C8813B'; this.style.color='#C8813B';"
            onmouseout="this.style.borderColor='#EDE0CC'; this.style.color='#3D1F0F';"
            aria-label="Selanjutnya">
            ›
        </a>
        @else
        <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid #EDE0CC; background:#F5F0E8; color:#C2B09A; font-size:0.75rem; font-weight:600; cursor:not-allowed;" aria-disabled="true" aria-label="Selanjutnya">
            ›
        </span>
        @endif
    </div>
</nav>
@endif
