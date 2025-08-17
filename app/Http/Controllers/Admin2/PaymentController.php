<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;
use App\Models\Payment;


class PaymentController extends AdminBaseController
{
    use VerificationTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function payments(Request $request)
    {
        $payments = Payment:: get_all_payments($request);
        return view('vendor.admin.paymentSetting.payments', compact('payments'));
    }

}

?>