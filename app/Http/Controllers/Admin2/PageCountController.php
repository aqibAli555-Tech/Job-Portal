<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;

use App\Models\PageCount;
use App\Models\Statistic;
use Illuminate\Http\Request;

class PageCountController extends AdminBaseController
{
    use VerificationTrait;

    public function get_all_page_count(Request $request)
    {
        $data['facebook_link_click'] = PageCount::get_page_count('facebook_link_click');
        $data['twitter_link_click'] = PageCount::get_page_count('twitter_link_click');
        $data['instagram_link_click'] = PageCount::get_page_count('instagram_link_click');
        $data['tiktok_link_click'] = PageCount::get_page_count('tiktok_link_click');
        $data['linkedin_link_click'] = PageCount::get_page_count('linkedin_link_click');
        $data['pinterest_link_click'] = PageCount::get_page_count('pinterest_link_click');
        $data['contact_us'] = PageCount::get_page_count('contact_us');
        $data['faq'] = PageCount::get_page_count('faq');
        $data['privacy'] = PageCount::get_page_count('privacy');
        $data['terms'] = PageCount::get_page_count('terms');
        $data['home_page'] = PageCount::get_page_count('home_page');
        $data['logo_click'] = PageCount::get_page_count('logo_click');
        $data['register_page'] = PageCount::get_page_count('register_page');
        $data['login_users'] = PageCount::get_page_count('login_users');
        $data['user_registered'] = PageCount::get_page_count('user_registered');
        $data['search_cv_page'] = PageCount::get_search_page_count('search_cv');
        $data['search_jobs_page'] = PageCount::get_search_page_count('search_jobs');
        $data['post_details'] = PageCount::get_search_page_count('post_details');
        $data['applied_users'] = PageCount::get_search_page_count('applied_users');
        return view('vendor.admin.analytics')->with($data);
    }

    public function get_analytics_details()
    {
        $data['page_title'] = request()->get('type');
        $data['data'] = PageCount::get_all_search_page_count(request()->get('type'));

        return view('vendor.admin.analytics_details')->with($data);
    }

    public function statistics()
    {
        $data['statistics'] = Statistic::get_home_page_statistic();
        return view('vendor.admin.statistics')->with($data);
    }

    public function update_statistics(Request $request)
    {
       if(Statistic::where('id',1)
           ->update(['employees'=>$request->employees,'companies'=>$request->companies,'jobs'=>$request->jobs])){
           flash('Statistics Updated Successfully')->success();
           return redirect()->back();
       }else{
           flash('Unable to update statistics')->success();
           return redirect()->back();
       }
    }
}
