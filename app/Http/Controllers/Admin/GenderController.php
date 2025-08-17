<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;
use App\Models\Gender;

class GenderController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use VerificationTrait;

    public function get_gender()
    {
        $title = 'Genders';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Genders',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.gender',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $gender = Gender::paginate(10);
        $gender_count = Gender::count();
        $data = [];
        foreach ($gender as $key => $obj){
            $data[$key][] = '<td>'.$obj->name.'</td>';
            $data[$key][] = '<td>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="javascript:void(0)" onclick="editmodal('.$obj->id.',\''.$obj->name.'\')"><i class="fas fa-edit"></i> Edit</a>
                        <a class="dropdown-item" href="'.admin_url('delete_title').'/'.$obj->id.'"><i class="fas fa-trash-alt"></i> Delete</a>
                    </div>
                </div>
            </td>';
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $gender_count,
                'recordsFiltered' =>  $gender_count,
                'data' => $data]);
        die;

    }

    public function post_title(Request $request)
    {
        $data = ['name' => $request->post('name'), 'translation_lang' => 'en'];
        $id = $request->get('id');
        $gender = $id ? Gender::findOrFail($id) : Gender::create($data);
        $gender->update($data);
        flash($id ? 'Updated Title Successfully' : 'Create Title Successfully')->info();
        return back();
    }

    public function delete_title($id)
    {
        Gender::find($id)->delete();
        flash('Delete Title Successfully')->info();
        return back();
    }
}
