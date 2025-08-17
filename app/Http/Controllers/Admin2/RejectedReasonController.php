<?php

namespace App\Http\Controllers\Admin;

use App\Models\RejectedReason;
use Illuminate\Http\Request;

class RejectedReasonController extends AdminBaseController
{
    public function index()
    {
        $rejected_reasons = RejectedReason::orderBy('title')->get();
        return view('vendor.admin.rejected-reason.index', compact('rejected_reasons'));

    }

    public function rejected_reason_post(Request $request)
    {
        $data = [
            'title' => $request->input('title'),
            'status' => $request->input('status')
        ];
        if (empty($request->input('id'))) {
            RejectedReason::create($data);
            flash('Create Rejected Reason Successfully')->info();
        } else {
            RejectedReason::find($request->input('id'))->update($data);
            flash('Update Rejected Reason Successfully')->info();
        }
        return back();
    }

    public function rejected_reason_delete($id)
    {
        $data = RejectedReason::find($id);
        $data->delete();
        flash('Delete Rejected Reason Successfully')->info();
        return back();

    }


}
