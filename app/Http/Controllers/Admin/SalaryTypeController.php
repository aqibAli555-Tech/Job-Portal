<?php

namespace App\Http\Controllers\Admin;

use App\Models\SalaryType;
use Illuminate\Http\Request;

class SalaryTypeController extends AdminBaseController
{
    public function index()
    {
        $title = 'Salary Type';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Salary Type',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.salarytype',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $salarytypes = SalaryType::orderBy('name')->get();
        $salarytypes_count = SalaryType::count();
        $data = [];
        foreach ($salarytypes as $key => $item) {
            $counter = $key+1;
            $data[$key][] = '<td>'.$counter.'</td>';
            $data[$key][] = '<td>'.$item->name.'</td>';
            $row = '';
            if ($item->active == 1) {
                $row .= "Active";
            } else {
               $row .= "Inactive";
            }
            $data[$key][] = '<td>'.$row.'</td>';
            $data[$key][] = '<td>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                            <a class="dropdown-item" href="javascript:void(null)" onclick="salary_type_edit('.$item->id.',\''.$item->name.'\','.$item->active.')"><i class="far fa-edit"></i>'.trans('admin.edit').'</a>
                            <a class="dropdown-item" href="'.admin_url('/salary_type_delete/').'/'.$item->id.'"><i class="fa fa-trash-alt"></i>'.trans('admin.delete').'</a>
                        </div>
                    </div>
                </div>
            </td>';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $salarytypes_count,
                'recordsFiltered' =>  $salarytypes_count,
                'data' => $data,
            ]
        );
        die;
    }

    public function salary_type_post(Request $request)
    {
        $data = [
            'name' => $request->input('salary_type_name'),
            'active' => $request->input('status')
        ];
        if (empty($request->input('salary_type_id'))) {
            $salary_type = new SalaryType();
            if ($salary_type->create($data)) {
                flash('Create Salary Type Successfully')->info();
            } else {
                flash('Salary type not created.Please try again')->info();
            }

        } else {
            $salary_type = SalaryType::find($request->input('salary_type_id'));
            if ($salary_type->update($data)) {
                flash('Update Salary Type Successfully')->info();
            } else {
                flash('Salary type not updated. Please try again')->info();
            }

        }
        return redirect()->back();
    }

    public function salary_type_delete($id)
    {
        $data = SalaryType::find($id);
        if ($data->delete()) {
            flash('Delete Salary Type Successfully')->info();
        } else {
            flash('Salary type not deleted. Please try again')->info();
        }
        return redirect()->back();


    }


}