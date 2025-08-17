<?php
$thirdLink = false;
$secondLink = '';
$FullName = '';
if (!empty(request('page')) && request('page') == 'applicants') {
    $pagePath = 'Applicants';
}
if (!empty(Request::segment(1)) && !empty(Request::segment(2)) && (Request::segment(1) == 'profile' || Request::segment(1) == 'employee_profile') && is_numeric(Request::segment(2))) {
//    $thirdLink = true;
    $FullName1 = !empty($data['fullName']) ? $data['fullName'] : '';
    $FullName = 'Profile';

    if (!empty($FullName1)) {
        $FullName = 'Profile - ' . $FullName1;
    }

    $secondLink = url('/account/Applicants');
}

if (Request::segment(1) == 'profile') {
    $pagePath = 'profile';
}

if (Request::segment(1) == 'employee_profile') {
    $pagePath = 'profile';
}
if (Request::segment(1) == 'account' && empty(Request::segment(2))) {
    $pagePath = 'account';
}
if (Request::segment(2) == 'upgrade') {
    $pagePath = 'upgrade';
}

switch ($pagePath) {
    case $pagePath == "my-posts":
        $page_title = t('My Jobs');
        break;
    case $pagePath == "message_request":
        $page_title = t('message_requests');
        break;
    case $pagePath == "search-resumes":
        $page_title = t('Search Resumes');
        break;
    case $pagePath == "Applicants":
        $page_title = t('Applicants');
        break;
    case $pagePath == "Saved-Resume":
        $page_title = t('Saved Resumes');
        break;
    case $pagePath == "companies":
        $page_title = t('My companies');
        break;
    case $pagePath == "resumes":
        $page_title = t('My CV');
        break;
    case $pagePath == "cv-viewed":
            $page_title = t('Who Viewed Your CV');
            break;
    case  $pagePath == "favorite-resumes";
        $page_title = t('Favorite Resumes');
        break;
    case $pagePath == 'Unlocked-Contact-Cards';
        $page_title = t('Unlocked Contact');
        break;
    case $pagePath == 'archived';
        $page_title = t('Archived ads');
        break;
    case $pagePath == 'messenger';
        $page_title = (auth()->check() && auth()->user()->user_type_id == 1) ? t('messenger') : t('Chat With Companies');
        break;
    case $pagePath == 'transactions';
        $page_title = t('Transactions');
        break;
    case $pagePath == 'account';
        $page_title = t('Personal Home');
        break;
    case $pagePath == 'upgrade';
        $page_title = t('Upgrade Account');
        break;
    case $pagePath == 'Applied-Jobs';
        $page_title = t('Applied Jobs');
        
        break;
    case $pagePath == 'favourite';
        $page_title = t('Favorite Jobs');
        break;
    case $pagePath == 'staff';
        $page_title = t('My staff');
        break;
    case $pagePath == 'activity_logs';
        $page_title = t('Activity Logs');
        break;
    case $pagePath = 'profile';
        $page_title = t('My Profile');
    default :
        $page_title = t('My Account');
        break;
}
if (!empty($FullName)) {
    $page_title = $FullName;
}
?>
<h3 style="padding: 0">
    <?= $page_title ?>
</h3>
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/')}}">{{t('Home')}}</a></li>
    <?php if (empty($thirdLink)) { ?>
        <li class="breadcrumb-item active"><?= ucwords($page_title) ?></li>
    <?php } else { ?>
        <li class="breadcrumb-item"><a href="<?= $secondLink ?>"><?= ucwords($page_title) ?></a></li>
        <li class="breadcrumb-item active"><?= $FullName ?></li>
    <?php } ?>
</ul>
