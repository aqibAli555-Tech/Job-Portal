<section class="discussions col-12 col-md-5 col-lg-4">
    <div class="discussion search">
        <div class="searchbar">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" id="search-input" placeholder="Search...">
        </div>

    </div>

    <div id="pagination" style="background:#FAFAFA">
        <center>
            @if ($threads->total() > 0)
                @include('admin.affiliates.message.pagination')
            @endif
        </center>
    </div>
    <div id="threads-list">
        @include('admin.affiliates.message.thread_list')
    </div>
</section>


