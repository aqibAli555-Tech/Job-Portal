<?php

namespace App\Http\Controllers\Admin;

use App\Models\Causes;
use App\Models\Entities;
use Illuminate\Http\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;

class EntityCausesController extends PanelController
{
    public function view()
    {
        $data[0] = Entities::orderBy('name')->get();
        $data[1] = Causes::orderBy('name')->get();
        return view('vendor.admin.entityCauses')->with('data', $data);
    }

    public function entityAdd(Request $request)
    {
        $values = array(
            'name' => $request->get('name')
        );
        Entities::create($values);
        $data[0] = Entities::orderBy('name')->get();
        return redirect('admin/entityCauses')->with('data', $data);
    }

    public function causesAdd(Request $request)
    {
        // dd($request->get('name']);
        $values = array(
            'name' => $request->get('name')
        );
        Causes::create($values);
        $data[1] = Causes::orderBy('name')->get();
        return redirect('admin/entityCauses')->with('data', $data);
    }

    public function entityEdit(Request $request)
    {
        $values = array(
            'name' => $request->get('name')
        );
        Entities::where('id', $request->get('id'))
            ->update($values);
        $data[0] = Entities::orderBy('name')->get();
        return redirect('admin/entityCauses')->with('data', $data);
    }

    public function causesEdit(Request $request)
    {
        $values = array(
            'name' => $request->get('name')
        );
        Causes::where('id', $request->get('id'))
            ->update($values);
        $data[1] = Causes::orderBy('name')->get();
        return redirect('admin/entityCauses')->with('data', $data);
    }

    public function entityDelete($id)
    {
        Entities::where('id', $id)->delete();
        $data[0] = Causes::orderBy('name')->get();
        return redirect('admin/entityCauses')->with('data', $data);
    }

    public function causesDelete($id)
    {
        Causes::where('id', $id)->delete();
        $data[1] = Causes::orderBy('name')->get();
        return redirect('admin/entityCauses')->with('data', $data);
    }

}
