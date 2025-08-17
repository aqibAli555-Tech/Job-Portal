<?php

namespace App\Http\Controllers\Admin;

use App\Models\Availability;
use Illuminate\Http\Request;
use Nexmo\Message\Shortcode\Alert;


class AvailabilityController extends AdminBaseController
{

    public function index()
    {
        $availability = Availability::orderBy('name')->get();        //get all data
        return view('vendor.admin.availability.index', compact('availability'));   // pass all data to view

    }


    public function add(Request $request)
    {
        $data = new Availability;
        $data->name = $request->name;
        $data->status = $request->status;
        $data->save();
        return redirect('admin/availability');
    }

    public function availabilityedit(Request $request)
    {
        $values = array(
            'name' => $request->input('name'),
            'status' => $request->input('status')
        );
        Availability::where('id', $request->input('id'))
            ->update($values);
        flash('Updated Successfully')->info();
        return redirect('admin/availability');
    }

    public function delete($id)
    {
        Availability::where('id', $id)->delete();
        flash('Deleted Successfully')->info();
        return redirect('admin/availability');

    }

    public function update_status(Request $request)
    {
        $id = $request->input('id');
        $post = Availability::find($id);
        if ($post->status == 0) {
            $status['status'] = 1;
        } else {
            $status['status'] = 0;
        }
        Availability::where('id', $id)->update($status);
        $post->save();
        if ($post->save()) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Agian')->info();
            echo 0;
            die;
        }
    }

    public function avai_edit(Request $request)
    {
        $id = $request->input('id');
        $data = Availability::where('id', $id)->first();
    }
}