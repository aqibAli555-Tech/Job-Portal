@extends('layouts.master')

@section('content')
@include('common.spacer')
<style>
    .access-form .form-group .form-control {
        height: 50px;
        border: 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 0;
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        font-size: 1.4rem;
    }

    .form-header {
        background: #f5f5f5;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 50px;
    }

    .form-group {
        margin-bottom: 30px;
    }

    html {
        font-size: 62.5%;
    }

    .alice-bg {
        background: #f7f9fc;
    }

    .access-form .button {
        color: #ffffff;
        padding: 14px;
        font-family: "Poppins", sans-serif;
        font-weight: 500;
    }

    .primary-bg {
        background: #22d3fd;
    }

    .button {
        border: 1px solid transparent;
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        border-radius: 3px;
        cursor: pointer;
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .block-wrapper {
        background: #ffffff;
        padding: 60px 30px;
        -webkit-box-shadow: 0px 5px 20px 0px rgb(0 0 0 / 3%);
        box-shadow: 0px 5px 20px 0px rgb(0 0 0 / 3%);
    }

    .payment-result {
        text-align: center;
        color: #6f7484;
    }

    .payment-result h3 {
        font-size: 2.6rem;
        margin-top: 30px;
        margin-bottom: 10px;
        color: #101725;
    }

    .payment-result h3 span {
        color: #22d3fd;
        text-decoration: underline;
    }

    .payment-result > p {
        font-size: 1.8rem;
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        color: #6f7484;
    }

    .payment-result .result {
        margin-top: 60px;
        margin-bottom: 120px;
        border: 1px solid rgba(238, 238, 238, 0.15);
        border-radius: 3px;
        background: #f4f8ff;
        padding: 15px 20px;
        display: inline-block;
    }

    .payment-result .icon {
        height: 100px;
        width: 100px;
        background: #22d3fd;
        border-radius: 50%;
        text-align: center;
        line-height: 100px;
        margin: 0 auto;
    }
</style>
<!-- Breadcrumb -->
<div class="alice-bg padding-top-70 padding-bottom-70">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="breadcrumb-area">
                    <h1>Card Information</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/account')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Credit Card Credentials</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6">
            </div>
        </div>
    </div>
</div>

<div class=" alice-bg  padding-top-90 padding-bottom-90" id="info">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="access-form">
                    <div class="form-header">
                        <h5><i data-feather="credit-card"></i>{{$data->price}} {{$data->currency_code}}
                            <?php
                            $name = $data->name;
                            $name = json_decode($name, true);
                            $data->name = $name['en'];
                            // $item->name=$name[$data['lang_code']];
                            ?>
                            <?php
                            $short_name = $data->short_name;
                            $short_name = json_decode($short_name, true);
                            $data->short_name = $short_name['en'];
                            // $item->name=$name[$data['lang_code']];
                            ?>
                            <small style="float: right">{{$data->name}}</small>
                        </h5>

                    </div>
                    <form action="#" id="form" method="POST">
                        <input type="hidden" name="package_id" value="{{$data->id}}">
                        <input type="hidden" name="credits" value="{{$data->short_name}}">
                        <div class="form-group">
                            <input type="number" required="required" pattern="/^-?\d+\.?\d*$/"
                                   onKeyPress="if(this.value.length==16) return false;" name="number"
                                   placeholder="Card number (5105105105105100)"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" required="required" name="name"
                                   placeholder="Name on card (John Parker)"
                                   class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="exp" maxlength="5" minlength="5"
                                           placeholder="Expiration (MM/YY)" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" name="cvv" pattern="/^-?\d+\.?\d*$/"
                                           onKeyPress="if(this.value.length==3) return false;"
                                           placeholder="CVV (123)"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="number" name="country_code" required="required"
                                           placeholder="965" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="number" name="phone" required="required"
                                           placeholder="7348974" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" required="required"
                                   placeholder="Email address" class="form-control">
                        </div>
                        <p id="err" style="color: #ff3e33;display: none;">Invalid credentials *</p>
                        <button type="button" onclick="submitForm()" class="button primary-bg btn-block">Submit
                            <img id="isLoad"
                                 style="width: 22px;display: none"
                                 src="{{ url('public//images/images/spinning-wheel.gif')}}"
                                 alt="">
                        </button>
                        <br>
                    </form>
                </div>
            </div>
            <div class="col-xl-8 col-md-6">
                <div class="access-form">
                    <div class="form-header">
                        <h5><i data-feather="flag"></i>Supported Cards</h5>
                    </div>
                </div>
                <img src="{{ url()->asset('/images/images/card.png')}}" style="width: 45%; margin: auto;display: block"
                     alt="">
                <br>
                <br>
                <img src="{{ url()->asset('/images/images/flag.png')}}" style="width: 85% ; margin: auto;display: block"
                     alt="">
            </div>
        </div>
    </div>
</div>
<div class="alice-bg section-padding-bottom" id="completed" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="block-wrapper">
                    <div class="payment-result">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <h3>You Unlocked <span>{{$data->name}}</span> Package</h3>
                        <p>Thanks for your order!</p>
                        <div class="result" style="margin-bottom: 0px !important;;">
                            <span>Your payment has been processed successfully.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // $('#form').submit(function (e) {
    function submitForm() {
        // e.preventDefault();
        // e.stopPropagation();
        // alert("asd");
        // console.log("Form Data :"+$('#form').serialize());
        // return;
        var AjaxURL = '<?= lurl('account/credentials/action')?>';
        $.ajax({
            type: "POST",
            url: AjaxURL,
            data: $('#form').serialize(),
            beforeSend: function () {
                $('#err').hide();
                $('#isLoad').show();
            },
            success: function (result) {
                console.log(result);
                if (result == "fail") {
                    $('#err').show();
                    $('#isLoad').hide();
                } else {
                    $('#info').hide();
                    $('#isLoad').hide();
                    $('#completed').show();
                }
            }
        });
    }

    // });
</script>
@endsection
