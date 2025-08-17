<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Auth\Traits\RegistersUsers;
use App\Helpers\Helper;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Torann\LaravelMetaTags\Facades\MetaTag;

class StaffController extends AccountBaseController
{
    use RegistersUsers, VerificationTrait;

    public $pagePath = 'staff';
    private $perPage = 10;

    public function __construct()
    {
        parent::__construct();

        $this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;

        view()->share('pagePath', $this->pagePath);
    }


    public function index()
    {
        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }

        $company_id = auth()->user()->id;

        // Get all Staff
        $staffs = User::where('user_type_id', 3)->where('Company_id', $company_id)->where('deleted_at', NULL)->paginate(10);
        // Meta Tags
        $title = t('My Staffs List');
        view()->share('title', $title);
        MetaTag::set('title', t('My Staffs List'));
        MetaTag::set('description', t('My Staff List on'));

        return appView('account.staff.index')->with('staffs', $staffs);
    }

    public function create()
    {
        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }

        // Meta Tags
        $title = t('Create a new staff');
        view()->share('title', $title);
        MetaTag::set('title', t('Create a new staff'));
        MetaTag::set('description', t('Create a new staff on'));

        return appView('account.staff.create');
    }

    public function store(Request $request)
    {
        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }

        $check_email_in_user = User::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('email', $request->staff['email'])->first();

        if (!empty($check_email) || !empty($check_email_in_user)) {
            flash('Email is already in use')->error();
            return back()->withInput();
        }

        $staffInfo = $request->get('staff');
        if (!empty($staffInfo['phone'])) {
            if (strlen($staffInfo['phone']) < 6) {
                flash('Please enter valid phone number')->error();
                return back()->withInput();
            }
        }
        if (!isset($staffInfo['user_id']) || empty($staffInfo['user_id'])) {
            $staffInfo += ['user_id' => auth()->user()->id];
        }
        if ($staffInfo['password'] != $staffInfo['confirm_password']) {
            flash('Password and confirm password is not matched!')->error();
            return back()->withInput();
        }

        $user = new User();
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }

        $user->password = Hash::make($staffInfo['password']);
        $user->verified_email = 1;
        $user->verified_phone = 1;
        $user->user_type_id = 3;
        $user->gender_id = 1;
        $user->name = !empty($staffInfo['name']) ? $staffInfo['name'] : '';
        $user->email = !empty($staffInfo['email']) ? $staffInfo['email'] : '';
        $user->phone = !empty($staffInfo['phone']) ? $staffInfo['phone'] : '';
        $user->parent_id = auth()->user()->id;
        $user->company_id = auth()->user()->id;
        $user->password_without_hash = $staffInfo['password'];
        $user->accept_marketing_offers = 0;
        $user->verified_email = 1;
        if ($user->save()) {
            $name = $staffInfo['name'];
            $email = $staffInfo['email'];
            $profile_url = admin_url() . '/staffs?search=' . $email;
            $company_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $company_name = auth()->user()->name;
            $description = "A company Name: <a href='$company_url' traget='_blank'>$company_name</a>  create new staff Name: <a href='$profile_url' traget='_blank'>$name</a> ";
            Helper::activity_log($description);
            $data['name'] = $name;
            $data['profile_url'] = url('/account/staff').'/'.$user->id;
            $companyDescription = Helper::companyDescriptionData($data, 'staff_create');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            flash(t("Your staff has created successfully"))->success();
            return redirect('account/staff');
        } else {
            flash(t("Error"))->error();
            return redirect('account/staff');
        }
    }

    public function show($id)
    {
        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission Error."))->error();
            return redirect('/');
        }
        if (auth()->user()->parent_id != auth()->user()->id) {
            flash(t("Only Parent Company Can View This Page."))->error();
            return redirect('/');
        }
        return redirect('account/staff/' . $id . '/edit');
    }

    public function edit($id)
    {
        // Get the staff
        $staff = User::where('id', $id)->firstOrFail();

        // Meta Tags

        view()->share([
            'title' => t('Edit the staff'),
            'description' => t('Edit the staff'),
            'keywords' => t('Edit the staff'),
            // Add more variables as needed
        ]);

        return appView('account.staff.edit')->with('staff', $staff);
    }

    public function update($id, Request $request)
    {
        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        if (auth()->user()->parent_id != auth()->user()->id) {
            flash(t("Only Parent Company Can View This Page."))->error();
            return redirect('/');
        }

        $old_staff_data = User::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $id)->first();
        $check_email_in_user = User::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', '!=', $old_staff_data->id)->where('email', $request->staff['email'])->first();
        if (!empty($check_email) || !empty($check_email_in_user)) {
            flash('Sorry! Email already in use')->error();
            return back()->withInput();
        }

        $staff = User::where('id', $id)->firstOrFail();
        // Get staff Info
        $staffInfo = $request->get('staff');
        if (!empty($staffInfo['phone'])) {
            if (strlen($staffInfo['phone']) < 6) {
                flash('Please enter valid phone number')->error();
                return back()->withInput();
            }
        }

        if (!isset($staffInfo['user_id']) || empty($staffInfo['user_id'])) {
            $staffInfo += ['user_id' => auth()->user()->id];
        }

        $staff->name = $staffInfo['name'];
        $staff->phone = $staffInfo['phone'];
        $staff->email = $staffInfo['email'];
        if ($staff->save()) {
            $name = $staffInfo['name'];
            $email = $staffInfo['email'];
            $profile_url = admin_url() . '/staffs?search=' . $email;
            $company_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $company_name = auth()->user()->name;
            $description = "A company Name: <a href='$company_url' traget='_blank'>$company_name</a>  update this staff Name: <a href='$profile_url' traget='_blank'>$name</a> ";
            Helper::activity_log($description);
            $data['name'] = $name;
            $data['profile_url'] = url('/account/staff').'/'.$staff->id;
            $companyDescription = Helper::companyDescriptionData($data, 'staff_update');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            flash(t("Your staff has updated successfully"))->success();
            return redirect('account/staff');
        } else {
            flash(t("Error"))->error();
            return redirect('/');
        }
    }

    public function destroy($id = null)
    {
        if (!empty(auth()->user()->user_type_id == 2)) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        if (auth()->user()->parent_id != auth()->user()->id) {
            flash(t("Only Parent Company Can View This Page."))->error();
            return redirect('/');
        }
        // Delete
        $nb = 0;
        $user_data = User::where('id', $id)->firstOrFail();
        if (!empty($user_data)) {
            $user_data->deleted_at = date('Y-m-d');
            $user_data->save();
            $nb++;
        }

        // Confirmation
        if ($nb == 0) {
            flash(t("No deletion is done"))->error();
        } else {
            $name = $user_data->name;
            $company_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $company_name = auth()->user()->name;
            $description = "A company Name: <a href='$company_url' traget='_blank'>$company_name</a>  delete this staff Name:$name";
            Helper::activity_log($description);
            $data['name'] = $name;
            $companyDescription = Helper::companyDescriptionData($data, 'staff_delete');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            flash(t("staff has been deleted successfully", ['entity' => t('staff')]))->success();
        }

        return redirect('account/staff');
    }

    public function permissions($id)
    {
        $userPermissions = User::find($id);
        $permissions = [
            ['id' => 1, 'name' => 'My Profile'],
            ['id' => 2, 'name' => 'My Companies'],
            ['id' => 3, 'name' => 'My Jobs'],
            ['id' => 4, 'name' => "Search CV'S"],
            ['id' => 5, 'name' => 'Favorite Employees'],
            ['id' => 6, 'name' => 'Applicants'],
            ['id' => 7, 'name' => "Saved CV'S"],
            ['id' => 8, 'name' => 'Unlocked Contacts'],
            ['id' => 9, 'name' => 'Archived Jobs'],
            ['id' => 13, 'name' => 'Archived Applicants'],
            ['id' => 10, 'name' => 'Chat With Employees'],
            ['id' => 14, 'name' => 'Bulk Chat Requests'],
            ['id' => 11, 'name' => 'Transactions'],
            ['id' => 12, 'name' => 'Upgrade Account']
        ];
        // Meta Tag
        view()->share([
            'title' => t('Staff Permission'),
            'description' => t('Staff Permission'),
            'keywords' => t('Staff Permission'),
            // Add more variables as needed
        ]);

        return appView('account.staff.permission')->with(['permissions' => $permissions, 'userPermissions' => $userPermissions]);
    }

    public function updatePermissions(Request $request)
    {
        $id = $request->get('id');
        if (!empty($request->get('permissions'))) {
            $permissions = implode(',', $request->get('permissions'));
        } else {
            $permissions = '';
        }
        $res = User::where('id', $id)->update(['permissions' => $permissions]);
        if ($res) {
            $User = User::find($id);
            $name = $User->name;
            $profile_url = admin_url() . '/staffs?search=' . $User->email;
            $company_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $company_name = auth()->user()->name;
            $description = "A company Name: <a href='$company_url' traget='_blank'>$company_name</a>  change permission for this staff  Name: <a href='$profile_url' traget='_blank'>$name </a>";
            Helper::activity_log($description);
            $data['name'] = $name;
            $data['url'] = url('/account/staff/');
            $companyDescription = Helper::companyDescriptionData($data, 'staff_permission');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            flash(t("Staff Permission updated successfully"))->success();
        } else {
            flash("Plz try again")->error();
        }
        return redirect('account/staff/' . $id . '/permissions');
    }

    public function update_passwords(Request $request)
    {

        $id = $request->get('id');
        if ($request->get('password') != $request->get('confirm_password')) {
            flash(t('Confirm password not matched'))->error();
            return redirect('account/staff/' . $id . '/change_password');
        }
        $User = User::where('password_without_hash', $request->get('old_password'))->first();
        if (!empty($User)) {
            $User->password_without_hash = $request->get('password');
            $User->password = Hash::make($request->get('password'));
            if ($User->save()) {
                $User = User::find($id);
                $name = $User->name;
                $profile_url = admin_url() . '/staffs?search=' . $User->email;
                $company_url = admin_url() . '/employer?search=' . auth()->user()->email;
                $company_name = auth()->user()->name;
                $description = "A company Name: <a href='$company_url' traget='_blank'>$company_name</a>  change Password for this staff  Name: <a href='$profile_url' traget='_blank'>$name </a>";
                Helper::activity_log($description);
                $data['name'] = $name;
                $data['url'] = url('/account/staff/');
                $companyDescription = Helper::companyDescriptionData($data, 'staff_password');
                if(!empty($companyDescription)){
                    Helper::activity_log($companyDescription,auth()->user()->id);
                }
                flash(t("Staff Password updated successfully"))->success();
                return redirect('account/staff');
            } else {
                flash('password Not Change Please try Again')->error();
                return redirect('account/staff/' . $id . '/change_password');
            }

        } else {
            flash(t('Old Password Not Correct.Please Enter Correct Old Password'))->error();
            return redirect('account/staff/' . $id . '/change_password');
        }
    }

    public function change_password($id)
    {

        $staff = User::where('id', $id)->firstOrFail();
        // Meta Tag
        view()->share([
            'title' => t('Change Password'),
            'description' => t('Change Password'),
            'keywords' => t('Change Password'),
            // Add more variables as needed
        ]);

        return appView('account.staff.change_password')->with('staff', $staff);
    }
}