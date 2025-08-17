<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeSkill;
use App\Models\Nationality;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ResumeSearchController extends FrontController
{
    public function searchresumes(Request $request)
    {
        if (!isset($request->cat, $request->country, $request->keyword, $request->city, $request->limit, $request->nationality,$request->sort)) {
            return redirect('/');
        }

        if (auth()->check()) {
            if (!Helper::check_permission(4)) {
                flash(t("You do not have permission to access this module"))->error();
                return redirect('/');
            }
        }

        $cacheKey = 'search_cv_' . md5(serialize($request->all()));
        $cacheExpiration = config('cache.stores.file.expire');

        $data['search_cv'] = Cache::remember($cacheKey, $cacheExpiration, function () use ($request) {
            // Your actual query here with filters
            return User::get_employee_list($request);
        });
        $cacheKey = 'emp_skills_with_user_count' . md5(serialize($request->all()));
        $data['emp_skills'] = Cache::remember($cacheKey, $cacheExpiration, function () use ($request) {
            // Your actual query here with filters
            return EmployeeSkill::getAllskillWithEmplyeeCount($request);
        });


        $data['countries'] = Cache::remember('countries_with_user_count', config('cache.stores.file.expire'), function () {
            // Your actual query here
            return Country::get_all_country_with_employee_count();
        });

        $cacheKey = 'cities_by_country_with_user_count' . md5(serialize($request->all()));
        $data['cities'] = Cache::remember($cacheKey, $cacheExpiration, function () use ($request) {
            // Your actual query here with filters
            return City::get_city_by_country_with_employee_count(request()->get('country'));
        });

        $data['nationalities'] = Cache::remember('nationalities_with_employee_count', config('cache.stores.file.expire'), function () {
            // Your actual query here
            return Nationality::get_nationalities_with_employee_count();
        });
        $title = $this->gettitleForSearchCV();
        $this->getBreadcrumb();
        $this->getHtmlTitle();
        
        $seo=Helper::getSeo('search-resume');
        view()->share([
            'title' => $seo['title'],
            'description' => $seo['description'],
            'keywords' => $seo['description'],
        ]);

        view()->share('pagePath', 'search-resumes');
        return view('account.search-resume')->with('data', $data);
    }

    public function send_bluk_email_by_employer(Request $request)
    {
        if (!empty($request['posts'])) {
            $post = Post::find($request['posts']);
        }
        $user_ids = explode(",", $request['user_ids']);
        if (!empty($user_ids)) {
            foreach ($user_ids as $value) {
                if (!empty($value)) {
                    $user = User::find($value);
                }


                if (!empty($post) && !empty($user)) {
                    $data['url'] = UrlGen::post($post);
                    $data['email'] = $user->email;
                    $data['subject'] = 'You might be interested in applying to this job!';
                    $data['employee'] = $user->name;
                    $data['position'] = $post->title;
                    $data['company'] = $post->company_name;
                    $data['view'] = 'emails.employer_share_post_on_bluk';
                    $data['header'] = 'You might be interested in applying to this job!';
                    $helper = new Helper();
                    $response = $helper->bulk_email_queue($data);
                }
            }
            if ($response) {
                flash('Post Share Successfully')->info();
                return redirect()->back();
            } else {
                flash('Please try again.')->error();
                return redirect()->back();
            }
        }
    }

}