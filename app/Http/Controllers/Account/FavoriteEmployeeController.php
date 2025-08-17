<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Models\EmployeeSkill;
use App\Models\Favoriteresume;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteEmployeeController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function favoriteresumes()
    {
        if (!Helper::check_permission(5)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission Error.."))->error();
            return redirect('/');
        }
        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }

        $pagePath = 'favorite-resumes';
        $data['resume'] = Favoriteresume::get_favorter_cv();
        $data['employee_skills'] = EmployeeSkill::getAllskill();
        view()->share('pagePath', 'favorite-resumes');

        // Meta Tags
        view()->share([
            'title' => t('Favorite CV'),
            'description' => t('Favorite CV'),
            'keywords' => t('Favorite CV'),
            // Add more variables as needed
        ]);

        return view('account.favorite-resume')->with('data', $data);
    }

    public function addtofavorite(Request $request, $id)
    {
        $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $company_name = auth()->user()->name;
        $applicant = user::where('id', $id)->first();
        $employee_url = admin_url() . '/job-seekers?search=' . $applicant->email;
        $employee_name = $applicant->name;
        $data['employee_url'] = url('/profile/').'/'.$applicant->id;
        $data['employee_name'] = $employee_name;
        if (!empty($id)) {
            if (!empty($request->get('remove'))) {
                $resume_data = Favoriteresume::where('user_id', $id)->where('company_id', auth()->user()->id)->delete();
                if ($resume_data) {
                    $favorite_Employee = "Company : <a href='$profile_url'>$company_name</a> Remove favorite Employee CV:  <a href='$employee_url'>$employee_name</a>";
                    Helper::activity_log($favorite_Employee);
                    $companyDescription = Helper::companyDescriptionData($data, 'remove_favourite');
                    if(!empty($companyDescription)){
                        Helper::activity_log($companyDescription,auth()->user()->id);
                    }
                    flash('CV has been removed to your favorites.')->success();
                    return redirect()->back();
                } else {
                    flash('Something went wrong')->error();
                    return redirect()->back();
                }
            } else {

                $fav = new Favoriteresume();
                $fav->user_id = $id;
                $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
                $company_name = auth()->user()->name;
                $applicant = user::where('id', $id)->first();
                $employee_url = admin_url() . '/job-seekers?search=' . $applicant->email;
                $employee_name = $applicant->name;
                $fav->company_id = auth()->user()->id;
                if ($fav->save()) {
                    $favorite_Employee = "Company : <a href='$profile_url'>$company_name</a> add into favorite Employee CV:  <a href='$employee_url'>$employee_name</a>";
                    Helper::activity_log($favorite_Employee);
                    $companyDescription = Helper::companyDescriptionData($data, 'add_favourite');
                    if(!empty($companyDescription)){
                        Helper::activity_log($companyDescription,auth()->user()->id);
                    }
                    flash('CV has been added to your favorites.')->success();
                    return redirect()->back();
                } else {
                    flash('Something went wrong')->error();
                    return redirect()->back();
                }
            }
        } else {
            flash('User not found')->error();
            return redirect()->back();
        }
    }
}
