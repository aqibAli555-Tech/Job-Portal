<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostArchivedOrCancleReason;
use Illuminate\Http\Request;

class PostArchivedReasonController extends AdminBaseController
{

    public function index()
    {
        $post_reasons = PostArchivedOrCancleReason::orderBy('title')->get();
        return view('vendor.admin.post-archived-reason.index', compact('post_reasons'));
    }

    public function post_archived_reasons_post(Request $request)
    {
        $data = [
            'title' => $request->input('title'),
            'status' => $request->input('status')
        ];
        if (empty($request->input('id'))) {
            PostArchivedOrCancleReason::create($data);
            flash('Reason Created Successfully')->info();
        } else {
            PostArchivedOrCancleReason::find($request->input('id'))->update($data);
            flash('Reason Update Successfully')->info();
        }
        return back();
    }

    public function post_archived_reason_delete($id)
    {
        $data = PostArchivedOrCancleReason::find($id);
        $data->delete();
        flash('Reason Deleted Successfully')->info();
        return back();

    }

}
