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
        $title = 'Experiences';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Experiences',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.skillExperience',compact('title','breadcumbs'));
    }

    public function ajax_experience(Request $request)
    {
        $experience = Experience::all();
        $experience_count = Experience::count();
        $data = [];
        foreach ($experience as $key => $item) {
            if ($key % 2 == 0) {
                $class = 'even';
            } else {
                $class = 'odd';
            }
            $counter = $key + 1;
            $data[$key][] = '<td>' . $counter . '</td>';
            $data[$key][] = '<td>' . $item->name . '</td>';
            $data[$key][] = '<td>' . $item->created_at->format('Y-m-d') . '</td>';
            $data[$key][] = '<td>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="javascript:void(0)" onclick="ExpeEdit(' . $item->id . ' , \'' . $item->name . '\')"><i class="fas fa-edit"></i> Edit</a>
                        <a class="dropdown-item" href="' . admin_url('/skillExperience/experienceDelete') . '/' . $item->id . '"><i class="fas fa-trash-alt"></i> Delete</a>
                    </div>
                </div>
            </td>';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $experience_count,
                'recordsFiltered' => $experience_count,
                'data' => $data]);
        die;
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
        $title = 'Contact Us';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Contact Us',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.contact_us',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);

        $search = '%' . $request->search['value'] . '%';
        $contact = Contact::select('*');
        if (!empty($search)) {
            $contact = $contact->where('first_name', 'like', $search)
                ->orWhere('last_name', 'like', $search)
                ->orWhere('email', 'like', $search)
                ->orWhere('message', 'like', $search);
        }

        $contact = $contact->orderBy('id', 'DESC')->paginate($limit);
        $contact_count = Contact::count();
        $data = [];

        if (!empty($contact)) {
            foreach ($contact as $key => $item) {
                $data[$key][] = '<td><input type="checkbox" name="contact_us_id" class="checkbox" value="' . $item->id . '"></td>';
                $data[$key][] = '<td class="d-flex flex-column border-0">
                    <span>
                        <strong class="font-weight-bolder">' . trans('admin.Name') . ':</strong><small>' . $item->first_name . ' ' . $item->last_name . '</small></span>
                    <span>
                        <strong class="font-weight-bolder">' . trans('admin.Phone') . ':</strong><small>' . $item->phone . '</small>
                    </span>
                        <small class="text-info">' . $item->created_at . '</small>
                </td>';
                $data[$key][] = '<td>' . $item->email . '</td>';
                $data[$key][] = '<td>' . $item->user_type . '</td>';
                $data[$key][] = '<td>' . $item->message . '</td>';
                $data[$key][] = '<td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="delete_item(' . $item->id . ')"><i class="fa fa-trash-alt"></i>' . trans('admin.delete') . '</a>
                            </div>
                        </div>
                    </div>
                </td>';
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $contact_count,
                'recordsFiltered' => $contact_count,
                'data' => $data,
            ]
        );
        die;
    }

    public function contactDelete($id)
    {
        Contact::where('id', $id)->delete();
        return redirect()->back();
    }

    public function contactsDelete(Request $request)
    {
        $ids = $request->get('contact_us_ids');
        $contact_us_ids = explode(",", $ids);
        if (!empty($contact_us_ids)) {
            foreach ($contact_us_ids as $value) {
                Contact::where('id', $value)->delete();
            }
        }
        echo 1;
        die;
    }

}