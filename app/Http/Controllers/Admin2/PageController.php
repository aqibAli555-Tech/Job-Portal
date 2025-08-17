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
        $pages = Page::get_pages($request);
        return view('vendor.admin.pages.index', compact('pages'));
    }

    public function pages_edit($id)
    {
        $pages = Page::find($id);
        return view('vendor.admin.pages.edit', compact('pages'));
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