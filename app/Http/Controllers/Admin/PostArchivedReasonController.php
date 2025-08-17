<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostArchivedOrCancleReason;
use Illuminate\Http\Request;

class PostArchivedReasonController extends AdminBaseController
{
    public function index()
    {
        $title = 'Post Archived Or Delete Reason';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Post Archived Or Delete Reason',
                'link' => 'javascript:void(0)'
            ]
        ];
        return view('admin.post-archived-reason.index', compact('title', 'breadcumbs'));
    }

//
    public function ajax(Request $request)
    {
        $post_reasons = PostArchivedOrCancleReason::orderBy('title')->get();
        $post_reasons_count = PostArchivedOrCancleReason::orderBy('title')->count();
        $data = [];
        if (!empty($post_reasons)) {
            foreach ($post_reasons as $key => $item) {
                $counter = $key + 1;
                $data[$key][] = '<td>' . $counter . '</td>';
                $data[$key][] = '<td>' . $item->title . '</td>';
                $row = '';
                if ($item->status == 1) {
                    $row .= "Active";
                } else {
                    $row .= "Inactive";
                }
                $data[$key][] = '<td>' . $row . '</td>';
                $data[$key][] = '<td>' . $item->created_at->format('Y-m-d') . '</td>';
                $data[$key][] = '<td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <a class="dropdown-item" href="javascript:void(null)" onclick="CancelReasonEdit(' . $item->id . ',\'' . $item->title . '\',' . $item->status . ')"><i class="far fa-edit"></i>' . trans('admin.edit') . '</a>
                                <a class="dropdown-item" href="' . admin_url('/post_archived_reason_delete/delete/') . '/' . $item->id . '"><i class="fa fa-trash-alt"></i>' . trans('admin.delete') . '</a>
                            </div>
                        </div>
                    </div>
                </td>';
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $post_reasons_count,
                'recordsFiltered' => $post_reasons_count,
                'data' => $data,
            ]
        );
        die;

    }


    public function index1()
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
