<!-- this (.mobile-filter-sidebar) part will be position fixed in mobile version -->
<?php
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
use App\Models\Country;
use App\Models\City;
$country_data_row=Country::get_country_name_by_code(request()->get('country'));
$country_name = $country_data_row->name??'';
$city_data_row=City::get_city_name_by_id(request()->get('city'));
$city_name = $city_data_row->name??'';
$nationality = request()->get('nationality')??'';
$sort = request()->get('sort')??'';
?>

<?php $bold = 'font-weight:bold'; ?>
<div style="clear:both"></div>
<div class="col-md-3 page-sidebar mobile-filter-sidebar pb-4">
    <aside>
        <div class="inner-box enable-long-words">
            @includeFirst([config('larapen.core.customizedViewPath') . 'account.searchcv.inc.categories',
            'account.searchcv.inc.categories'])
            @includeFirst([config('larapen.core.customizedViewPath') . 'account.searchcv.inc.country',
            'account.searchcv.inc.country'])
            @includeFirst([config('larapen.core.customizedViewPath') . 'account.searchcv.inc.nationality',
            'account.searchcv.inc.nationality'])
            @includeFirst([config('larapen.core.customizedViewPath') . 'account.searchcv.inc.cities',
            'account.searchcv.inc.cities'])
             @includeFirst([config('larapen.core.customizedViewPath') . 'account.searchcv.inc.sort',
             'account.searchcv.inc.sort'])

        </div>
    </aside>
</div>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <ul id="skillList" class="list-unstyled"></ul>
    </div>
</div>

@section('after_scripts')
@parent
<script>
$(document).ready(function() {
    var category = "{{request('cat')}}";
    var country = "{{request('country')}}";
    var city = "{{request('city')}}";
    var nationality = "{{request('nationality')}}";
    var sort = "{{request('sort')}}";
    var QuaryParameter = [];

    if (category != '') {
        QuaryParameter.push(category)
        $('#categories').collapse('show');
    }
    if (country != '') {
        QuaryParameter.push('{{$country_name}}');
        $('#countries').collapse('show');
    }
    if (city != '') {
        QuaryParameter.push('{{$city_name}}')
        $('#cities').collapse('show');
    }
    if (nationality != '') {
        QuaryParameter.push('{{$nationality}}');
        $('#nationalities').collapse('show');
    }
    if (sort != '') {
        QuaryParameter.push('{{$sort}}');
        $('#sort').collapse('show');
    }
    page_count('search_cv',QuaryParameter);

    // Close dropdown when clicking outside of it
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.dropdown-menu').length) {
            $('.dropdown-menu').collapse('hide');
        }
    });
});
$(document).ready(function() {
    $('#keyword').keypress(function(event) {
        // Check if the Enter key is pressed (keycode 13)
        if (event.which === 13) {
            // Call your function here
            event.preventDefault();
            submitFrpmData();
        }
    });
});

function submitFrpmData() {
    var urlmy = $('#url').val();
    var url = new URL(urlmy);
    var search_params = url.searchParams;
    var keyword = $('#keyword').val();
    var limit = $('#limit').val();
    // new value of "id" is set to "101"
    search_params.set('keyword', keyword);
    search_params.set('limit', limit);
    // change the search property of the main url
    url.search = search_params.toString();
    // the new url string
    var new_url = url.toString();
    window.location.replace(new_url);
}

function submitForm() {
    // Call submit() method on <form id='myform'>
    document.getElementById('myform').submit();
}

function disabledbutton() {
    document.getElementById('send_email_post').submit();
    var btn = document.getElementById('sendemail');
    btn.disabled = true;
}
</script>



<script>
$(document).ready(function() {

    $(function() {
        $('.lazy').lazy();
    });
    $('.openModalBtn').click(function() {
        // Your code to open the modal goes here
        $('#myModal').css('display', 'block');
    });

    $('.close').click(function() {
        // Your code to close the modal goes here
        $('#myModal').css('display', 'none');
    });

    $(window).click(function(event) {
        if (event.target == $('#myModal')[0]) {
            // Your code to close the modal when clicking outside goes here
            $('#myModal').css('display', 'none');
        }
    });
});
var url = window.location.href;
// Dynamically populate the skill set in the modal
var skills = <?php echo json_encode($data['emp_skills']); ?>;
var skillList = document.getElementById("skillList");
skills.forEach(function(skill) {
    if (url.includes('?cat=')) {
  // Get the value of the 'cat' parameter from the URL
  var currentCat = new URLSearchParams(window.location.search).get('cat');

  // Replace the value of the 'cat' parameter with the 'skill' value
  var newCat = skill.skill; // Replace this with the appropriate value
  var newUrl = url.replace('?cat=' + currentCat, '?cat=' + newCat);
}

    var li = document.createElement("li");
    li.innerHTML = '<a href="' + newUrl + '" title="' + skill.skill + '">' +
        '<span class="title">' + skill.skill + ' (' + skill.user_count + ')</span></a>';
    skillList.appendChild(li);
});
</script>

@endsection