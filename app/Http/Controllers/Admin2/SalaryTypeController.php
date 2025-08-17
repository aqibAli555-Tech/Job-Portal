<?php

namespace App\Http\Controllers\Admin;

use App\Models\SalaryType;
use Illuminate\Http\Request;

class SalaryTypeController extends AdminBaseController
{
    public function index()
    {
        $salarytypes = SalaryType::orderBy('name')->get();
        return view('vendor.admin.salarytype.index', compact('salarytypes'));

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