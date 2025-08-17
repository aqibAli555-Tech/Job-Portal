<?php
$thirdLink = false;
$secondLink = '';
$FullName = '';
if (Request::segment(1) == 'affiliate' && Request::segment(2) == 'dashboard') {
    $pagePath = 'dashboard';
}
if (Request::segment(2) == 'affiliate_profile') {
    $pagePath = 'profile';
}
if (Request::segment(2) == 'referral_users') {
    $pagePath = 'referral_users';
}
if (Request::segment(2) == 'referral_commissions') {
    $pagePath = 'referral_commissions';
}
if (Request::segment(2) == 'bank_details') {
    $pagePath = 'bank_details';
}
if (Request::segment(2) == 'withdraw_requests') {
    $pagePath = 'withdraw_requests';
}
if (request()->segment(2) == 'messages') {
    $pagePath = 'affiliate_messenger';
}

switch ($pagePath) {
    case $pagePath == 'profile';
        $page_title = t('My Profile');
        break;
    case $pagePath == 'dashboard';
        $page_title = t('Dashboard');
        break;
    case $pagePath == 'referral_users';
        $page_title = t('referral_users');
        break;
    case $pagePath == 'referral_commissions';
        $page_title = t('referral_commissions');
        break;
    case $pagePath == 'bank_details';
        $page_title = t('bank_details');
        break;
    case $pagePath == 'withdraw_requests';
        $page_title = t('withdraw_requests');
        break;
    case $pagePath == 'affiliate_messenger';
        $page_title = t('affiliate_messenger');
        break;
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