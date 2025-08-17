<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\EmployeeSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;

class EmployeeSkillController extends PanelController
{
    public function view()
    {
        $title = 'Employee Skill';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Employee Skill',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.employeeskill', compact('title', 'breadcumbs'));
    }

    public function view1()
    {
        $employeeskills = EmployeeSkill::getAllskillsSets();
        return view('vendor.admin.employeeskill', compact('employeeskills'));
    }

    public function ajax(Request $request)
    {
        $employee_skills = EmployeeSkill::getAllskillsSets($request);
        $employee_skills_count = EmployeeSkill::getAllskillsSetsCount();
        $employee_skills_count_all = EmployeeSkill::getAllskillsSetsCount(true);
        $data = [];

        foreach ($employee_skills as $key => $item) {
            if (!empty($item->image) && file_exists(public_path('/') . 'storage/' . $item->image)) {
                $image = $item->image;
            } else {
                $image = 'pictures/default.jpg';
            }

            $data[$key][] = '<img class="img" style="height: 50px; width:50px;border-radius:50%" src="' . url('/public/storage/' . $image) . '">';
            $data[$key][] = $item->skill;
            $row = '';
            if ($item->status == 0) {
                $row .= '<a href="' . admin_url('employeeSkill/updateStatus/' . $item->id) . '" class="badge badge-danger" style="background: red;
    color: white">Pending</a>';
            } else {
                $row .= '<span class="badge badge-success">' . trans('admin.approved') . '</span>';
            }
            $data[$key][] = $row;
            $row = '';
            if (!empty($item->user)) {
                $row .= '<a href="' . admin_url('get_employer?search=' . $item->user->email) . '">' . $item->user->name . '</a>';
            }
            $data[$key][] = $row;
            if ($item->add_feature == 1) {
                $toggle = 'fa-toggle-on';
            } else {
                $toggle = 'fa-toggle-off';
            }
            $data[$key][] = '<td><a href="" onclick="add_feature(' . $item->id . ',' . $item->add_feature . ')" data-table="items" data-field="add_feature" data-line-id="add_feature' . $item->id . '" data-id="' . $item->id . '" data-value="1"><i id="add_feature' . $item->id . '" class="admin-single-icon fa ' . $toggle . '" aria-hidden="true"></i></a></td>';
            $data[$key][] = $item->created_at->format('Y-m-d');
            $data[$key][] = '<div class="btn-group" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item" href="#" onclick="SkillEdit(\'' . $item->id . '\', \'' . addslashes($item->skill) . '\', \'' . $item->status . '\', \'' . url('/public/storage/' . $image) . '\')">
            <i class="far fa-edit"></i>' . trans('admin.edit') . '
        </a>';

            if ($item->status == 0) {
                $data[$key][6] .= '<a class="dropdown-item" href="javascript:void(null)" onclick="delete_skill(\'' . $item->id . '\')"><i class="fas fa-trash-alt"></i> Delete</a>';
            }
            $data[$key][6] .= '</div></div>';

        }
        return response()->json(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $employee_skills_count_all,
                'recordsFiltered' => $employee_skills_count,
                'data' => $data,
            ]
        );
    }

    public function skillAdd(Request $request)
    {

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if ($file->isValid()) {
                $tempPath = $file->getRealPath();
                list($width, $height) = getimagesize($tempPath);
                if ($width == 380 && $height == 568) {
                    $newFilename = date('YmdHis') . mt_rand() . '.jpg';
                    $file->move('storage/employee_skill/', $newFilename);
                    $image = 'employee_skill/' . $newFilename;
                } else {
                    $message = "Image dimensions are not correct. Expected: 380x568, Actual: {$width}x{$height}.";
                    flash($message)->error();
                    return back();

                }

            } else {
                $image = 'pictures/default.jpg';
            }
        } else {
            $image = 'pictures/default.jpg';
        }

        $values = array(
            'skill' => $request->post('skill'),
            'image' => $image,
            'status' => 1,
        );
        EmployeeSkill::create($values);
        return redirect('admin/employeeSkill');
    }

    public function skillEdit(Request $request)
    {

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if ($file->isValid()) {
                $tempPath = $file->getRealPath();
                list($width, $height) = getimagesize($tempPath);
                if ($width == 380 && $height == 568) {
                    $newFilename = date('YmdHis') . mt_rand() . '.jpg';
                    $file->move('storage/employee_skill/', $newFilename);
                    $image = 'employee_skill/' . $newFilename;
                } else {
                    $message = "Image dimensions are not correct. Expected: 380x568, Actual: {$width}x{$height}.";
                    flash($message)->error();
                    return back();

                }

            } else {
                $image = $request->post('old_image');
            }
        } else {
            $image = $request->post('old_image');
        }
        $skill = EmployeeSkill::find(request()->post('id'));
        $values = array(
            'skill' => $request->post('skill'),
            'image' => $image,
            'status' => $request->post('status'),
        );

        EmployeeSkill::where('id', request()->post('id'))
            ->update($values);
        $skill_image = 'storage/' . $skill->image;
        if (!empty($skill->image)) {
            if (file_exists($skill_image)) {
                unlink($skill_image);
            }
        }
        $employeeskills = EmployeeSkill::getAllskillsSets();
        return redirect('admin/employeeSkill')->with('employeeskills', $employeeskills);
    }

    public function add_feature(Request $request)
    {
        $id = $request->post('id');
        $featured = $request->post('featured');
        $item = EmployeeSkill::find($id);
        if ($featured == 0) {
            $item->add_feature = 1;
        } else {
            $item->add_feature = 0;
        }

        if ($item->save()) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Agian')->info();
            echo 1;
            die;
        }
    }

    public function send_email()
    {
        $data['users'] = User::where('user_type_id', request()->get('user_type'))->get();

        $title = 'Send Emails';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Send Emails',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.send_email', compact('title', 'breadcumbs'))->with('data', $data);
    }

    public function send_email_post(Request $request)
    {

        if (empty($request->users[0])) {
            $userData = User::all();
        } else {
            $userData = User::withoutGlobalScopes()->whereIn('id', $request->users)->get();
        }

        if (!empty($userData)) {
            foreach ($userData as $user) {
                $data['email'] = $user->email;
                $data['subject'] = $request->subject;
                $data['myName'] = $user->name;
                $data['content'] = $request->message;
                $data['view'] = 'emails.general_email';
                $data['header'] = $request->subject;
                $helper = new Helper();
                $response = $helper->send_email($data);
            }
            flash("Email send successfully")->success();
            return back();
        } else {
            flash("Email Not send.")->error();
            return back();
        }
    }

    public function updateStatus($id)
    {
        $skill = EmployeeSkill::find($id);
        if ($skill->status == 1) {
            $skill->status = 0;
        } else {
            $skill->status = 1;
        }
        if ($skill->save()) {
            flash("Status Update successfully")->success();
            return back();
        } else {
            flash("Status Update Failed")->error();
            return back();
        }
    }

    public function delete_skill($id)
    {
        $pincode = request()->get('pincode');
        if (!empty($pincode) && $pincode == 'hungry') {
            $skill = EmployeeSkill::find($id);
            if ($skill->status == 0) {
                if ($skill->delete()) {
                    $response = array(
                        'status' => true,
                        'message' => "Skill Delete Successfully",
                        'url' => '',
                    );
                    return response()->json($response);
                } else {
                    $response = array(
                        'status' => false,
                        'message' => "Unable to delete",
                        'url' => '',
                    );
                    return response()->json($response);
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => "Unable to delete",
                    'url' => '',
                );
                return response()->json($response);
            }
        } else {
            $response = array(
                'status' => false,
                'message' => t("Your Pin Code Invalid"),
                'url' => '',
            );
            return response()->json($response);
        }
    }
}
