<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Request;
use App\Models\Page;

class PageController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $title = 'Home Pages';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Home Pages',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.pages.index',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $pages = Page::get_pages($request);
        $pages_count = Page::get_pages_count($request);
        $data = [];

        if($pages->count() > 0){
            foreach ($pages as $key => $obj){
                $data[$key][] = '<td>'.$obj->name.'</td>';
                $data[$key][] = '<td>'.$obj->title.'</td>';
                $row = '';
                $row .='<a href="javascript:void(null)" onclick="update_status('.$obj->id.')" data-table="status" data-field="status" data-line-id="update_status'.$obj->id.'" data-id="'.$obj->id.'" data-value="1"><i id="update_status'.$obj->id.'" class="admin-single-icon fa';
                if ($obj->active == 1) {
                   $row .= " fa-toggle-on";
                } else {
                   $row .= " fa-toggle-off";
                }
                $row .= ' aria-hidden="true"></i></a>';
                $data[$key][] = '<td>'.$row.'</td>';
                $data[$key][] = '<td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btm-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <a class="dropdown-item" href="'.admin_url('pages_edit/' . $obj->id).'"><i class="far fa-edit"></i>'.trans('admin.edit').'</a>
                            </div>
                        </div>
                    </div>
                </td>';
            }
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $pages_count,
                'recordsFiltered' =>  $pages_count,
                'data' => $data,
            ]
        );
        die;
    }

    public function pages_edit($id)
    {
        $pages = Page::find($id);
        // return view('vendor.admin.pages.edit', compact('pages'));
        return view('admin.settings.pages.edit', compact('pages'));
    }

    public function update_pages(Request $request)
    {
        $pages = Page::find($request->input('id'));
        $pages->name = $request->input('name');
        $pages->slug = $request->input('slug');
        $pages->external_link = $request->input('external_link');
        $pages->title = $request->input('title');
        $pages->content = $request->input('content');
        $pages->type = $request->input('type');
        $pages->active = $request->input('active');

        if ($pages->save()) {
            flash('Updated Successfully')->info();
            return redirect(admin_url('pages'));
        } else {
            flash('Please Trey Agian')->info();
            return redirect(admin_url('pages_edit  /' . $request->input('id')));
        }
    }

    public function update_status(Request $request)
    {
        $id = $request->input('id');
        $pages = Page::find($id);
        if ($pages->active == 0) {
            $active['active'] = 1;
        } else {
            $active['active'] = 0;
        }
        Page::where('id', $id)->update($active);
        $pages->save();
        if ($pages->save()) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Agian')->info();
            echo 0;
            die;
        }
    }
}

?>