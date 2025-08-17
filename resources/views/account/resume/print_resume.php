<?php

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Resume</title>
    <style>
        * {
            padding: 0px;
            margin: 0px;
            font-family: 'Lato', sans-serif;
        }

        body {
            background: white;
            padding-bottom: 80px;
        }

        .main {
            width: 100%;
            padding-bottom: 253px !important;
        }

        .left {
            width: 33%;
            float: left;
            height: 900px;
            padding-left: 20px;
        }

        .right {
            width: 60%;
            background: white;
            padding-right: 20px;
            padding-top: 10px;
            float: right;
        }

        .img {
            width: 70%;
            /*margin-left: 10%;*/
            border-radius: 10px;
            overflow: hidden;
        }

        .img img {
            width: 100%;
        }


        .interst h4 {
            color: #635987;
        }

        .interst_list {
            padding-left: 4px;
            width: 100%;
        }

        p {
            width: 100%;
            color: rgb(96, 95, 95);
            font-size: 14px;
            line-height: 1.5;
        }

        .about {
            color: #545454;
        }

        .about p {
            padding: 20px !important;
            text-align: justify !important;
        }

        .category_item {
            color: #635987;
            text-transform: capitalize;
        }

        .category_item1 {
            background: #22d3fd;
            display: block;
            color: #000;
            padding: 10px;
            font-size: 13px;
            margin-top: 2px;
            font-weight: 700;
        }

        .category_item1 img {
            width: 20px;
            cursor: pointer;
            position: absolute;
            left: 50px;
            margin-top: 2px;
        }

        p {
            margin-bottom: 0rem;
        }
    </style>
</head>
<body>
<!-- Header wala Portion code start-->
<div style="background: #79598d; min-height: 66px; border: none;text-align: center;padding: 20px">
    <a href="<?php use App\Models\Country;

    echo url('/') ?>"><img src="https://hungryforjobs.com/storage/app/public/app/icon/hfjpdf.png" height="70px" alt="Hungry For Jobs" class="tooltipHere main-logo" title="" data-placement="bottom" data-toggle="tooltip" data-original-title="Hungry For Jobs Kuwait" style="display: block;"></a>
    <span style="color:#fff;display: inline-block;float: right;/* text-align: left; */margin-top: -55px;">
        <b>Employee</b><br> Resume
    </span>
</div>
<div class="main">
    <div class="left">
        <div class="img" style="margin-top: 8px;">
            <img src=" <?= $request['img']; ?>" alt="">
        </div>
        <br>
        <h1 style="text-align: left;color: #635987;font-family: 'Lato', sans-serif;font-size: 24px">
            <?= $request['resume_data']->fullName; ?>
        </h1>
        <br>
        <p style="color: #63598d;font-weight: 700">
            Employee Skill
        </p>
        <p class="category_item">
            <?= $request['resume_data']->interest; ?>
        </p>
        <br>
        <!-- Interset code is start here -->

        <!-- End of Interest Data Here -->
        <p style="color: #63598d;font-weight: 700">Date oF Birth</p>
        <p class="about" style="font-weight:400;color:#63598d"><?= $request['resume_data']->birthDate; ?> </p>
        <br>
        <p style="color: #63598d;font-weight: 700">Gender</p>
        <p class="about" style="font-weight:400;color:#63598d"><?php if ($request['resume_data']->gender == 1) {
                echo 'Male';
            } else {
                echo 'Female';
            } ?> </p>
        <br>
        <p style="color: #63598d;font-weight: 700">Nationality</p>
        <p class="about" style="font-weight:400;color:#63598d">

            <?php
            $nationalitY_id = (!empty($request['resume_data']->nationality) ? $request['resume_data']->nationality : 0);
            $nationality = DB::table('nationality')->where('id', $nationalitY_id)->get()->first();
            $user_nationality = (!empty($nationality->name) ? $nationality->name : '');

            ?>
            <?= $user_nationality ?>


        </p>
        <br>


        <p style="color: #63598d;font-weight: 700">ABOUT</p>
        <p class="about" style="font-weight:400;color:#63598d"><?= $request['resume_data']->about; ?> </p>
        <br>

        <p style="color: #63598d;"><span style="font-weight: 700">Address</span>&nbsp;&nbsp;&nbsp;Country: <?= $request['country_name']; ?>
            City: <?= $request['city_name']; ?></p>
        <br>
        <br>
        <div style="padding: 20px;background: #f1f0f0;text-align: center">
            Connect with this Employee through their Contact Card.
            <br>
            <br>
            <a href="<?php echo url('profile/' . $request['user_data']->id) ?>" target="_blank" class="category_item1" style="text-decoration: none; "><img src="https://hungryforjobs.com/storage/app/public/app/icon/eye.png" alt="" style=""> VIEW CONTACT CARD</a>
            <a href="<?php echo url('profile/' . $request['user_data']->id) ?>" target="_blank" class="category_item1" style="text-decoration: none"><img src="https://hungryforjobs.com/storage/app/public/app/icon/eye.png" alt="">VIEW PROFILE</a>

        </div>
        <div>

        </div>
    </div>
    <div class="right">
        <div style="width: 100% ; display: block">
            <p style="color: #63598d;margin: 0;padding: 0;font-weight: bold;font-size: 20px">Skills & Experiences</p>
            <br>
            <div style="width: 100%">
                <?php
                $se_skills = explode(',', $request['resume_data']->skill);
                $se_experiences = explode(',', $request['resume_data']->experience);
                $se_Company = explode(',', $request['resume_data']->seCompany);
                $se_Country = explode(',', $request['resume_data']->seCountry);
                $se_city = explode(',', $request['resume_data']->seCity);
                $se_enddate = explode(',', $request['resume_data']->seenddate);
                $se_startdate = explode(',', $request['resume_data']->sestartdate);
                $se_Description = explode(',', $request['resume_data']->seDescription);
                ?>
                <?php if (!empty($se_skills) && !empty($se_Company[0])): ?>
                    <?php foreach ($se_skills as $key => $skill): ?>
                        <?php
                        $se_country = Country::where('code', $se_Country[$key])->first();
                        if (empty($se_country)) {
                            $se_country = '';
                        }
                        ?>
                        <div style="padding: 5px;padding-left: 0">
                            <h4><?= $skill ?></h4>
                            <p>
                                <?= $se_country->name ?> - <?= $se_city[$key] ?><br>
                                <?= $se_Company[$key] ?> - <?= $se_experiences[$key] ?>
                                <br>
                                <?= date("F Y", strtotime($se_startdate[$key])); ?> - <?= date("F Y", strtotime($se_enddate[$key])); ?>
                                <br>
                                <?= $se_Description[$key] ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    I do not have any Skills & Experiences.
                <?php endif; ?>

            </div>

        </div>
        <br>
        <div style="width: 100% ; display: block">
            <p style="color: #63598d;margin: 0;padding: 0;font-weight: bold;font-size: 20px">Education</p>

            <div style="width: 100%">
                <?php
                $university = explode(',', $request['resume_data']->university);
                $degree = explode(',', $request['resume_data']->degree);
                $ed_startDate = explode(',', $request['resume_data']->ed_startDate);
                $ed_endDate = explode(',', $request['resume_data']->ed_endDate);

                ?>
                <?php if (!empty($university[0])): ?>
                    <?php foreach ($university as $key => $uni): ?>
                        <div style="padding: 5px;padding-left: 0">
                            <h4><?= $uni ?></h4>
                            <p>
                                <?= $degree[$key] ?>
                            </p>
                            <p> <?= date("F Y", strtotime($ed_startDate[$key])); ?> - <?= date("F Y", strtotime($ed_endDate[$key])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding: 5px;padding-left: 0">
                        I hereby verify that I do not own an official college or university degree.
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <!-- Education Portion Code is End here -->
        <br>
        <!-- Employment Portion Code Start Here -->
        <?php
        $hobby = explode(',', $request['resume_data']->hobby);
        $hDescription = explode(',', $request['resume_data']->hDescription);
        ?>
        <p style="color: #63598d;margin: 0;padding: 0;font-weight: bold;font-size: 20px">Interests</p>
        <br>
        <?php foreach ($hobby as $key => $hob): ?>
            <p><?= $hob ?></p>

            <p><?= $hDescription[$key] ?></p>
        <?php endforeach; ?>

    </div>
</div>
<br>
<div style="background: #79598d ;max-height: 122px;border: none;padding: 20px;position: fixed;bottom: 0;width: 100%;">
    <a href="https://www.facebook.com/Hungry-For-Jobs-726772877757699"><img src="https://hungryforjobs.com/storage/app/public/app/icon/awesome-facebook-f.svg?version=<?= time() ?>" alt="" style=" width: 11px; cursor: pointer;background-color:#79598d"></a>&nbsp;
    <a href="https://www.instagram.com/HungryForJobs/"><img src="https://hungryforjobs.com/storage/app/public/app/icon/metro-instagram.svg?version=<?= time() ?>" alt="" style=" width: 20px; cursor: pointer;background-color:#79598d"></a>&nbsp;
    <a href="https://www.linkedin.com/company/hungryforjobs"><img src="https://hungryforjobs.com/storage/app/public/app/icon/awesome-linkedin.svg?version=<?= time() ?>" alt="" style=" width: 20px;height:20px; border-radius:50%;cursor: pointer;background-color:#79598d"></a>
    <a href="<?php echo url('/') ?>" style="float: right;color:#fff;text-decoration:none;font-size:13px;">www.hungryforjobs.com</a>
</div>
</body>
</html>
<script type="text/javascript">
    window.onload = function () {
        window.print();
    }
</script>


