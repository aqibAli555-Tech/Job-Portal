@if ($threads->hasPages())
    <div class="chat-pagination d-flex justify-content-between align-items-center">
        {{-- Previous Page Link --}}
        @if (!$threads->onFirstPage())
            <a class="btn btn-outline-primary btn-rounded"
               onclick="load_new_threads('{{ $threads->currentPage() - 1 }}')" href="javascript:void(null)" rel="prev"
               aria-label="Previous">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
        @else
            <button type="button" class="btn btn-outline-secondary btn-rounded" disabled>
                <i class="fas fa-chevron-left"></i> Previous
            </button>
        @endif

        <div class="pagination-numbers">
            Page {{ $threads->currentPage() }} of {{ $threads->lastPage() }}
        </div>

        @if ($threads->hasMorePages())
            <a class="btn btn-outline-primary btn-rounded"
               onclick="load_new_threads('{{ $threads->currentPage() + 1 }}')" href="javascript:void(null)" rel="next"
               aria-label="Next">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <button type="button" class="btn btn-outline-secondary btn-rounded" disabled>
                Next <i class="fas fa-chevron-right"></i>
            </button>
        @endif
    </div>
@endif
