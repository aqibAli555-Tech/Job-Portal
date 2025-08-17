<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends AdminBaseController
{
    public function index(Request $request)
    {
        $staff = User::get_staff_list($request);
        return view('vendor.admin.staff.index', compact('staff'));
    }

    public function staff_delete($id)
    {
        $data = User::find($id);
        if ($data->delete()) {
            flash('Delete Staff Successfully')->info();
            return redirect()->back();
        } else {
            flash('Please try again')->info();
            return redirect()->back();
        }


    }

    public function staff_edit($id)
    {
        $staff = User::find($id);
        return view('vendor.admin.staff.edit', compact('staff'));

    }

    public function staff_edit_post(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')
        ];
        if (!empty($request->input('id'))) {
            $user = User::find($request->input('id'));
            if ($user->update($data)) {
                flash('Update Staff Successfully')->info();
                return redirect('admin/staffs');
            } else {
                flash('Not updated data.Please try again')->info();
                return redirect()->back();
            }

        }

    }

    public function permissions($id)
    {
        $userPermissions = User::find($id);
        $permissions = [
            ['id' => 1, 'name' => 'My Profile'],
            ['id' => 2, 'name' => 'My Companies'],
            ['id' => 3, 'name' => 'My Jobs'],
            ['id' => 4, 'name' => 'Search Resumes'],
            ['id' => 5, 'name' => 'Favorite Resumes'],
            ['id' => 6, 'name' => 'Applicants'],
            ['id' => 7, 'name' => 'Saved Resumes'],
            ['id' => 8, 'name' => 'Unlocked Contact'],
            ['id' => 9, 'name' => 'Archived Jobs'],
            ['id' => 10, 'name' => 'Messenger'],
            ['id' => 11, 'name' => 'Transactions'],
            ['id' => 12, 'name' => 'Upgrade Account']
        ];

        return view('vendor.admin.staff.permissions')->with(['permissions' => $permissions, 'userPermissions' => $userPermissions]);
    }

    public function updatePermissions(Request $request)
    {

        $id = $request->get('id');
        if (!empty($request->get('permissions'))) {
            $permissions = implode(',', $request->get('permissions'));
        } else {
            $permissions = '';
        }
        $res = User::where('id', $id)->update(['permissions' => $permissions]);
        if ($res) {
            flash(t("Staff Permission updated successfully"))->info();
        } else {
            flash("Not deleted.Please try again")->info();
        }
        return redirect()->back();
    }

}
