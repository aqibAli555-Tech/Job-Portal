<?php

namespace App\Http\Controllers\Admin;

use App\Models\PackageCancelReason;
use App\Models\RejectedReason;
use Illuminate\Http\Request;

class PackageCancelReasonController extends AdminBaseController
{
    public function index()
    {
        $cancel_reasons = PackageCancelReason::orderBy('title')->get();
        return view('vendor.admin.package-cancel-reason.index', compact('cancel_reasons'));

    }

    public function package_cancel_reasons_post(Request $request)
    {
        $data = [
            'title' => $request->input('title'),
            'status' => $request->input('status')
        ];
        if (empty($request->input('id'))) {
            PackageCancelReason::create($data);
            flash('Create Cancel Reason Successfully')->info();
        } else {
            PackageCancelReason::find($request->input('id'))->update($data);
            flash('Update Cancel Reason Successfully')->info();
        }
        return back();
    }

    public function package_cancel_reason_delete($id)
    {
        $data = PackageCancelReason::find($id);
        $data->delete();
        flash('Delete Cancel Reason Successfully')->info();
        return back();

    }


}
