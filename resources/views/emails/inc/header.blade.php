<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        @media only screen and (max-width: 1000px) {
            .main {
                width: 85% !important;
            }
        }
    </style>
</head>
<body>
<div style=" background: #ecf8fd;min-height:100vh;">
    <br>
    <div class="main" style="display: block;margin: 0 auto; background: white; width: 50%; max-width:500px ">
        <div class="header" style="background: #5e567f; min-height: 50px; width: 100%;">
            <table style="width: 100%;">
                <tr>
                    <td><img src="{{ asset('images/logo.png') }}" alt=""
                             style="width: 150px;max-width:100%; margin-top: 10px;vertical-align:middle">
                        <a href="{{url('/')}}" target="_blank"
                           style="color: #7ed0de; font-size: 12px;padding-right:10px;float:right; margin-top:20px;text-decoration:none;" ;>www.hungryforjobs.com</a>
                    </td>
                </tr>
                <tr>
                    <td style="color: white; padding-left: 10px;padding-bottom:10px;font-weight:bold;font-size:18px">
                        {{$header}}
                    </td>
                </tr>
            </table>
        </div>
