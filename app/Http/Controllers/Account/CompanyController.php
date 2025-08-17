<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Auth\Traits\RegistersUsers;
use App\Helpers\Helper;
use App\Helpers\Ip;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\CompanyRequest;
use App\Models\Causes;
use App\Models\City;
use App\Models\EmployeeLogo;
use App\Models\Company;
use App\Models\Country;
use App\Models\Entities;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Imagick;

class CompanyController extends AccountBaseController
{
    use RegistersUsers, VerificationTrait;

    public $pagePath = 'companies';
    private $perPage = 10;

    public function __construct()
    {
        parent::__construct();

        $this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;

        view()->share('pagePath', $this->pagePath);
    }

    public function index()
    {


        if (!Helper::check_permission(2)) {
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
        $companies = $this->companies->get()->sortBy('id');

        // Meta Tags
        view()->share([
            'title' => t('My Companies List'),
            'description' => t('My Companies List on', ['appName' => config('settings.app.app_name')]),
            'keywords' => '',
            // Add more variables as needed
        ]);
        return appView('account.company.index')->with('companies', $companies);
    }

    public function create()
    {
        if (!Helper::check_permission(2)) {
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

        // Meta Tags
        view()->share([
            'title' => 'Create a new company',
            'description' => '',
            'keywords' => '',
            // Add more variables as needed
        ]);


        $data['entities'] = Entities::orderBy('name')->get();
        return appView('account.company.create')->with('data', $data);
    }

    public function store(CompanyRequest $request)
    {
        if (!Helper::check_permission(2)) {
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
        $check_email = Company::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('email', $request->company['email'])->first();
        $check_email_in_user = User::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('email', $request->company['email'])->first();
        if (empty($request->company['city_id'])) {
            flash("Please select a city first")->error();
            return back()->withInput();
        }
        $cityid1 = $request->company['city_id'];
        if (empty($cityid1)) {
            flash(t("Please select a city first"))->error();
            return back()->withInput();
        }

        if (!empty($check_email) || !empty($check_email_in_user)) {
            flash('Email is already in use')->error();
            return back()->withInput();
        }


        $companyInfo = $request->get('company');
        if (!isset($companyInfo['user_id']) || empty($companyInfo['user_id'])) {
            $companyInfo += ['user_id' => auth()->user()->id];
        }
        if (!isset($companyInfo['country_code']) || empty($companyInfo['country_code'])) {
            $companyInfo += ['country_code' => config('country.code')];
        }
        if (!empty($companyInfo['phone'])) {
            if (strlen($companyInfo['phone']) < 6) {
                flash('Please enter valid phone number')->error();
                return back()->withInput();
            }
        }


        $user = new User();
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }
        $user->country_code = config('country.code');
        $user->city = $request->company['city_id'];
        $user->language_code = config('app.locale');
        $user->password = Hash::make('!!HFJ@2022!!');
        $user->ip_addr = Ip::get();
        $user->verified_email = 1;
        $user->verified_phone = 1;
        $user->user_type_id = 1;
        $user->name = !empty($companyInfo['name']) ? $companyInfo['name'] : '';
        $user->username = !empty($companyInfo['name']) ? $companyInfo['name'] : '';
        $user->phone = !empty($companyInfo['phone']) ? $companyInfo['phone'] : '';
        $user->email = !empty($companyInfo['email']) ? $companyInfo['email'] : '';
        $user->parent_id = auth()->user()->id;
        $user->password_without_hash = '!!HFJ@2022!!';
        $user->last_login_at = date('Y-m-d H:i:s');

        // Email verification key generation

        // Save

        $user->save();


        // Create the User's Company
        $company = new Company($companyInfo);

        $entities = implode(",", $request->entities);
        $company->entities = $entities;
        $company->c_id = $user->id;
        $company->save();

        flash(t("Your company has created successfully"))->success();
        $name = auth()->user()->name;
        $company_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $child_company_url = admin_url() . '/employer?search=' . $companyInfo['email'];
        $child_company_name = $companyInfo['name'];
        $description = "<b>A Employer:<a href='$company_url'>$name </a> created a child company :<a href='$child_company_url'> $child_company_name</a>";
        Helper::activity_log($description);
        $data['child_company_url'] = url('account/companies');
        $data['child_company_name'] = $child_company_name;
        $companyDescription = Helper::companyDescriptionData($data, 'child_company_create');
        if(!empty($companyDescription)){
            Helper::activity_log($companyDescription,auth()->user()->id);
        }
        
        $file = !empty($request->file('company')['logo']) ? $request->file('company')['logo'] : '';
        if (!empty($file)) {
            $file_type = $file->getClientOriginalExtension();
            $destinationPath = public_path('/') . 'storage/pictures/kw/' . $user->id;
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $fileName = 'pictures/kw/' . $user->id . '/' . time() . "." . $file_type;
            $file->move($destinationPath, $fileName);

            $url = url('public/storage/' . $fileName);
            $unique = 'thumbnail_' . uniqid() . '.jpg';
            Helper::generate_thumbnail($url, $user->id, $unique);
            
            $company->thumbnail = 'pictures/kw/' . $user->id . '/' . $unique;
            $company->logo = $fileName;
            $company->save();

            $values = array(
                'file' => $fileName,
                'thumbnail' => 'pictures/kw/' . $user->id . '/' . $unique
            );
            User::where('id', $user->id)->update($values);
        }

        // Redirection
        return redirect('account/companies');
    }

    public function edit($id)
    {
        if (!Helper::check_permission(2)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission Error."))->error();
            return redirect('/');
        }
        if (auth()->user()->parent_id != auth()->user()->id) {
            flash(t("Only Parent Company Can View This Page."))->error();
            return redirect('/');
        }
        // Get the Company
        $company = Company::where('id', $id)->firstOrFail();
        // Meta Tags
        view()->share([
            'title' => t('Edit the Company'),
            'description' => t('Edit the Company on', ['appName' => config('settings.app.app_name')]),
            'keywords' => '',
            // Add more variables as needed
        ]);
        $company['entitiess'] = Entities::orderBy('name', 'asc')->get();

        return appView('account.company.edit')->with('company', $company);
    }


    public function update($id, CompanyRequest $request)
    {
        if (!Helper::check_permission(2)) {
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

        $company_data = Company::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('user_id', auth()->user()->id)->first();
        if (!empty($request->entities)) {
            $entities = implode(',', $request->entities);
        } else {
            $entities = [];
        }

        $old_company_data = Company::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $request->company_id)->first();
        $check_email = Company::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('c_id', '!=', $old_company_data->c_id)->where('email', $request->company['email'])->first();
        $check_email_in_user = User::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', '!=', $old_company_data->c_id)->where('email', $request->company['email'])->first();
        if (!empty($check_email) || !empty($check_email_in_user)) {
            flash('Sorry! Email already in use')->error();
            return back()->withInput();
        }
        if (empty($request->company['city_id'])) {
            flash(t("Please select a city first"))->error();
            return back()->withInput();
        }
        $cityid1 = $request->company['city_id'];
        if (empty($cityid1)) {
            flash(t("Please select a city first"))->error();
            return back()->withInput();
        }

        $company = Company::where('id', $id)->where('user_id', auth()->user()->id)->firstOrFail();

        // Get Company Info
        $companyInfo = $request->get('company');

        if (!isset($companyInfo['user_id']) || empty($companyInfo['user_id'])) {
            $companyInfo += ['user_id' => auth()->user()->id];
        }
        if (!isset($companyInfo['country_code']) || empty($companyInfo['country_code'])) {
            $companyInfo += ['country_code' => config('country.code')];
        }


        // Make an Update

        if (isset($request->entities)) {
            $entities = implode(",", $request->entities);
            $company->entities = $entities;
        }
        if (!empty($companyInfo['name'])) {
            $company->name = $companyInfo['name'];
        }
        if (!empty($companyInfo['description'])) {
            $company->description = $companyInfo['description'];
        }
        if (!empty($companyInfo['country_code'])) {
            $company->country_code = $companyInfo['country_code'];
        }
        if (!empty($companyInfo['city_id'])) {
            $company->city_id = $companyInfo['city_id'];
        }
        if (!empty($companyInfo['address'])) {
            $company->address = $companyInfo['address'];
        }
        if (!empty($companyInfo['phone'])) {
            $company->phone = $companyInfo['phone'];
        }
        if (!empty($companyInfo['fax'])) {
            $company->fax = $companyInfo['fax'];
        }
        if (!empty($companyInfo['email'])) {
            $company->email = $companyInfo['email'];
        }
        if (!empty($companyInfo['website'])) {
            $company->website = $companyInfo['website'];
        }
        if (!empty($companyInfo['facebook'])) {
            $company->facebook = $companyInfo['facebook'];
        }
        if (!empty($companyInfo['twitter'])) {
            $company->twitter = $companyInfo['twitter'];
        }
        if (!empty($companyInfo['linkedin'])) {
            $company->linkedin = $companyInfo['linkedin'];
        }
        if (!empty($companyInfo['pinterest'])) {
            $company->pinterest = $companyInfo['pinterest'];
        }
        // Make an Update

        $company->save();
        // updating post emails 
        Post::update_posts_email($company->id, $company->email);

        flash(t("Your company details has updated successfully"))->success();

        $file = !empty($request->file('company')['logo']) ? $request->file('company')['logo'] : '';
        if (!empty($file)) {

            $file_type = $file->getClientOriginalExtension();
            $destinationPath = public_path('/') . 'storage/pictures/kw/' . $company->c_id;
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $fileName = 'pictures/kw/' . $company->c_id . '/' . time() . "." . $file_type;
            $file->move($destinationPath, $fileName);

            // create thumbnail
            $url = url('public/storage/' . $fileName);
            $unique = 'thumbnail_' . uniqid() . '.jpg';
            $im = new Imagick($url);
            $imageprops = $im->getImageGeometry();
            $width = $imageprops['width'];
            $height = $imageprops['height'];
            if ($width > $height) {
                $newHeight = 80;
                $newWidth = (80 / $height) * $width;
            } else {
                $newWidth = 80;
                $newHeight = (80 / $width) * $height;
            }
            $im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 0.9, true);
            // $im->cropImage (80,80,0,0);
            $im->writeImage(public_path('/') . 'storage/pictures/kw/' . $company->c_id . '/' . $unique);

            $company->thumbnail = 'pictures/kw/' . $company->c_id . '/' . $unique;
            $company->logo = $fileName;
            $company->is_image_uploaded_on_aws = 0;
            $company->save();

            $values = array(
                'file' => $fileName,
                'thumbnail' => 'pictures/kw/' . $company->c_id . '/' . $unique,
                'is_image_uploaded_on_aws' => 0,
            );
            User::where('id', $company->c_id)->update($values);
        }

        $values = array(
            'city'=>$companyInfo['city_id'],
            'name'=>$companyInfo['name'],
            'phone'=>$companyInfo['phone'],
            'email'=>$companyInfo['email'],
        );
        User::where('id', $company->c_id)->update($values);

        $name = auth()->user()->name;
        $company_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $description = "A Company Name: <b> <a href='$company_url'>$name</a></b> Updated his Profile details <br>";

        $changes = [];
        if ($company_data->entities != $entities) {
            $changes[] = "Entities : " . $entities . " <br>";
            $changes[] = "Entities Old: " . $company_data->entities . " <br>";
        }

        if (auth()->user()->country_code != $request->company['country_code']) {
            $country = Country::where('code', $request->company['country_code'])->first();
            $changes[] = "Country : " . $country->name . " <br>";
        }

        if (auth()->user()->city != $request->company['city_id']) {
            $city = City::where('id', $request->company['city_id'])->first();
            $changes[] = "City : " . $city->name . " <br>";
        }
        if (auth()->user()->phone != $request->company['phone']) {
            $changes[] = "Phone : " . $request->company['phone'] . " <br>";
        }

        if (!empty($changes)) {
            $description .= implode(" ", $changes) . "</a>";
            $companyDescription['changes'] = $description;
            Helper::activity_log($description);
            $companyDescription = Helper::companyDescriptionData($changes, 'child_company_update');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
        }

        // Redirection
        return redirect('account/companies');
    }

    public function destroy($id = null)
    {
        if (!Helper::check_permission(2)) {
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
        // Get Entries ID
        $ids = [];
        if (request()->filled('entries')) {
            $ids = request()->input('entries');
        } else {
            if (!is_numeric($id) && $id <= 0) {
                $ids = [];
            } else {
                $ids[] = $id;
            }
        }
        // Delete
        $nb = 0;
        foreach ($ids as $item) {
            $company = Company::where('id', $item)->firstOrFail();
            if (!empty($company)) {
                // Delete Entry
                $company->deleted_at = date('Y-m-d');
                $nb = $company->save();
                //                $nb = $company->delete();
            }
            $user_data = User::where('id', $company->c_id)->firstOrFail();
            if (!empty($user_data)) {
                // Delete Entry
                $user_data->deleted_at = date('Y-m-d');
                $user_data->save();
            }
            
            $data['child_company_name'] = $company->name;
            $companyDescription = Helper::companyDescriptionData($data, 'child_company_delete');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
        }

        // Confirmation
        if ($nb == 0) {
            flash(t("No deletion is done"))->error();
        } else {
            $count = count($ids);
            if ($count > 1) {
                flash(t("x entities has been deleted successfully", ['entities' => t('companies'), 'count' => $count]))->success();
            } else {
                flash(t("company has been deleted successfully", ['entity' => t('company')]))->success();
            }
        }

        return redirect('account/companies');
    }

    public function companyprofile($id)
    {

        if (!Helper::check_permission(2)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        $data['user'] = Company::get_company_data($id);
        $companyUrl = \App\Helpers\UrlGen::company(null, $data['user']->id);
        return redirect($companyUrl);

        if (!empty($data['user'])) {
            if (auth()->check()) {
                 $data['logoData'] = EmployeeLogo::get_comapny_logo($id);
                $data['all_companies'] = User::where('parent_id', $id)->get();
                return view('pages.company_profile')->with('data', $data);
            } else {
                $company_id = $data['user']->id;
                return redirect(url("companies/$company_id/jobs"));
            }
        } else {
            flash('Company not found.')->error();
            return redirect('/');
        }
    }
}
