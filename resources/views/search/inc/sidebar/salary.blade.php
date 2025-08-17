
<?php 

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<style>
.list-filter ul li a {
    border-radius: 5px;
    display: block;
    padding: 3px 15px 3px 5px;
    position: relative;
    color: #4e575d;
    font-size: 14px;
}
</style>
<!-- Salary -->
<div class="list-filter">
    <h5 class="list-title collapsed" data-toggle="collapse" href="#salary" role="button" aria-expanded="false"
        aria-controls="salary">
        <span class="font-weight-bold">
            {{ t('Salary Pay Range') }}
        </span>
        {{-- {!! $clearFilterBtn !!}--}}
        <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
    </h5>

    <div class="filter-salary filter-content collapse" id="salary">
        <form role="form" class="form-inline">
           
            <input type="hidden" id="url" value="{{ $actual_link }}" name="url">
            <ul class="browse-list list-unstyled long-list">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend border">
                                <input type="text" class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    id="min_salary" required name="minSalary" placeholder="Min salary"
                                    value="{{!empty(request('min_salary'))?request('min_salary'):''}}">
                                <div class="input-group-text" style="font-size: 12px;border: 1px solid #ebe5e5;">
                                    {{ config('currency')['symbol']}}</div>

                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="col-12">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend border">
                                <input type="text"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    class="form-control" id="max_salary" required name="maxSalary"
                                    placeholder="Max salary"
                                    value="{{!empty(request('max_salary'))?request('max_salary'):''}}">
                                <div class="input-group-text" style="font-size: 12px;border: 1px solid #ebe5e5;">
                                    {{ config('currency')['symbol']}}</div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="col-12">
                        <input type="button" class="btn btn-primary btn-block" id="search_salary"
                            onclick="search_slarry()" value="Search Salary">
                    </div>
                </div>
            </ul>
        </form>
    </div>
</div>
<div style="clear:both"></div>
<script>


function search_slarry() {
    var urlmy = $('#url').val();
    var url = new URL(urlmy);
    var search_params = url.searchParams;
    var min_salary = $('#min_salary').val();
    var max_salary = $('#max_salary').val();
    if (min_salary != '' && max_salary != '') {
        search_params.set('min_salary', min_salary);
        search_params.set('max_salary', max_salary);
        // change the search property of the main url
        url.search = search_params.toString();
        // the new url string
        var new_url = url.toString();
        window.location.replace(new_url);
    } else {
        alert('Please fill Min and Max salary for search')
    }

}
</script>