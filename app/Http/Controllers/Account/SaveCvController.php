<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Models\Allsaved_resume;
use App\Models\Applicant;

class SaveCvController extends AccountBaseController
{
    public function Saved_Resume()
    {
        if (!Helper::check_permission(7)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }
        $pagePath = 'Saved-Resume';
        $data = Allsaved_resume::get_all_saved_cv();
        view()->share('pagePath', 'Saved-Resume');

        // Meta Tags
        view()->share([
            'title' => t('Saved CV'),
            'description' => t('Saved CV'),
            'keywords' => t('Saved CV'),
            // Add more variables as needed
        ]);
        return view('account.saved_resume')->with('data', $data);
    }

    public function remove_save_resume($id)
    {
        $save_cv = Allsaved_resume::where('applicant_id', $id)->where('user_id', auth()->user()->id)->first();

        $save_cv->delete();
        $applicant = Applicant::where('user_id', $id)->first();
        $data['employee_url'] = url('/profile/').'/'. $applicant->user->id;
        $data['employee_name'] = $applicant->user->name;
        $companyDescription = Helper::companyDescriptionData($data, 'remove_save_resume');
        if(!empty($companyDescription)){
            Helper::activity_log($companyDescription,auth()->user()->id);
        }
        return redirect()->back();
    }
}
