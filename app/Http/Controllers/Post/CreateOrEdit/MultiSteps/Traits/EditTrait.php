<?php

namespace App\Http\Controllers\Post\CreateOrEdit\MultiSteps\Traits;

use App\Helpers\UrlGen;
use App\Http\Controllers\Post\CreateOrEdit\Traits\RetrievePaymentTrait;
use App\Http\Requests\PostRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyPackages;
use App\Models\EmployeeSkill;
use App\Models\Nationality;
use App\Models\Post;
use App\Models\PostDetails;
use App\Models\PostMeta;
use DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use App\Models\PostType;
use App\Models\SalaryType;
use App\Models\Skill;
use App\Helpers\Helper;

trait EditTrait
{
    use RetrievePaymentTrait;

    /**
     * Show the form the create a new ad post.
     *
     * @param $postIdOrToken
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function getUpdateForm($postIdOrToken)
    {
        $data = [];
        // Get Post
        if (request()->segment(2) == 'create') {
            if (!session()->has('tmpPostId')) {
                return redirect('posts/create');
            }
            $post = Post::with(['postDetail', 'postDetail.city', 'postMeta'])
                ->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('id', session('tmpPostId'))
                ->where('tmp_token', $postIdOrToken)
                ->first();
        } else {
            $post = Post::with(['postDetail', 'postDetail.city', 'postMeta'])
                ->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('user_id', auth()->user()->id)
                ->where('id', $postIdOrToken)
                ->first();
        }

        if (empty($post)) {
            abort(404);
        }

        view()->share('post', $post);

        // Share the Post's Latest Payment Info (If exists)
        $this->sharePostLatestPaymentInfo($post);

        // Get the Post's Company
        if (!empty($post->company_id)) {
            $data['postCompany'] = Company::find($post->company_id);
            view()->share('postCompany', $data['postCompany']);
        }

        // Get the Post's Administrative Division
        if (config('country.admin_field_active') == 1 && in_array(config('country.admin_type'), ['1', '2'])) {
            if (!empty($post->city)) {
                $adminType = config('country.admin_type');
                $adminModel = '\App\Models\SubAdmin' . $adminType;

                // Get the City's Administrative Division
                $admin = $adminModel::where('code', $post->city->{'subadmin' . $adminType . '_code'})->first();
                if (!empty($admin)) {
                    view()->share('admin', $admin);
                }
            }
        }
        $data['nationality'] = Nationality::pluck('id', 'name');
        $data['post_country_city'] = City::get_post_country_city($post->country_code);
        $data['employee_skills'] = EmployeeSkill::getAllskill();
        $data['valid_package']=CompanyPackages::check_company_has_premium_package();


        // Meta Tags
        view()->share([
            'title' => t('Update My Job Post'),
            'description' => t('Update My Job Post'),
            'keywords' => t('Update My Job Post'),
            // Add more variables as needed
        ]);

        return appView('post.createOrEdit.multiSteps.edit', $data);
    }

    /**
     * Update the Post
     *
     * @param $postIdOrToken
     * @param PostRequest $request
     * @return RedirectResponse|Redirector
     */
    public function postUpdateForm($postIdOrToken, PostRequest $request)
    {
        // Get Post
        if (request()->segment(2) == 'create') {
            if (!session()->has('tmpPostId')) {
                return redirect('posts/create');
            }
            $post = Post::where('id', session('tmpPostId'))->first();
        } else {
            $post = Post::where('user_id', auth()->user()->id)
                ->where('id', $postIdOrToken)
                ->first();
        }

        if (empty($post)) {
            abort(404);
        }
        if ($post->title != $request->title) {
            flash("You can not edit Position of the post")->error();
            return back()->withInput();
        }
        // Get the Post's City
        if (empty($request->company_id)) {
            $cityId = $request->company['city_id'];
            $country_code = $request->company['country_code'];
        } else {
            $company_data = Company::where('id', $request->company_id)->first();
            $cityId = $company_data->city_id;
            $country_code = $company_data->country_code;
        }

        if (!empty($request->city_id)) {
            $cityId = $request->city_id;
        }

        $city = City::find($cityId);
        if (empty($city)) {
            flash(t("Posting Ads is disabled"))->error();
            return back()->withInput($request->except('company.logo'));
        }


        // Get or Create Company
        if ($request->filled('company_id') && !empty($request->input('company_id'))) {
            // Get the User's Company
            $company = Company::where('id', $request->input('company_id'))->where('c_id', auth()->user()->id)->first();
        } else {
            $companyInfo = $request->input('company');
            if (!isset($companyInfo['country_code']) || empty($companyInfo['country_code'])) {
                $companyInfo += ['country_code' => config('country.code')];
            }

            // Logged Users
            if (!isset($companyInfo['user_id']) || empty($companyInfo['user_id'])) {
                $companyInfo += ['user_id' => auth()->user()->id];
            }

            // Store the User's Company
            $company = new Company($companyInfo);
            $company->save();
        }

        // Return error if company is not set
        if (empty($company)) {
            flash(t("Please select a company or New Company to create one"))->error();

            return back()->withInput($request->except('company.logo'));
        }

        /*
         * Allow admin users to approve the changes,
         * If the ads approbation option is enable,
         * And if important data have been changed.
         */

        // Update Post


        //save post

        $post_update = Post::Save_post_request($request, $country_code);
        $post_id = $post_update->id;
        if (!empty($post_id)) {
            $post_details = PostDetails::get_post_details_by_post_id($post_id);
            $post_meta = PostMeta::get_post_meta_by_post_id($post_id);
            PostDetails::save_post_details_request($request, $city, $post_id);
            PostMeta::save_post_meta_request($request, $post_id);
            $this->update_Activity_log($post, $post_details, $post_meta, $request);
        } else {
            flash(("Post Not Save Successfully.Please try Again"))->error();
            return back()->withInput();
        }


        $result = $post->save();
        if (empty($result)) {
            flash(t("Your post updated.Plz try again"))->error();
            return redirect()->back();
        }
        flash(t("Your job has been updated"))->success();
        return redirect(UrlGen::postUri($post));
    }

    public function update_Activity_log($post, $post_details, $post_meta, $request)
    {
        $user_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $post_url = UrlGen::postUri($post);
        $post_url = url()->to($post_url);
        $name = auth()->user()->name;

        $companyDescription['user_url'] = $user_url;
        $companyDescription['name'] = $name;
        $description = "A User Name: <b> <a href='$user_url'>$name</a></b> Updated his Jobs details:<b> <a href='$post_url'>$post->title</a></b> <br>";
        $changes = [];
        if ($post->category_id != $request->category_id) {
            $changes[] = "Skill : " . EmployeeSkill::get_skill_name_by_id($request->category_id) . " <br>";
        }

        if ($post->description != $request->description) {
            $changes[] = "Description : " . $request->description . " <br>";
        }
        if ($post->salary_min != $request->salary_min) {
            $changes[] = "Salary Min : " . $request->salary_min . " <br>";
        }
        if ($post->salary_max != $request->salary_max) {
            $changes[] = "Salary max : " . $request->salary_max . " <br>";
        }
        if ($post->salary_type_id != $request->salary_type_id) {
            $changes[] = "Salary Type: " . SalaryType::get_salary_type_name_by_id($request->salary_type_id) . " <br>";
        }

        if ($post->negotiable != $request->negotiable) {
            $negotiable = ($request->negotiable) ? 'Yes' : 'No';
            $changes[] = "Negotiable: " . $negotiable . " <br>";
        }
        if ($post->start_date != $request->start_date) {
            $changes[] = "Start date: " . $request->start_date . " <br>";
        }
        if ($post->as_soon != $request->as_soon) {
            $as_soon = ($request->as_soon) ? 'Yes' : 'No';
            $changes[] = "As Soon as possible: " . $as_soon . " <br>";
        }

        if ($post->contact_name != $request->contact_name) {
            $changes[] = "Contact name: " . $request->contact_name . " <br>";
        }

//        if (stripos($post->phone,$request->phone) !== true) {
//            dd(stripos($post->phone,$request->phone));
//            $changes[] = "Phone: " . $request->phone . " <br>";
//        }
        if ($post->email != $request->email) {
            $changes[] = "Email: " . $request->email . " <br>";
        }
        if ($post_details->who_can_apply != $request->who_can_apply) {
            if ($request->who_can_apply == 1) {
                $who_can_apply = 'Local Hire Only';
            } elseif ($request->who_can_apply == 2) {
                $who_can_apply = 'International Hire Only';
            } else {
                $who_can_apply = ' Both Local Hire & International Hire';
            }
            $changes[] = "Type of Hiring: " . $who_can_apply . " <br>";
        }

        if ($post_details->post_type != $request->post_type) {
            $post_type = '';
            if ($request->post_type == 1) {
                $post_type = 'Anyone with any Skills Sets';
            } elseif ($request->post_type == 2) {
                $post_type = 'Only specific Skills Sets (choose them below)';
            }
            $changes[] = "Type of Hiring: " . $post_type . " <br>";
        }


        if (explode(',', $post_details->skills_set) != $request->skill_set) {
            if (!empty($request->skill_set)) {
                $changes[] = "Skill Set: " . implode(',', $request->skill_set) . " <br>";
            }
        }

        if ($post_details->transportation_available != $request->transportation_available) {
            $transportation_available = ($request->transportation_available) ? 'Yes' : 'No';
            $changes[] = "Transportation available: " . $transportation_available . " <br>";
        }
        if ($post_details->overtime_pay != $request->overtime_pay) {
            $overtime_pay = ($request->overtime_pay) ? 'Yes' : 'No';

            $changes[] = "Overtime pay: " . $overtime_pay . " <br>";
        }
        if ($post_details->housing_available != $request->housing_available) {
            $housing_available = ($request->housing_available) ? 'Yes' : 'No';
            $changes[] = "Housing Available: " . $housing_available . " <br>";
        }
        if ($post_details->gender != $request->gender) {
            $changes[] = "Gender: " . $request->gender . " <br>";
        }

        if ($post_details->experiences != $request->experience) {
            $changes[] = "Experiences: " . $request->experience . " <br>";
        }
        if ($post_details->hide_company_logo != $request->hide_company_logo) {
            $hide_company_logo = ($request->hide_company_logo) ? 'Yes' : 'No';
            $changes[] = "Hide Company Logo: " . $hide_company_logo . " <br>";
        }
        if ($post->post_type_id != $request->post_type_id) {
            $changes[] = "Post type: " . PostType::get_post_type_name_by_id($request->post_type) . " <br>";
        }

        if ($post_meta->hide_salary != $request->hide_salary) {
            $hide_salary = ($request->hide_salary) ? 'Yes' : 'No';
            $changes[] = "Hide Salary: " . $hide_salary . " <br>";
        }

        if ($post_details->city_id != $request->city_id) {
            $city = City::get_city_name_by_id($request->city_id);
            $changes[] = "City: " . $city->name . " <br>";
        }


        if (explode(',', $post_details->nationality) != $request->nationality) {
            $nationality_names = Nationality::get_nationality_name_array($request->nationality);
            $nationality_names = implode(",", $nationality_names->toArray());
            $changes[] = "Nationality: " . $nationality_names . " <br>";
        }
        if (!empty($changes)) {
            $description .= implode(" ", $changes);
            $data['changes'] = $changes;
            $data['post_url'] = $post_url;
            $data['name'] = $post->title;
            $companyDescription['changes'] = $description;
            Helper::activity_log($description);
            $companyDescription = Helper::companyDescriptionData($data, 'job_post_edit');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
        }
    }

}
