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
        $gender = Gender::paginate(10);
        return view('vendor.admin.users.gender.index', compact('gender'));
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
