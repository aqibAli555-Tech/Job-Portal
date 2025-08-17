<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;
use App\Models\Company;
use App\Models\Country;

class CompanyController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use VerificationTrait;

    public function get_company(Request $request)
    {
        $company = Company::get_company($request);
        $countries = Country::orderBy('name', 'ASC')->get();
        return view('vendor.admin.company.index', compact('company', 'countries'));
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
        return view('vendor.admin.company.edit', compact('company', 'country'));


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