@extends('auth.layouts.master')
@section('content')
<?php
$affiliate = url("storage/app/public/affiliate.jpg");
$employee = url("storage/app/public/employee.jpg");
$employer = url("storage/app/public/employer.jpg");
$affiliate_mobile = url("storage/app/public/affiliate_mobile.jpg");
$employee_mobile = url("storage/app/public/employee_mobile.jpg");
$employer_mobile = url("storage/app/public/employer_mobile.jpg");
?>
<style>
    .row {
        height: 95vh;
    }

    .register{
        height: 90vh;
    }

    .register-inner {
        padding: 10px;
        display: block;
        margin: 0 auto;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .register-inner h2 {
        font-size: 45px;
    }

    .register p {
        font-size: 18px;
    }

    .register img {
        width: 150px;
        position: absolute;
        bottom: 10px;
        left: 100px;
    }

    .bg-color {
        padding: 0;
        background-color: #615583;
    }

    body {
        padding-top: 50px;
        overflow: hidden;
        height: 100vh;
    }

    .register-item {
        background-size: cover;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 20px;
    }

    #affiliate {
        background-image: url('<?= $affiliate ?>');
    }

    #employee {
        background-image: url('<?= $employee ?>');
    }

    #employer {
        background-image: url('<?= $employer ?>');
    }


    @media (max-width: 768px) {
        #main-content-div {
            padding-top: 0 !important;
        }

        .register-inner {
            padding: 10px;
            padding-top: 100px;
            position: initial;
            transform: none;
        }

        .register img {
            left: initial;
            right: 20px;
        }

        body {
            overflow: auto !important;
            padding-top: 0 !important;
        }

        .register{
            height: auto;
        }

        .row {
            height: 100vh;
        }

        .navbar.navbar-site {
            position: initial;
        }

        #affiliate {
            background-image: url('<?= $affiliate_mobile ?>');
        }

        #employee {
            background-image: url('<?= $employee_mobile ?>');
        }

        #employer {
            background-image: url('<?= $employer_mobile ?>');
        }
    }
</style>
<div class="row">
    <div class="col-lg-3 text-white p-4 bg-color">
        <div class="register">
            <div class="register-inner">
                <h2 class="fw-bold text-white">Register</h2>
                <p>Register as one of the following three options and begin your journey with Hungry for Jobs</p>
                <p>Already have an account?</p>
                <a href="<?= url('login') ?>" class="btn fw-bold" style="background-color: white; color: #615583; padding: 10px 20px; border-radius: 5px; font-weight: bold;">Login Here</a>
            </div>
            <img alt="register" src="<?= url("public/assets/images/logo_register.png?v2") ?>">
        </div>
    </div>

    <div class="col-lg-3 register-item bg-color" id="employer" onclick="window.location='<?= url('register') ?>?user_type_id=2';" style="cursor: pointer;">
        <a href="<?= url('register') ?>?user_type_id=2" style="color: white; text-decoration: none; display: flex; flex-direction: column; align-items: flex-start;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" style="margin-bottom: 5px;">
                <circle cx="12" cy="8" r="4" stroke="white" stroke-width="2" fill="white"/>
                <path d="M12 14C9 14 4 15 4 18V20H20V18C20 15 15 14 12 14Z" stroke="white" fill="white" stroke-width="2"/>
            </svg>
            <span style="font-weight: bold; font-size: 25px; margin-bottom: 5px;">Employee</span>
            <span style="font-size: 20px;">Job Seeker
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" style="margin-left: 5px;">
                    <path d="M17 12L7 12M17 12L13 16M17 12L13 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
        </a>
    </div>
    <div class="col-lg-3 register-item bg-color" id="employee" onclick="window.location='<?= url('register') ?>?user_type_id=1';" style="cursor: pointer;">
        <a href="<?= url('register') ?>?user_type_id=1" style="color: white; text-decoration: none; display: flex; flex-direction: column; align-items: flex-start;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 5px;">
                <path d="M3 21h18"></path>
                <path d="M5 21V9a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v12"></path>
                <path d="M9 21V12"></path>
                <path d="M15 21V12"></path>
                <path d="M9 6V3"></path>
                <path d="M15 6V3"></path>
                <path d="M9 6h6"></path>
            </svg>
            <span style="font-weight: bold; font-size: 25px; margin-bottom: 5px;">Employer</span>
            <span style="font-size: 20px;">Company
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" style="margin-left: 5px;">
                    <path d="M17 12L7 12M17 12L13 16M17 12L13 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
        </a>
    </div>

    <div class="col-lg-3 register-item bg-color" id="affiliate" onclick="window.location='<?= url('affiliate-program') ?>'" style="cursor: pointer;">
        <a href="<?= url('affiliate-program') ?>" style="color: white; text-decoration: none; display: flex; flex-direction: column; align-items: flex-start;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 5px;">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 6v12"></path>
                <path d="M14 8c-2-2-4 0-4 1s2 2 4 3 2 3 0 4-4 0-4-2"></path>
            </svg>
            <span style="font-weight: bold; font-size: 25px; margin-bottom: 5px;">Affiliate</span>
            <span style="font-size: 20px;">Earn Money
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px;">
                    <path d="M17 12H7"></path>
                    <path d="M17 12l-4 4"></path>
                    <path d="M17 12l-4-4"></path>
                </svg>
            </span>
        </a>
    </div>
</div>
@endsection