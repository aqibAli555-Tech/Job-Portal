<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;
use App\Models\Company;
use App\Models\Country;
use App\Helpers\Helper;
class CompanyController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use VerificationTrait;

    public function get_company(Request $request)
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        $title = 'Companies';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Companies',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.company.index', compact('countries','title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $company = Company::get_company($request);
        $company_count = Company::get_company_count($request);
        $data = [];
        foreach ($company as $key => $obj){
            $parent_company =$obj->user;
            $data[$key][] = '<td class="d-flex flex-column border-0"><img width="50" height="50" src="'. Helper::get_company_logo_AWS($obj).'"/><span><strong class="font-weight-bolder">'.trans("admin.Name").': </strong><small>'.$obj->name.'</small></span><br><span><strong class="font-weight-bolder">'.trans("admin.Country").':  </strong><img src="'.url()->asset('images/flags/16/' . strtolower($obj->country_code) . '.png').'"/></span></td>';
            $data[$key][] = '<td>'.$obj->posts()->count().' jobs</td>';
            $data[$key][] = '<td>'. !empty($parent_company) && !empty($parent_company->name) ? $parent_company->name : ''.'</td>';
            $data[$key][] = '<td>'.$obj->description.'</td>';         
            $data[$key][] = '<td>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                        <a href="'.admin_url('edit_company/' . $obj->id).'" class="dropdown-item"><i class="far fa-edit"></i>'.trans("admin.Edit").'</a>
                        <a href="'.admin_url('delete_company/' . $obj->id).'" class="dropdown-item" data-button-type="delete" onclick="return confirm(\'Are You Sure You want to delete?\')"><i class="far fa-trash"></i>'.trans('admin.Delete').'</a>
                    </div>
                </div>
            </td>';
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $company_count,
                'recordsFiltered' =>  $company_count,
                'data' => $data]
        );
        die;
    }

    public function delete_company($id = null)
    {
        $company = Company::find($id);
        $company->deleted_at = date('y-m-d');
        if ($company->save()) {
            flash('Deleted Successfully')->info();
            return redirect(admin_url('get_company'));
        } else {
            flash('Please Try Again')->info();
            return redirect(admin_url('get_company'));
        }
    }

    public function edit_company($id)
    {
        $country = Country::orderBy('name', 'ASC')->get();
        $company = Company::find($id);
        return view('admin.company.edit', compact('company', 'country'));


    }

    public function update_company(Request $request)
    {

        $company = Company::find($request->id);
        $company->name = $request->name;
        $company->description = $request->description;
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;
        if ($company->save()) {
            flash('Updated Successfully')->info();
            return redirect(admin_url('get_company'));
        } else {
            flash('Please Trey Agian')->info();
            return redirect(admin_url('edit_company  /' . $request->id));
        }
    }
}

?>