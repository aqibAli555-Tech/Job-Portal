<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\UserType;
use App\Models\Gender;
use App\Models\User;
use App\Models\Country;
class ProfileController extends AdminBaseController
{
	// public $userType;
	public function index()
    {
    	$user = UserType::active()->get();

        $userType = [];
        if ($user->count() > 0) {
            foreach ($user as $entry) {
                $userType[$entry->id] = $entry->name;
            }
        }

        $gender = Gender::query()->get();

        $gender = collect($gender)->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        })->toArray();

        $userData = User::where('is_admin',1)->first()->toArray();

        $country = Country::where('active', 1)->get();
        $countries = [];
        if ($user->count() > 0) {
            foreach ($country as $entry) {
                $countries[$entry->code] = $entry->name;
            }
        }

    	$title = 'Profile';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Profile',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.profile.index',compact('title','breadcumbs','userType','gender','userData','countries'));
    }

    public function update_account(Request $request)
    {
    	if(!empty($request->email)){
    		$user = User::where('email',$request->email)->first();
    		if(!empty($user)){
    			$user->user_type_id = $request->user_type_id;
    			$user->gender_id = $request->gender_id;
    			$user->username = $request->username;
    			$user->phone = $request->phone;
    			$user->country_code = $request->country_code;
    			if($user->save()){
    				flash('Updated Successfully')->info();
            		return redirect()->back();
    			}
    		}
    	}
        
        flash('Unable to updated record')->info();
         return redirect()->back();
    }
}