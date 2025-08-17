{{--
* JobClass - Job Board Web Application
* Copyright (c) BedigitCom. All Rights Reserved
*
* Website: https://bedigit.com
*
* LICENSE
* -------
* This software is furnished under a license and may be used and copied
* only in accordance with the terms of such license and with the inclusion
* of the above copyright notice. If you Purchased from CodeCanyon,
* Please read the full License from here - http://codecanyon.net/licenses/standard
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            background-color: #f6f6f6;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 15px;
            box-sizing: border-box;
        }

        .card {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .top-section {
            background-color: #e9e9e9;
            padding: 30px;
            text-align: center;
        }

        .top-section img {
            width: 100px;
            height: auto;
            margin-bottom: 15px;
        }

        .top-section p {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .bottom-section {
            background-color: #ffffff;
            padding: 20px;
        }

        .bottom-section hr {
            border: 1px solid #000;
            margin: 10px 0;
        }

        .bottom-section p {
            margin: 0;
            font-size: 16px;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .footer-text {
            font-size: 12px;
            margin-top: 20px;
            text-align: center;
        }

        .footer-logo img {
            width: 40px;
            height: 50px;
            display: block;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .top-section img {
                width: 80px;
            }

            .top-section p {
                font-size: 18px;
            }

            .bottom-section p {
                font-size: 14px;
            }

            .footer-text {
                font-size: 10px;
            }

            .card {
                padding: 10px;
            }

            .flex-container {
                display: block;
                text-align: center;
            }

            .flex-container p {
                margin-bottom: 5px;
            }
        }

        @media (max-width: 576px) {
            .top-section p {
                font-size: 16px;
            }

            .footer-text {
                font-size: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: small; padding-left: 10px;">{{ t('Receipt') }} {{ @$invoice->transaction_id }}</p>
            <p style="font-size: small; padding-right: 10px;">{{ $invoice->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="card">
            <div class="top-section">
                <div style="padding: 2px;">
                    <div style="text-align: center; margin-bottom: 5px; margin-top:10px;">
                        <img src="{{url()->asset('/icon/tap-new.png')}}" alt="{{ strtolower(config('settings.app.app_name')??'') }}" class="dark-logo img-fluid"/>
                    </div>
                    <p style="font-size: 20px;">{{ t('Hungry For Jobs') }}</p>
                    <p style="text-align: center; margin: 10px 0; font-size: 40px; font-family:sans-serif;">USD{{$invoice->amount}}</p>
                </div>
            </div>

            <div class="bottom-section">
                <div class="flex-container">
                    <p>{{$invoice->package['name']}}</p>
                    <p>USD{{$invoice->amount}}</p>
                </div>

                <hr>

                <div class="flex-container">
                    <p>{{t('Total')}}</p>
                    <p>USD{{$invoice->amount}}</p>
                </div>

                <hr>

                <div style="padding: 20px; margin-top: 40px;">
                    <p style="text-align: center; margin: 0;">{{t('Billed To')}}, {{$company_data->name}}</p>
                    <p style="text-align: center;">{{$company_data->phone}}</p>
                    <p style="text-align: center;">{{$company_data->email}}</p>
                </div>

                <div style="padding: 20px; margin-top: 20px;">
                    <p style="text-align: center;">{{t('Please retain this receipt for your records.')}}</p>
                    <p style="text-align: center;">{{t('Users are advised to read the terms and conditions carefully.')}}</p>
                    <p style="text-align: center;">{{t('We respect your privacy.')}}</p>
                </div>
            </div>

            <div class="footer-text">
                <p>{{ t('Please do not reply to this email. This mailbox is not monitored & you will not receive a reply.') }}</p>
                <p>{{ t('Tap is a brand of the International Trading Group.') }}</p>
                <p>{{ t('TM and Copyright ©') }}<span id="currentYear"></span></p>
                <p>{{ t('All Rights Reserved.') }}</p>
            </div>

            <div class="footer-logo">
                <img src="{{url()->asset('/icon/tap-payment.png')}}" alt="{{ strtolower(config('settings.app.app_name')??'') }}" class="dark-logo img-fluid"/>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    var currentYear = new Date().getFullYear();
    document.getElementById("currentYear").textContent = " " + currentYear + ".";
</script>
