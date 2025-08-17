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
        $title = 'Payments';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Payments',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.payments',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $payments = Payment:: get_all_payments($request);
        $payments_count = Payment:: get_all_payments_count($request);
        $data = [];
        if (!empty($payments)){
            foreach ($payments as $key => $payment){
                if(!empty($payment->user) && !empty($payment->package)){
                    $data[$key][] = '<td>'.$payment->id.'</td>';
                    $data[$key][] = '<td>'.$payment->created_at.'</td>';
                    $row = '';
                    $row .= '<p>'.$payment->package->name.'</p><strong>Package Price : </strong> $'.$payment->package->price;
                    if($payment->is_refunded==1){
                        $row .= '<div class="badge text-bg-primary" style="float: right;">Refunded</div>';
                    }
                    if(!empty($payment->discount_value)){
                        $discount = $payment->discount_type === 'percentage' ? $payment->discount_value . '%' : '$' . $payment->discount_value;
                        $row .= '<br><strong>Discount : </strong>' . $discount . '<br><strong>Package price after discount : </strong>$' . $payment->amount;
                    }
                    $data[$key][] = '<td>'.$row.'</td>';
                    $data[$key][] ='<td><p>'.$payment->user->name.'</p></td>';
                }
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $payments_count,
                'recordsFiltered' =>  $payments_count,
                'data' => $data,
            ]
        );
        die;
    }
}

?>