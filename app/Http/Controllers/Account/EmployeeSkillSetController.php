<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Auth\Traits\RegistersUsers;
use App\Helpers\Helper;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\SkillRequest;
use App\Models\EmployeeSkill;
use Torann\LaravelMetaTags\Facades\MetaTag;

class EmployeeSkillSetController extends AccountBaseController
{
    use RegistersUsers, VerificationTrait;

    public $pagePath = 'skill_set';
    private $perPage = 10;

    public function index()
    {
        if (!Helper::check_permission(14)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        if (auth()->user()->parent_id != auth()->user()->id) {
            flash(t("Only Parent Company Can View This Page."))->error();
            return redirect('/');
        }
        // Get all User's Companies
        $EmployeeSkill = EmployeeSkill::getAllskillsSetsByUserId();
        // Meta Tags
        MetaTag::set('title', t('My EmployeeSkill List'));
        MetaTag::set('description', t('My EmployeeSkill List on', ['appName' => config('settings.app.app_name')]));

        return appView('account.skills.index')->with('EmployeeSkill', $EmployeeSkill);
    }

    public function save_skill_set(SkillRequest $request)
    {

        $new_skill = new EmployeeSkill;
        $new_skill->skill = $request->skill;
        $new_skill->employer_id = auth()->user()->id;

        if ($new_skill->save()) {
            flash("Create Skills Sets successfully ")->success();
            return redirect()->back();
        } else {
            flash("Please Try Again ")->error();
            return redirect()->back();
        }
    }

}
