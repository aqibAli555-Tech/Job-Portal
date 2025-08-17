<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostType;
use Illuminate\Http\Request;

class PostTypeController extends AdminBaseController
{
    public function index()
    {
        $title = 'Post Types';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Post Types',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.post_type',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $posttypes = PostType::orderBy('name')->get();
        $posttypes_count = PostType::count();
        $data = [];
        foreach ($posttypes as $key => $item) {
            $counter = $key+1;
            $data[$key][] = '<td>'.$counter.'</td>';
            $data[$key][] = '<td>'.$item->name.'</td>';
            $row = '';
            if ($item->active == 1) {
                $row .= "Active";
            } else {
               $row .= "Inactive";
            }
            $data[$key][] = '<td>'.$row.'</td>';

            // $data[$key][] = '<td>'.$item->created_at.'</td>';
            $data[$key][] = '<td>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                            <a class="dropdown-item" href="javascript:void(null)" onclick="post_type_edit('.$item->id.',\''.$item->name.'\','.$item->active.')"><i class="far fa-edit"></i>'.trans('admin.edit').'</a>
                            <a class="dropdown-item" href="'.admin_url('/post_type_delete/').'/'.$item->id.'"><i class="fa fa-trash-alt"></i>'.trans('admin.delete').'</a>
                        </div>
                    </div>
                </div>
            </td>';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $posttypes_count,
                'recordsFiltered' =>  $posttypes_count,
                'data' => $data,
            ]
        );
        die;

    }

    public function post_type_post(Request $request)
    {
        $data = [
            'name' => $request->input('post_type_name'),
            'active' => $request->input('status')
        ];
        if (empty($request->input('post_type_id'))) {
            $post_type = new PostType();
            if ($post_type->create($data)) {
                flash('Create Post Type Successfully')->info();
            } else {
                flash('Post type not created.Please try again')->info();
            }

        } else {
            $post_type = PostType::find($request->input('post_type_id'));
            if ($post_type->update($data)) {
                flash('Update Post Type Successfully')->info();
            } else {
                flash('Post type not updated. Please try again')->info();
            }

        }
        return redirect()->back();
    }

    public function post_type_delete($id)
    {
        $data = PostType::find($id);
        if ($data->delete()) {
            flash('Delete Post Type Successfully')->info();
        } else {
            flash('Post type not deleted. Please try again')->info();
        }
        return redirect()->back();


    }


}