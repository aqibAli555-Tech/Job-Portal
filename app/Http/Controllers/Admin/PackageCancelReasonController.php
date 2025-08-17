<?php

namespace App\Http\Controllers\Admin;

use App\Models\PackageCancelReason;
use Illuminate\Http\Request;

class PackageCancelReasonController extends AdminBaseController
{
    public function index()
    {
        $title = 'Package Cancel Reason';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Package Cancel Reason',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.package-cancel-reason',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $cancel_reasons = PackageCancelReason::orderBy('title')->get();
        $cancel_reasons_count = PackageCancelReason::orderBy('title')->count();
        $data = [];
        if (!empty($cancel_reasons)) {
            foreach ($cancel_reasons as $key => $item) {
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
                                <a class="dropdown-item" href="javascript:void(null)" onclick="CancelReasonEdit('.$item->id.',\''.$item->title.'\','.$item->status.')"><i class="far fa-edit"></i>'.trans('admin.edit').'</a>
                                <a class="dropdown-item" href="'.admin_url('/package_cancel_reason_delete/delete/') . '/' . $item->id.'"><i class="fa fa-trash-alt"></i>'.trans('admin.delete').'</a>
                            </div>
                        </div>
                    </div>
                </td>';
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $cancel_reasons_count,
                'recordsFiltered' =>  $cancel_reasons_count,
                'data' => $data,
            ]
        );
        die;
        
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
