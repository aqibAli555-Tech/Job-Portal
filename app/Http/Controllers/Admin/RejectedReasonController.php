<?php

namespace App\Http\Controllers\Admin;

use App\Models\RejectedReason;
use Illuminate\Http\Request;

class RejectedReasonController extends AdminBaseController
{
    public function index()
    {
        $title = 'Rejected Reason';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Rejected Reason',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.rejected-reason',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {

        $rejected_reasons = RejectedReason::get_reasons($request);
        $rejected_reasons_count = RejectedReason::get_reasons_count($request);

        $data = [];

        if (!empty($rejected_reasons)) {
            foreach ($rejected_reasons as $key => $item) {
                $counter = $key+1;
                $data[$key][] = '<td>'.$counter.'</td>';
                $data[$key][] = '<td>'.$item->title.'</td>';
                $row = '';
                if ($item->status == 1) {
                    $row .= "Active";
                } else {
                   $row .= "Inactive";
                }
                $data[$key][] = '<td>'.$row.'</td>';
                $data[$key][] = '<td>'.$item->created_at->format('Y-m-d').'</td>';
                $data[$key][] = '<td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                            <a class="dropdown-item" href="javascript:void(null)" onclick="RejectedReasonEdit('.$item->id.',\''.$item->title.'\','.$item->status.')"><i class="far fa-edit"></i>'.trans('admin.edit').'</a>
                            <a class="dropdown-item" href="'.admin_url('/rejected_reasons/delete/').'/'.$item->id.'"><i class="fa fa-trash-alt"></i>'.trans('admin.delete').'</a>
                        </div>
                    </div>
                </td>';
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $rejected_reasons_count,
                'recordsFiltered' =>  $rejected_reasons_count,
                'data' => $data,
            ]
        );
        die;
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
