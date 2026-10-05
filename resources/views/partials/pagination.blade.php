{{-- resources/views/partials/pagination.blade.php --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="ss-pagination-container">
        <div class="ss-pagination-info">
            Affichage de <span>{{ $paginator->firstItem() }}</span> à <span>{{ $paginator->lastItem() }}</span> sur <span>{{ $paginator->total() }}</span> matériels
        </div>

        <ul class="ss-pagination-list">
            {{-- Bouton Précédent --}}
            @if ($paginator->onFirstPage())
                <li class="ss-page-item is-disabled" aria-disabled="true">
                    <span class="ss-page-btn" aria-label="Précédent">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="d-none d-sm-inline">Précédent</span>
                    </span>
                </li>
            @else
                <li class="ss-page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="ss-page-btn" aria-label="Précédent">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="d-none d-sm-inline">Précédent</span>
                    </a>
                </li>
            @endif

            {{-- Numéros de page --}}
            @foreach ($elements as $element)
                {{-- Séparateur "..." --}}
                @if (is_string($element))
                    <li class="ss-page-item is-disabled"><span class="ss-page-dots">{{ $element }}</span></li>
                @endif

                {{-- Liens de pages --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="ss-page-item is-active" aria-current="page">
                                <span class="ss-page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="ss-page-item">
                                <a href="{{ $url }}" class="ss-page-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Bouton Suivant --}}
            @if ($paginator->hasMorePages())
                <li class="ss-page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="ss-page-btn" aria-label="Suivant">
                        <span class="d-none d-sm-inline">Suivant</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </li>
            @else
                <li class="ss-page-item is-disabled" aria-disabled="true">
                    <span class="ss-page-btn" aria-label="Suivant">
                        <span class="d-none d-sm-inline">Suivant</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
