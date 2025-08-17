<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Experience;
use App\Models\Skill;
use Illuminate\Http\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;

class SkillExperienceController extends PanelController
{
    public function view()
    {
        $data[0] = Skill::all();
        $data[1] = Experience::all();
        return view('vendor.admin.skillExperience')->with('data', $data);
    }


    public function skillAdd(Request $request)
    {
        $values = array(
            'name' => $request->get('name')
        );
        Skill::create($values);
        $data[0] = Skill::all();
        return redirect('admin/skillExperience')->with('data', $data);
    }

    public function experienceAdd(Request $request)
    {
        $values = array(
            'name' => $request->get('name')
        );
        Experience::create($values);
        $data[1] = Experience::all();
        return redirect('admin/skillExperience')->with('data', $data);
    }

    public function skillEdit(Request $request)
    {
        $values = array(
            'name' => $request->get('name')
        );
        Skill::where('id', $request->get('id'))
            ->update($values);
        $data[0] = Skill::all();
        return redirect('admin/skillExperience')->with('data', $data);
    }

    public function experienceEdit(Request $request)
    {
        $values = array(
            'name' => $request->get('name')
        );
        Experience::where('id', $request->get('id'))
            ->update($values);
        $data[1] = Experience::all();
        return redirect('admin/skillExperience')->with('data', $data);
    }

    public function skillDelete($id)
    {
        Skill::where('id', $id)->delete();
        $data[0] = Experience::all();
        return redirect('admin/skillExperience')->with('data', $data);
    }

    public function experienceDelete($id)
    {
        Experience::where('id', $id)->delete();
        $data[1] = Experience::all();
        return redirect('admin/skillExperience')->with('data', $data);
    }

    public function contact_us(Request $request)
    {
        $search = '%' . $request->search . '%';
        return view('vendor.admin.contact_us')->with('contact', Contact::where('first_name', 'like', $search)
            ->orWhere('last_name', 'like', $search)
            ->orWhere('email', 'like', $search)
            ->orWhere('message', 'like', $search)
            ->orderBy('id', 'DESC')
            ->paginate(20));
    }     
    public function contactDelete($id)
    {  
        Contact::where('id', $id)->delete();
        return redirect()->back();
    }
     public function contactsDelete(Request $request)
    {
        $ids=$request->get('contact_us_ids');
        $contact_us_ids = explode(",", $ids);
        if(!empty($contact_us_ids)){
            foreach($contact_us_ids as $value){
                 Contact::where('id', $value)->delete();
            }
        }
       echo 1;
       die;
    }

}