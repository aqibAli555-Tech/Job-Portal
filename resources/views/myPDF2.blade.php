<?php

?>
<!DOCTYPE html>
<html>
<head>
    <title>Resume</title>
    <style>
        * {
            padding: 0px;
            margin: 0px;
            font-family: 'Lato', sans-serif;
        }

        body {
            background: white;
            padding-bottom: 40px;
        }

        .main {
            width: 100%;
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
            margin-left: 10%;
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
    </style>
</head>
<body>
<!-- Header wala Portion code start-->
<div style="background: #79598d;height: 45px;border: none;padding: 20px">
    <img src="https://hfj.menu.house/storage/app/public/app/icon/hfjpdf.png" alt="Hungry For Jobs" height="70px">
    <span style="color:#fff;display: inline-block;float: right;text-align: left">
        <b>Employee</b><br> Resume
    </span>
</div>
<div class="main">
    <div class="left">
        <div class="img" style="margin-top: 8px;">
            <img src="{{$img}}" alt="">
        </div>
        <br>
        <h1 style="text-align: left;color: #635987;font-family: 'Lato', sans-serif;font-size: 24px">

        </h1>
        <br>
        <p style="color: #63598d;font-weight: 700">Employee Skills</p>
        <p style="color: #63598d;font-weight: 400">
            <?php $count = array_key_last($data->catgory); ?>
            @foreach ($data->catgory as $key => $cat)
            <span class="category_item">{{$cat}}@if($key <  $count),@endif</span>
            @endforeach
        </p>


        <br>
        <!-- Interset code is start here -->

        <!-- End of Interest Data Here -->
        <p style="color: #63598d;font-weight: 700">Date Of Birth</p>
        <p class="about" style="font-weight:400;color:#63598d"><?php echo $data->date; ?></p>
        <br>
        <p style="color: #63598d;font-weight: 700">Gender</p>
        @if($data->gender==1)
        <p class="about" style="font-weight:400;color:#63598d">Male</p>
        @else
        <p class="about" style="font-weight:400;color:#63598d">Female</p>
        @endif
        <br>

        <p style="color: #63598d;font-weight: 700">ABOUT</p>
        <p class="about" style="font-weight:400;color:#63598d"><?php echo $data->about; ?></p>
        <br>

        <p style="color: #63598d;"><span style="font-weight: 700">Location</span>&nbsp;&nbsp;&nbsp;
            <br>
        <p class="about" style="font-weight:400;color:#63598d">Country: <?php echo $data->country_name; ?></p>
        <p class="about" style="font-weight:400;color:#63598d">City: <?php echo $data->city_name; ?></p>
        <br>
        <div style="padding: 20px;background: #f1f0f0;text-align: center">
            Connect with this Employee through their Contact Card.
            <br>
            <br>
            <a target="_blank" href="{{url('profile/'.$data->id)}}" target="_blank" class="category_item1" style="text-decoration: none; "><img src="https://hfj.menu.house/storage/app/public/app/icon/eye.png" alt="" style=""> VIEW CONTACT CARD</a>
            <a target="_blank" href="{{url('profile/'.$data->id)}}" target="_blank" class="category_item1" style="text-decoration: none"><img src="https://hfj.menu.house/storage/app/public/app/icon/eye.png" alt="">VIEW PROFILE</a>

        </div>
        <div>
            <!--            <div class="interst">-->
            <!--                <h4>PERSONAL</h4>-->
            <!--            </div>-->
            <!--            <div class="interst_list">-->
            <!--                <p><strong>Last name </strong> {{ucfirst ($data->fatherName)}}</p>-->
            <!--            </div>-->
            <!--            <div class="interst_list">-->
            <!--                <p><strong>Date of birth </strong> {{$data->date}}</p>-->
            <!--            </div>-->
            <!--            <div class="interst_list">-->
            <!--                <p><strong>Gender </strong>-->
            <!--                    @if($data->gender == 1)-->
            <!--                    Male-->
            <!--                    @else-->
            <!--                    Female-->
            <!--                    @endif-->
            <!--                </p>-->
            <!--            </div>-->
            <!--            <div class="interst_list">-->
            <!--                <p><strong>Local ID Number </strong> {{$data->cnic}}</p>-->
            <!--            </div>-->
            <!--            <div class="interst_list">-->
            <!--                <p><strong>Address </strong> {{$data->address }}</p>-->
            <!--            </div>-->
        </div>
    </div>
    <div class="right">
        <div style="width: 100% ; display: block">

            <div style="width: 100%">

            </div>

        </div>
        <br>
        <div style="width: 100% ; display: block">
            <p style="color: #63598d;margin: 0;padding: 0;font-weight: bold;font-size: 20px">Education</p>
            <br>
            <div style="width: 100%">
                @foreach ($data->university as $uni)
                @if(!empty($uni))
                <div style="padding: 5px;padding-left: 0">
                    <h4>{{$uni}}</h4>
                    <p>{{ $data->degree[$loop->index]}}</p>
                    <p><? echo date("Y", strtotime($data->dateStart[$loop->index])); ?> - <? echo date("Y", strtotime($data->dateEnd[$loop->index])); ?></p>

                </div>
                @else
                <div style="padding: 5px;padding-left: 0">
                    <p>I do not own an official college or university degree.</p>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Education Portion Code is End here -->
        <br>
        <!-- Employment Portion Code Start Here -->

        <p style="color: #63598d;margin: 0;padding: 0;font-weight: bold;font-size: 20px">Interests</p>
        <br>
        @foreach ($data->interests as $interest)
        <!--        <div class="interst_list">-->
        <!--            <br>-->
        <!--            <h5>{{ ucfirst($interest)}}</h5>-->
        <!--            <p>{{ $data->interestDes[$loop->index] }}</p>-->
        <!--        </div>-->
        <p>{{ ucfirst($interest)}}</p>
        @endforeach
    </div>
</div>
<br>
{{--footer--}}
<div style="background: #79598d;height: 18px;border: none;padding: 20px;position: fixed;bottom: 0;width: 100%;">
    <a target="_blank" href="https://www.facebook.com/Hungry-For-Jobs-726772877757699"><img src="https://hfj.menu.house/storage/app/public/app/icon/awesome-facebook-f.svg?version=<?= time() ?>" alt="" style="height: 21px;"></a>&nbsp;
    <a target="_blank" href="https://www.instagram.com/HungryForJobs/"><img src="https://hfj.menu.house/storage/app/public/app/icon/metro-instagram.svg?version=<?= time() ?>" alt="" style=" width: 20px;"></a>&nbsp;
    <a target="_blank" href="https://www.linkedin.com/company/hungryforjobs"><img src="https://hfj.menu.house/storage/app/public/app/icon/awesome-linkedin.svg?version=<?= time() ?>" alt="" style=" width: 20px;"></a>
    <a target="_blank" style="float: right;color:#fff;text-decoration:none;font-size:13px;">www.hungryforjobs.com</a>
</div>
</body>
</html>



