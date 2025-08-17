<?php
// Clear Filter Button
use App\Helpers\UrlGen;

$clearFilterBtn = '';
if (request()->filled('type')) {
    $clearFilterUrl = UrlGen::search([], ['page', 'type']);
    $clearFilterBtn = getFilterClearBtn($clearFilterUrl);
}
?>
<?php
$inputPostType = [];
if (request()->filled('type')) {
    $types = request()->get('type');
    if (is_array($types)) {
        foreach ($types as $type) {
            $inputPostType[] = $type;
        }
    } else {
        $inputPostType[] = $types;
    }
}

?>
<!-- PostType -->
<div class="list-filter">
    <h5 class="list-title collapsed" data-toggle="collapse" href="#postType" role="button" aria-expanded="false"
        aria-controls="postType">
        <span class="font-weight-bold">
            {{ t('Job Type') }}
        </span>
        {{-- {!! $clearFilterBtn !!}--}}
        <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
    </h5>
    <div class="filter-content filter-employment-type collapse" id="postType">
        <ul id="blocPostType" class="browse-list list-unstyled">
            @if (isset($postTypes) and $postTypes->count() > 0)
            @foreach($postTypes as $key => $postType)
            <li>
                <input type="checkbox" name="type[{{ $key }}]" id="employment_{{ $postType->id }}"
                    value="{{ $postType->id }}" class="emp emp-type"
                    {{ (in_array($postType->id, $inputPostType)) ? ' checked="checked"' : '' }}>
                <label for="employment_{{ $postType->id }}">{{ $postType->name }}</label>
            </li>
            @endforeach
            @endif
            <input type="hidden" id="postTypeQueryString"
                value="{{ httpBuildQuery(request()->except(['page', 'type'])) }}">
        </ul>
    </div>
</div>
<style>
.list-title:not(.collapsed) .rotate-icon {
    transform: rotate(180deg);
}

.accicon {
    font-size: 12px;
    float: right;
    padding-right: 14px;
}

.list-title {
    background: #f5f5f5;
    padding: 10px;
    margin-bottom: 10px;
}

.filter-content {
    padding-left: 10px;
}
</style>
<div style="clear:both"></div>

@section('after_scripts')
@parent

<script>
$(document).ready(function() {
    var link = window.location.href;
    $('#blocPostType input[type=checkbox]').click(function() {
        var postTypeQueryString = $('#postTypeQueryString').val();

        if (postTypeQueryString != '') {
            postTypeQueryString = postTypeQueryString + '&';
        }
        var tmpQString = '';
        $('#blocPostType input[type=checkbox]:checked').each(function() {
            if (tmpQString != '') {
                tmpQString = tmpQString + '&';
            }
            tmpQString = tmpQString + 'type[]=' + $(this).val();
        });
        postTypeQueryString = postTypeQueryString + tmpQString;
        var newlink = link.includes('?q=');
        if (newlink) {
            var searchUrl = baseUrl + '?' + postTypeQueryString;
        } else {
            var searchUrl = baseUrl + '?' + postTypeQueryString;
        }
        window.location.href = searchUrl;
    });
});
</script>
@endsection