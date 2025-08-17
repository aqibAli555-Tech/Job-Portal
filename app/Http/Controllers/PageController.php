<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Traits\Sluggable\PageBySlug;
use App\Http\Requests\ContactRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Permission;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Session;
use Torann\LaravelMetaTags\Facades\MetaTag;

class PageController extends FrontController
{
    use PageBySlug;

    public function __construct()
    {
        parent::__construct();
    }

    public static function userCompanies()
    {
        $data = Company::where('c_id', auth()->user()->id)->get();
        return $data;
    }


    public function cms($slug)
    {

        $data['page'] = $slug;
        $data['from'] = url()->previous();
        $data['server'] = json_encode(request()->server());
        $data['request'] = request();
        
        $data['quary_parameter'] = '';
        Helper::page_count_post($data);
        // Get the Page
        $page = $this->getPageBySlug($slug);
        if (empty($page)) {
            abort(404);
        }

        $seo = Helper::getSeo($slug);

        $title = ucfirst($slug);
        view()->share('title', $seo['title']);
        view()->share('description', $seo['description']);
        view()->share('keywords', $seo['description']);
        view()->share('page', $page);
        view()->share('uriPathPageSlug', $slug);
        // Check if an external link is available
        if (!empty($page->external_link)) {
            return redirect()->away($page->external_link, 301)->withHeaders(config('larapen.core.noCacheHeaders'));
        }

        // SEO
        $title = $page->title;
        $description = Str::limit(str_strip($page->content), 200);

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description);
        if (!empty($page->picture)) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
            $this->og->image(imgUrl($page->picture, 'bgHeader'), [
                'width' => 600,
                'height' => 600,
            ]);
        }
        view()->share('og', $this->og);
        return appView('pages.cms');
    }


    public function checkuserpackage()
    {
        if (auth()->check()) {
            $today = date('Y-m-d');
            if ((int)auth()->user()->remaining_credits > 0 && (strtotime(auth()->user()->post_expire) > strtotime($today))) {
                echo 1;
                die;
            } else {
                echo 0;
                die;
            }
        }
    }

    public function userdata()
    {
        Auth::logout();
    }

    public function contact()
    {
        // Get the Country's largest city for Google Maps
        $cacheId = config('country.code') . '.city.population.desc.first';
        $city = Cache::remember($cacheId, $this->cacheExpiration, function () {
            $city = City::currentCountry()->orderBy('population', 'desc')->first();

            return $city;
        });
        view()->share('city', $city);
        // Meta Tags
        $title = t('contact-us');
        $seo = Helper::getSeo('contact');

        view()->share('title', $seo['title']);
        view()->share('description', $seo['description']);
        view()->share('keywords', $seo['description']);
        MetaTag::set('title', getMetaTag('title', 'contact'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
        MetaTag::set('keywords', getMetaTag('keywords', 'contact'));

        return appView('pages.contact');
    }

    public function contactPost(ContactRequest $request)
    {
        if (empty($request->get('h-captcha-response'))) {
            flash('Please fill recaptcha ')->error();
            return redirect()->back();
        }

        $data = array(
            'secret' => "0x32d1e6e430AD3233A43bd88C676469ca0FbdCa9b",
            'response' => $request->get('h-captcha-response')
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $responseData = json_decode($response);

        if ($responseData->success) {

            $contact = new Contact();
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->user_type = $request->user_type;
            $contact->phone = $request->countryCode . $request->phone;
            $contact->email = $request->email;
            $contact->message = $request->message;
            $contact->created_at = date('Y-m-d');
            $contact->save();
            // Store Contact Info
            $contactForm = $request->all();
            $contactForm['country_code'] = config('country.code');
            $contactForm['country_name'] = config('country.name');
            $contactForm = ArrayHelper::toObject($contactForm);
            $admins = User::permission(Permission::getStaffPermissions())->get();
            if ($admins->count() > 0) {
                foreach ($admins as $admin) {

                    $this->sendconatcemail($request, $admin);
                }
            }

            flash(t('message_sent_to_moderators_thanks'))->success();
        } else {
            flash('Recaptcha is not verified! Please try again')->success();
        }
        return redirect(UrlGen::contact());
    }

    public function sendconatcemail($request, $admin)
    {
        $data['email'] = $admin->email;
        $data['subject'] = 'New message from Hungry For Jobs';
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['country_code'] = config('country.code');
        $data['country_name'] = config('country.name');
        $data['email_address'] = $request->email;
        $data['phone'] = $request->countryCode . $request->phone;
        $data['user_type'] = $request->user_type;
        $data['user_message'] = $request->message;
        $data['header'] = 'Contact Us';
        $data['view'] = 'emails.contact_us';
        $helper = new Helper();
        $helper->send_email($data);
    }
    public function makemoney()
    {
        $seo = Helper::getSeo('affiliate-program');

        view()->share('title', $seo['title']);
        view()->share('description', $seo['description']);
        view()->share('keywords', $seo['description']);
        MetaTag::set('title', getMetaTag('title', 'makemoney'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'makemoney')));
        MetaTag::set('keywords', getMetaTag('keywords', 'makemoney'));
        return appView('pages.makemoney');
    }
}
