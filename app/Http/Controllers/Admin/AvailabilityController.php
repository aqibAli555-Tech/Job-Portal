<?php

namespace App\Http\Controllers\Admin;

use App\Models\Availability;
use Illuminate\Http\Request;
use Nexmo\Message\Shortcode\Alert;


class AvailabilityController extends AdminBaseController
{

    public function index()
    {
        $title = 'Availability';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Availability',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.availability',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $availability = Availability::orderBy('name')->get();
        $availability_count = Availability::count();
        $data = [];
        if (!empty($availability)) {
            foreach ($availability as $key => $item) {
                $counter = $key+1;
                $data[$key][] = '<td>'.$counter.'</td>';
                $data[$key][] = '<td>'.$item->name.'</td>';
                $row = '';
                $row .= '<a href="javascript:void(null)" onclick="update_status('.$item->id.')" data-table="availability" data-field="status" data-line-id="update_status'.$item->id.'" data-id="'.$item->id.'" data-value="1"><i id="update_status'.$item->id.'" class="admin-single-icon fa';
                if ($item->status == 1) {
                   $row .= " fa-toggle-on";
                } else {
                   $row .= " fa-toggle-off";
                }
                $row .= ' aria-hidden="true"></i></a>';
                $data[$key][] = '<td>'.$row.'</td>';
                $data[$key][] = '<td>'.$item->created_at->format('Y-m-d').'</td>';
                $data[$key][] = '<td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btm-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <a class="dropdown-item" href="javascript:void(null)" onclick="availabilityEdit('.$item->id.', \''.$item->name.'\', \''.$item->status.'\')"><i class="far fa-edit"></i>'.trans('admin.edit').'</a>
                            </div>
                        </div>
                    </div>
                </td>';;
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $availability_count,
                'recordsFiltered' =>  $availability_count,
                'data' => $data,
            ]
        );
        die;
    }


    public function add(Request $request)
    {
        $data = new Availability;
        $data->name = $request->name;
        $data->status = $request->status;
        $data->save();
        return redirect('admin/availability');
    }

    public function availabilityedit(Request $request)
    {
        $values = array(
            'name' => $request->input('name'),
            'status' => $request->input('status')
        );
        Availability::where('id', $request->input('id'))
            ->update($values);
        flash('Updated Successfully')->info();
        return redirect('admin/availability');
    }

    public function delete($id)
    {
        Availability::where('id', $id)->delete();
        flash('Deleted Successfully')->info();
        return redirect('admin/availability');

    }

    public function update_status(Request $request)
    {
        $id = $request->input('id');
        $post = Availability::find($id);
        if ($post->status == 0) {
            $status['status'] = 1;
        } else {
            $status['status'] = 0;
        }
        Availability::where('id', $id)->update($status);
        $post->save();
        if ($post->save()) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Agian')->info();
            echo 0;
            die;
        }
    }

    public function avai_edit(Request $request)
    {
        $id = $request->input('id');
        $data = Availability::where('id', $id)->first();
    }
}