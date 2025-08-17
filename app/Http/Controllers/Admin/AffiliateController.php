<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EmailCheck;
use App\Helpers\Helper;
use App\Models\AffiliateBankDetail;
use App\Models\AffiliatesCommissionSlots;
use App\Models\City;
use App\Models\Country;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\ThreadParticipant;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends AdminBaseController
{

    public function ajax(Request $request)
    {
      $data = [];
      $affiliate_users = User::get_affiliates($request, 5);
      $filtered = User::get_affiliates_filter_count($request);
      $affiliates_count = User::get_employees_count(5);

      foreach ($affiliate_users as $key => $user) {
        $commission = $user->commission ? '<strong>Commission : </strong>' . $user->commission . '%<br>' : '';
        // $registerBy = '<br><strong>Register By : </strong>' . $user->register_via . '<br>';
        $total_companies = User::where('user_type_id',1)->where('affiliate_id',$user->id)->where('is_active',1)->count();
        $total_affiliates = User::where('user_type_id',5)->where('affiliate_id',$user->id)->where('is_active',1)->count();
        $referred_companies = '<br> <a target="_blank" href="'.admin_url('employer?affiliate_id=' . $user->id).'"> <span class="badge text-bg-primary"><strong>Referred Companies :</strong> ' .  $total_companies . '</span></a>';
        $referred_affiliates = '&nbsp;<a target="_blank" href="'.admin_url('affiliates?affiliate_id=' . $user->id).'"><span class="badge text-bg-success"><strong>Referred Affiliates :</strong> ' .  $total_affiliates . '</span></a>';
        $data[$key][] = '<div class="pt-1 text-center"><input type="checkbox" name="employee_ids" class="checkbox" onclick="SingletoggleCheckbox(this)" value="' . $user->id . '"></div>';
        $data[$key][] = '<div class="card bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-header border-0 p-0"><img width="55" height="55" src="' . Helper::getImageOrThumbnailLink($user). '" alt=""></div><div class="card-block px-2"><p class="card-text"><strong><span class="badge badge-success"># ' . $user->id . '</span> &nbsp;' . $user->name . '</strong><br>' . $user->email . '<br>' . $user->phone . '<br><img height="20" alt="' . $user->country_code . '" src="' . url()->asset('images/flags/16/' . strtolower($user->country_code) . '.png') . '"/>&nbsp;<br>' . $commission  . date('d M-Y h:i A', strtotime($user->created_at)) . $referred_companies . $referred_affiliates .'</p></div>     </div>';
        $slots = '';
        if (!empty($user->affiliatedCommissionSlots) && $user->affiliatedCommissionSlots->count() > 0) {
            foreach ($user->affiliatedCommissionSlots as $slot) {
                $currentSlot = User::get_current_slot($user->id);
                if(!empty($currentSlot) && $slot->id == $currentSlot->id){
                    $slots .= '<span class="bg-warning text-dark affiliate-border">$'.number_format($slot->min_amount) . ' - $' . number_format($slot->max_amount) . ($slot->max_amount == 250000 ? '+' : '') . ' => ' . $slot->commission . '%</span><br>';
                }else{
                    $slots .= '<span>$'. number_format($slot->min_amount) . ' - $' . number_format($slot->max_amount) . ($slot->max_amount == 250000 ? '+' : '') . ' => ' . $slot->commission . '%</span><br>';
                }
            }
        }
        $data[$key][] = '<div class="card bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block border-0 p-0">'
        . $slots . '
        </div></div>';


        if (!empty($user->is_active)) {
            $buttonText = 'Active';
            $buttonClass = 'btn-success';
            $titleMessage = 'Click to deactivate this affiliate';
        } else {
            $buttonText = 'Deactive';
            $buttonClass = 'btn-danger';
            $titleMessage = 'Click to activate this affiliate';
        }

        $data[$key][] = '<div class="card bg-transparent shadow-none flex-row flex-wrap m-0">
            <div class="card-block border-0 ps-1 pt-1">
                <button class="btn btn-sm ' . $buttonClass . '" id="' . $user->id . '" title="' . $titleMessage . '" onclick="status_change(this)" data-url="change_affiliate_status/' . $user->id . '">
                    ' . $buttonText . '
                </button>
            </div>
        </div>';


        $counter = $key + 1;
        $impersonate = url('impersonate/take/' . $user->id);
        $data[$key][] =
            '<div class="btn-group" role="group" aria-label="Action">
                <a class="btn btn-xs btn-primary btn-sm" href="'.$impersonate.'" data-toggle="tooltip" data-original-title="Impersonate this affiliate"><i class="fas fa-sign-in-alt"></i></a>&nbsp;
                <div class="dropdown">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop' . $counter . '" type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" >
                            <a class="dropdown-item" href="' . admin_url('edit_affiliate/' . $user->id) . '"><i class="fas fa-edit"></i> Edit</a>
                            
                            <a class="dropdown-item copy_affiliate_url" href="javascript:;" data-affiliate-url="' . url("register") . '?user_type_id=1&referral_code=' . $user->referral_code . '"><i class="fas fa-copy"></i> Copy affiliate URL for Company</a>
                            <a class="dropdown-item copy_affiliate_url" href="javascript:;" data-affiliate-url="' . url("affiliate-register") . '?user_type_id=5&referral_code=' . $user->referral_code . '"><i class="fas fa-copy"></i> Copy affiliate URL for Affiliate</a>
                            <a class="dropdown-item" href="' . admin_url('affiliated-commission-slot/' . $user->id) . '"><i class="fas fa-edit"></i> Edit Commission</a>
                            <a class="dropdown-item" href="'. admin_url('bank_detail/' . $user->id) .'"><i class="fas fa-university"></i> Bank Detail</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="delete_affiliate(' . $user->id . ')"><i class="fas fa-trash-alt"></i> Delete Account</a>
                        </div>
                    </div>
                </div>
            </div>';
        }

        header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                [
                    'draw' => $request->get('draw'),
                    'recordsTotal' => $affiliates_count,
                    'recordsFiltered' => $filtered,
                    'data' => $data
                ]
            );
            die;
    }

    public function index(Request $request)
    {
        $title = 'Affiliates';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Affiliates',
                'link' => 'javascript:void(0)'
            ]
        ];

        $affiliates = User::where('user_type_id',5)->get();
        $affiliate_id = (!empty($request->affiliate_id))?$request->affiliate_id:'';
        return view('admin.affiliates.index', compact('title', 'breadcumbs','affiliate_id','affiliates'));
    }

    public function create(Request $request)
    {
        $title = trans('admin.create_affiliate');
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => $title,
                'link' => 'javascript:void(0)'
            ]
        ];

        $country_list = Country::all();

        return view('admin.affiliates.create', compact('title', 'breadcumbs', 'country_list'));
    }

    public function store_affiliate(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed!',
                'errors' => $validator->errors()->all()
            ]);
        }
      
        $response = User::store_affiliate_request($request);
        return response()->json(['status' => $response['status'], 'message' => $response['message']]);
    }

    public function edit(Request $request, $id): Renderable
    {
        $title = trans('admin.edit_affiliate');
        $breadcrumbs = [
            [
                'title' => 'Dashboard', 
                'link' => admin_url('dashboard')
            ],
            [
                'title' => $title, 
                'link' => 'javascript:void(0)'
            ]
        ];

        $affiliateUser = User::get_affiliate($id);
        if (!$affiliateUser) {
            flash("No affiliate found. Please check details or register to become an affiliate.")->error();
            return back();
        }

        $countryList = Country::all();
        $city = City::where('country_code', $affiliateUser->country_code)->orderBy('name', 'ASC')->select('id','name')->get();
        
        return view('admin.affiliates.edit', compact('title', 'breadcrumbs', 'countryList', 'affiliateUser', 'city'));
    }

    public function update_request(Request $request, $id) : JsonResponse {
        $rules = [
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ];

        if (!empty($request->password)) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed!',
                'errors' => $validator->errors()->all()
            ]);
        }
        $this->create_activity_log($request);
        $response = User::update_affiliate_request($request, $id);
        return response()->json(['status' => $response['status'], 'message' => $response['message']]);
    }

    private function create_activity_log($request)
    {
        $user = User::find($request->id);
        $name = $user->name;
        $user_url = admin_url() . '/affiliates?search=' . $user->email;
        $admin_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $url = "<a href='$admin_url'><strong>HungryForJobs</strong></a>";
        $description = "The admin ". $url ." has updated the Profile details of Affiliate  Name: <b> <a href='$user_url'>$name</a></b>  <br>";

        $changes = [];
        if ($user->name != $request->name) {
            $changes[] = "Name : " . $request->name . " <br>";
        }


        if ($user->country_code != $request->country_code) {
            $country = Country::where('code', $request->country_code)->first();
            $changes[] = "Country : " . $country->name . " <br>";
        }
        if ($user->city != $request->city) {

            $city = City::where('id', $request->city)->first();
            $changes[] = "City : " . $city->name . " <br>";
        }

        if ($user->phone != $request->phone) {
            $changes[] = "Old Phone : " . $user->phone . " <br>";
            $changes[] = "New Phone : " . $request->phone . " <br>";
        }


        if (!empty($changes)) {
            $description .= implode(" ", $changes) . "</a>";
            Helper::activity_log($description);
        }
    }

    public function change_affiliate_status(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($user, 'change_affiliate_status');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription);
        }
        
        $user->update(['is_active' => $request->status]);
        
        return response()->json(['status' => true, 'message' => 'Status updated successfully']);
    }

    public function affiliated_index(Request $request, $id){
        $title = 'Affiliates';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Affiliates',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.affiliates.affiliated_index', compact('title', 'breadcumbs'));
    }

    public function bank_detail($user_id)
    {
        $bank_detail = AffiliateBankDetail::where('user_id', $user_id)->first();

        $title = trans('admin.bank_details');
        $breadcrumbs = [
            [
                'title' => 'Dashboard', 
                'link' => admin_url('dashboard')
            ],
            [
                'title' => $title, 
                'link' => 'javascript:void(0)'
            ]
        ];
        $countryList = Country::all();
        $city = collect();
        if ($bank_detail && $bank_detail->country_code) {
            $city = City::where('country_code', $bank_detail->country_code)->orderBy('name', 'ASC')->get();
        }
        return view('admin.affiliates-bank-detail.index', compact('title', 'breadcrumbs', 'countryList', 'bank_detail', 'city','user_id'));
    }

    public function edit_bank_detail(Request $request)
    {
        $request->validate([
            'service_type' => 'required|in:paypal,bank',
            'bank_detail_user_id' => 'required|exists:users,id',
            'email' => 'required_if:service_type,paypal|nullable|email|max:255',            
        ]);
        $data = [
            'user_id' => $request->bank_detail_user_id,
            'service_type' => $request->service_type,
        ];

        if ($request->service_type === 'paypal') {
            $data['email'] = $request->email;
            if(!empty($data['email'])){
                $response = EmailCheck::verify_user_email($data['email']);
                if (!empty($response) && $response->score <= 3 && $response->status == 'undeliverable') {
                    flash(t("The email address you entered is not valid."))->error();
                    return redirect()->back();
                }
            }
            $data = array_merge($data, [
                'bank_name' => null,
                'beneficiary_name' => null,
                'address' => null,
                'IBAN' => null,
                'country_code' => null,
                'city' => null,
                'state' => null,
                'bank_address' => null,
                'swift_code' => null,
            ]);
        } else {
            $data = array_merge($data, [
                'bank_name' => $request->bank_name,
                'beneficiary_name' => $request->beneficiary_name,
                'address' => $request->address,
                'IBAN' => $request->IBAN,
                'country_code' => $request->country_code,
                'city' => $request->city,
                'state' => $request->state,
                'bank_address' => $request->bank_address,
                'swift_code' => $request->swift_code,
                'email' => null,
            ]);
        }
        $user = User::find($request->bank_detail_user_id);
        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($user, 'update_bank_detail');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription);
        }

        $bankDetail = AffiliateBankDetail::updateOrCreate(
            ['user_id' => $request->bank_detail_user_id],
            $data
        );
        
        flash(trans('Bank Detail updated successfully'))->success();
        return redirect()->back();
    }

    public function delete_affiliate()
    {
        $pincode = request()->get('pincode');

        if (!empty($pincode) && $pincode == 'hungry') {
            $id = request()->get('id');
            $model = User::find($id);

            if (!empty($model->user_type_id)) {
                if ($model->user_type_id == 5) {
                    $hasReferrals = User::where('affiliate_id', $id)->exists();

                    if ($hasReferrals) {
                        return response()->json([
                            'status' => false,
                            'message' => t("This affiliate has referred companies"),
                            'url' => '',
                        ]);
                    }

                    $threads = ThreadParticipant::where('user_id', $id)->groupBy('id')->get();
                    if (!empty($threads)) {
                        foreach ($threads as $value) {
                            Thread::where('id', $value->thread_id)->delete();
                            ThreadMessage::where('thread_id', $value->thread_id)->delete();
                        }
                    }
                    ThreadParticipant::where('user_id', $id)->delete();
                    AffiliatesCommissionSlots::where('affiliate_id', $id)->delete();
                    $description = "<b>An Affiliate: $model->name has been deleted by admin.";

                }
                Helper::activity_log($description);

                $res = User::where('id', $id)->delete();
                if ($res) {
                    $response = array(
                        'status' => true,
                        'message' => t("Deleted successfully"),
                        'url' => '',
                    );
                    return response()->json($response);
                } else {
                    $response = array(
                        'status' => false,
                        'message' => t("please try again"),
                        'url' => '',
                    );
                    return response()->json($response);
                }
            } else {

                $response = array(
                    'status' => false,
                    'message' => t("User Not Found"),
                    'url' => '',
                );
                return response()->json($response);
            }
        } else {

            $response = array(
                'status' => false,
                'message' => t("Your Pin Code Invalid"),
                'url' => '',
            );
            return response()->json($response);
        }

    }
}