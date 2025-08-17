<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostType;
use Illuminate\Http\Request;

class PostTypeController extends AdminBaseController
{
    public function index()
    {
        $posttypes = PostType::orderBy('name')->get();
        return view('vendor.admin.posttype.index', compact('posttypes'));

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