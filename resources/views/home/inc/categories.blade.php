<?php
$hideOnMobile = '';
if (isset($categoriesOptions, $categoriesOptions['hide_on_mobile']) and $categoriesOptions['hide_on_mobile'] == '1') {
    $hideOnMobile = ' hidden-sm';
}
?>

<div class="categories-section">
    @if (isset($categoriesOptions))
    @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'], ['hideOnMobile' => $hideOnMobile])
    <div class="container{{ $hideOnMobile }}">
        <br>
        <br>
        <div class="company-name-heading" style="color:#000 !important;">
        {{t('Browse Employees (Job Seekers) by Skills Sets')}}
    </div>
    <div class="categories">
      @if (!empty($emp_skills))
          <?php $counter = 1; ?>
          @foreach($emp_skills as $key => $skills)
              <a href="{{url('search-resumes?cat=')}}{{$skills->skill}}{{'&country=&city=&keyword=&limit=&offset=0&send=&nationality=&sort='}}" class="category">
                  <div class="card featured_skill_card">
                      <img class="featured_skill_image" src="{{url('public/storage/'.$skills->image)}}" alt="{{ $skills->skill }}">
                      <div class="skill-text">{{ $skills->skill }}</div>
                  </div>
              </a>
  
              @if($counter == 11)
                  <a href="{{url('search-resumes?cat=all&country=&city=&keyword=&limit=&offset=0&send=&nationality=&sort=')}}" class="category">
                      <div class="card featured_skill_card">
                          <img class="featured_skill_image" src="{{url('public/storage/employee_skill/view_all_category.png')}}" alt="View All Skill Sets">
                      </div>
                  </a>
                  <?php break; ?>
              @endif
  
              <?php $counter++;?>
          @endforeach
      @endif
  </div>

</div>
@endif

@section('before_scripts')
@parent
@if (isset($categoriesOptions) and isset($categoriesOptions['max_sub_cats']) and $categoriesOptions['max_sub_cats'] >= 0)

@endif
@endsection
