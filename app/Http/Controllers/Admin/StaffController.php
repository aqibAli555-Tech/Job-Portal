<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends AdminBaseController
{
    public function index(Request $request)
    {
        $title = 'Staffs';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Staffs',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.staff.index',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $data = [];
        $staff = User::get_staff_list($request);
        $staff_count = User::get_staff_list_count($request);
        $filtered = User::get_staff_list_count($request);
        if (!empty($staff)) {
            foreach ($staff as $key => $item) {
                $data[$key][] = '<td>'.$item->name.'</td>';
                $data[$key][] = '<td>'.$item->email.'</td>';
                $data[$key][] = '<td>'.$item->phone.'</td>';
                $data[$key][] = '<td>'.$item->company_email.'</td>';
                $data[$key][] = '<td>'.$item->company_name.'</td>';
                $data[$key][] = '<td>'.$item->created_at->format('Y-m-d').'</td>';
                $data[$key][] =
                '
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="'.admin_url('/staff/edit/').'/'.$item->id.'"><i class="far fa-edit"></i>Edit</a>
                            <a class="dropdown-item" href="'.admin_url('/staff/permissions/').'/'.$item->id.'"><i class="far fa-edit"></i>Permission</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="reset_pass('.$item->id.')"><i class="fa fa-key"></i>Reset Password</a>
                            <a class="dropdown-item" href="'.admin_url('/staff/delete/').'/'.$item->id.'" class="dropdown-item"><i class="far fa-trash-alt"></i>Delete</a>
                        </div>
                    </div>
                ';
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $staff_count,
                'recordsFiltered' =>  $filtered,
                'data' => $data]);
        die;
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
        // return view('vendor.admin.staff.edit', compact('staff'));
        return view('admin.staff.edit', compact('staff'));

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

        // return view('vendor.admin.staff.permissions')->with(['permissions' => $permissions, 'userPermissions' => $userPermissions]);
        return view('admin.staff.permissions')->with(['permissions' => $permissions, 'userPermissions' => $userPermissions]);
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
        return redirect('admin/staffs');
    }

}
