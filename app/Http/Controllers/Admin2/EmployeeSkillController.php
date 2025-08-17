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
        $employeeskills = EmployeeSkill::getAllskillsSets();
        return view('vendor.admin.employeeskill', compact('employeeskills'));
    }

    public function skillAdd(Request $request)
    {

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if ($file->isValid()) {$tempPath = $file->getRealPath();
                list($width, $height) = getimagesize($tempPath);
                if ($width == 380 && $height == 568) {
                    $newFilename = date('YmdHis') . mt_rand() . '.jpg';
                    $file->move('storage/employee_skill/', $newFilename);
                    $image = 'employee_skill/' . $newFilename;
                    } else {
                    $message="Image dimensions are not correct. Expected: 380x568, Actual: {$width}x{$height}.";
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
                    $message="Image dimensions are not correct. Expected: 380x568, Actual: {$width}x{$height}.";
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
        if(!empty($skill->image)){
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
        return view('vendor.admin.send_email')->with('data', $data);
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
            flash("Status Update Faild")->error();
            return back();
        }
    }

    public function delete_skill($id)
    {
        $skill = EmployeeSkill::find($id);
        if ($skill->status == 0) {
            if ($skill->delete()) {
                flash("Skill deleted successfully")->success();
                return back();
            } else {
                flash("Unable to delete")->error();
                return back();
            }
        } else {
            flash("Unable to delete")->error();
            return back();
        }
    }

}
