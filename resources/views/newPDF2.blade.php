<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/logoo.png" type="image/icon type">
    <title>Resume</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            margin-top: 100px;
            margin-left: 0px;
            margin-right: 0px;
            margin-bottom: 100px;
            font-family: sans-serif;
        }

        header {
            background-color: #615583;
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 100px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
            background-color: #615583;
            margin: 0px;
            color: white;
            font-size: 10px;
        }

        #footer-col-2 {
            width: 18%;
            text-align: center;
            padding-top: 15px;
            float: left;
        }

        .footer-2-center {
            width: 57%;
            text-align: center;
            padding-top: 15px;
            float: left;
        }

        .footer-col-2-right {
            padding-top: 18px;
            width: 25%;
            text-align: center;
            float: right;
        }


        .main-row {
            width: 100%;
            display: block;
        }

        .navbar-leftside {
            /* width: 70%; */
            text-align: left;
            float: left;
        }

        .navbar-leftside img {
            width: 90px;
            margin: 10px 10px;
        }

        .navbar-rightside {
            /* width: 20%; */
            text-align: center;
            float: right;
        }

        .navbar-rightside .ER_box {
            padding: 10px;
            text-align: left;
            margin: 18px;
        }

        .navbar-rightside .ER_box h2,
        h4 {
            color: white;
            margin: 0px 0px 5px;
        }


        /* Main thing is now happening */

        h1,
        h2,
        h3,
        h5,
        h6 {
            color: #615583;
        }


        .c-p {
            color: #A9A9A9;
            margin: 0px 0px 0px 0px;
        }

        .man-img {
            width: 200px;
        }

        .c-p p {
            margin: 4px 0px 0px 0px;
            font-size: 12px;
            color: #A9A9A9;
        }

        #leftside-col {
            padding: 22px 0px 0px 30px;
            /* width: 50%; */
            float: left;
        }

        .right-side-col {
            padding: 22px 0px 0px 10px;
            /* width: 43%; */
            float: left;
        }

        .about-p {
            color: #615583;
            width: 100%;
            text-align: justify;
            justify-content: center;
            font-size: 12px;
            font-weight: 100;
            margin: 20px 0px 25px 0px;
        }

        .about-h4 {
            color: #615583;
            font-size: 15px;
            margin: 15px 0px 10px 0px;
        }

        .connect {
            width: 65%;
            min-height: 140px;
            background-color: #f0f0f0;
            padding: 30px 50px;
            text-align: center;
        }

        .bttn {
            width: 90%;
            display: block;
            margin: 0px auto;
            padding-top: 15px;
            padding-left: 10px;
            background: #2ed6fd;
            border: none;
            border-radius: 0px;
            font-weight: 600;
            color: #3a3a3a;
            margin-bottom: 4px;
            font-size: 14px;
            height: 35px;
        }

        .bttn > img {
            width: 20px;
            float: left;

        }

        .bttn:focus {
            outline: none !important;
            box-shadow: none !important;
        }

    </style>
</head>

<body>
<div class="Main-Outter">

    <main>
        <table>
            <tr>
                <td style="width: 50%">
                    <div id="leftside-col">
                        <img src="{{ $img }}" alt="" class="man-img">
                        <div>
                            <h2 style="margin: 5px 0px 0px 0px;">{{ ucfirst($data->fullName) }} {{ ucfirst($data->fatherName) }}</h2>
                            <h5 style="margin: 5px 0px 0px 0px;">@foreach ($data->catgory as $cat)
                                {{ $cat }}
                                @if ($loop->index != count($data->catgory) - 1)
                                ,
                                @endif

                                @endforeach</h5>
                        </div>
                        <div>
                            <h4 class="about-h4">About US</h4>
                            <div>
                                <p class="about-p">
                                    {{ $data->about }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 12px;">
                                    <span><b style="color: #615583;">Address</b><span
                                                style="margin-left: 10px;color: #615583;">{{ $data->address }}</span> </span>
                            </div>
                            <br>
                        </div>
                        <div class="connect">
                            <p style="font-size: 15px;margin: 0px 0px 20px 0px;font-weight: 200;">Connect with this Employee through their Contact Card</p>
                            <button class="bttn"><img src="{{ url('/pdf/eye.png') }}"
                                                      alt=""> <a href="http://v2.hungryforjobs.com/#/viewresume/{{$data->id}}" target="_blank" style="color: black;text-decoration: none;">View Contact Card</a></button>
                            <button class="bttn"><img src="{{ url('/pdf/eye.png') }}"
                                                      alt=""><a href="http://v2.hungryforjobs.com/#/viewresume/{{$data->id}}" target="_blank" style="color: black;text-decoration: none;">View Profile</a></button>
                        </div>
                    </div>
                </td>
                <td style="width: 50%">
                    <div class="right-side-col">
                        <div>
                            <h2 style="font-size: 18px;">Skills and Experience</h2>
                        </div>
                        @foreach ($data->skill as $skil)
                        <div>
                            <h5 style="color: #000000">
                                {{ $data->sillExperienceComapny[$loop->index] }}
                            </h5>
                            <div class="c-p">
                                <p>{{ $data->sillExperienceCity[$loop->index] }},
                                    {{ $data->sillExperienceCoutry[$loop->index] }}
                                </p>
                                <p>{{ $skil }}</p>
                                <p>{{ $data->sillExperienceDis[$loop->index] }}</p>
                            </div>
                        </div>
                        @endforeach
                        <div>
                            <h2 style="font-size: 18px;">Education</h2>
                        </div>
                        @foreach ($data->university as $uni)
                        <div>
                            <div class="c-p">
                                <p style="color: #000000"><b>{{ $uni }}</b></p>
                                <p style="color: #000000"><b>{{ $data->degree[$loop->index] }}</b></p>
                                <p>{{ $data->dateStart[$loop->index] }} - {{ $data->dateEnd[$loop->index] }}</p>
                            </div>
                        </div>
                        @endforeach
                        <div>
                            <h2 style="font-size: 18px;">Interests</h2>
                        </div>
                        <div>
                            <div class="c-p">
                                @foreach ($data->interests as $interest)
                                <p>{{ ucfirst($interest) }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </main>
</div>
</body>
</html>
