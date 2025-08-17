<?php

namespace App\Models;


use App\Helpers\Date;
use App\Helpers\UrlGen;
use App\Models\Scopes\LocalizedScope;
use App\Models\Scopes\StrictActiveScope;
use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Larapen\Admin\app\Models\Traits\Crud;

class Payment extends BaseModel
{
    use Crud, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    protected $appends = ['created_at_formatted'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['post_id', 'package_id', 'payment_method_id', 'transaction_id', 'active', 'user_id', 'package_type', 'important','amount', 'created_at', 'updated_at', 'discount_type', 'discount_value', 'package_amount'];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'deleted_at'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function get_all_payments($request)
    {
        $limit = (!empty($request->get('limit'))) ? $request->get('limit') : 50;
        $payments = new Payment();
        if (!empty($request->get('search')) && $request->get('search') == 'paid') {
            $payments = $payments->where('package_id', '!=', 5);
        }

        if ($request->get('search') != 'paid') {
            $searchName = $request->get('search');
            $payments = $payments->whereHas('package', function ($query) use ($searchName) {
                $query->where('name', 'like', '%' . $searchName . '%');
            })->orWhereHas('user', function ($query) use ($searchName) {
                $query->where('name', 'like', '%' . $searchName . '%');
            });
        }

        $payments->with(['user', 'package'])->orderBy('id', 'desc');
        if (!empty($request->get('start'))) {
            return $payments->skip($request->get('start'))->take($limit)->get();
        }
        return $payments->paginate($limit)->appends(request()->query());
    }

    public static function get_all_payments_count($request)
    {
        $limit = (!empty($request->get('limit'))) ? $request->get('limit') : 50;
        $payments = new Payment();
        if (!empty($request->get('search')) && $request->get('search') == 'paid') {
            $payments = $payments->where('package_id', '!=', 5);
        }
        if (!empty($request->get('search')) && $request->get('search') != 'paid') {
            $searchName = $request->get('search');
            $payments = $payments->whereHas('package', function ($query) use ($searchName) {
                $query->where('name', 'like', '%' . $searchName . '%');
            })->orWhereHas('user', function ($query) use ($searchName) {
                $query->where('name', 'like', '%' . $searchName . '%');
            });
        }

        return $payments->with(['user', 'package'])->count();
    }

    public static function top_life_time_users_count()
    {
        return Payment::count();
    }

    public static function revenue_count()
    {
        return Payment::where('is_refunded', 0)->sum('amount');
    }

    public static function get_revenue($range)
    {
        $monthlyRevenue = [];
        if ($range) {
            $currentYear = Carbon::now()->year;
            $startYear = $currentYear - $range + 1;

            $revenues = Payment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_amount')
                ->where('is_refunded', 0)
                ->whereYear('created_at', '>=', $startYear)
                ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                ->get()
                ->groupBy('year');


            for ($year = $startYear; $year <= $currentYear; $year++) {
                $monthlyRevenue[$year] = array_fill(1, 12, 0);
                if (isset($revenues[$year])) {
                    foreach ($revenues[$year] as $revenue) {
                        $monthlyRevenue[$year][$revenue->month] = $revenue->total_amount;
                    }
                }
            }
        }

        return $monthlyRevenue;

    }


    protected static function boot()
    {
        parent::boot();

        Payment::observe(PaymentObserver::class);

        static::addGlobalScope(new StrictActiveScope());
        static::addGlobalScope(new LocalizedScope());
    }

    public function getPostTitleHtml()
    {
        $out = '';

        // Post's Country
        if ($this->post) {
            if (isset($this->post->country_code)) {
                $countryName = (isset($this->post->country) && isset($this->post->country->name)) ? $this->post->country->name : null;
                $countryName = (!empty($countryName)) ? $countryName : $this->post->country_code;

                $iconPath = 'images/flags/16/' . strtolower($this->post->country_code) . '.png';
                if (file_exists(public_path($iconPath))) {
                    $out .= '<a href="' . dmUrl($this->post->country_code, '/', true, true) . '" target="_blank">';
                    $out .= '<img src="' . url('public/' . $iconPath) . getPictureVersion() . '" data-toggle="tooltip" title="' . $countryName . '">';
                    $out .= '</a>';
                } else {
                    $out .= '<img src="' . url()->asset('images/blank.gif') . '" width="16" height="16" alt="' . $this->post->country_code . '"> ';
                }
                $out .= ' ';
            }
        } else {
            $out .= '<img src="' . url()->asset('images/blank.gif') . '" width="16" height="16"> ';
        }

        // Post's ID
        $out .= '#' . $this->post_id;

        // Post's title & link
        if ($this->post) {
            // $postUrl = url(UrlGen::postUri($this->post));
            $postUrl = dmUrl($this->post->country_code, UrlGen::postPath($this->post));
            $out .= ' - ';
            $out .= '<a href="' . $postUrl . '" target="_blank">' . $this->post->title . '</a>';

            if (config('settings.single.posts_review_activation')) {
                $outLeft = '<div class="pull-left">' . $out . '</div>';
                $outRight = '<div class="pull-right"></div>';

                if ($this->active != 1) {
                    // Check if this ad has at least successful payment
                    $countSuccessfulPayments = Payment::where('post_id', $this->post_id)->where('active', 1)->count();
                    if ($countSuccessfulPayments <= 0) {
                        $msg = trans('admin.payment_post_delete_btn_tooltip');
                        $tooltip = ' data-toggle="tooltip" title="' . $msg . '"';

                        $outRight = '';
                        $outRight .= '<div class="pull-right">';
                        $outRight .= '<a href="' . admin_url('posts/' . $this->post_id) . '" class="btn btn-xs btn-danger" data-button-type="delete"' . $tooltip . '>';
                        $outRight .= '<i class="fa fa-trash"></i> ';
                        $outRight .= trans('admin.Delete');
                        $outRight .= '</a>';
                        $outRight .= '</div>';
                    }
                }

                $out = $outLeft . $outRight;
            }
        }

        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function getPackageNameHtml()
    {
        $out = $this->package_id;

        if (!empty($this->package)) {
            $packageUrl = admin_url('packages/' . $this->package_id . '/edit');
            $out = '';
            $out .= '<p>';
            $out .= $this->package->name;
            $out .= '</p>';
            $out .= ' (' . $this->package->price . ' ' . $this->package->currency_code . ')';
        }
        return $out;
    }

    public function getuserNameHtml()
    {

        $out = '';
        // $out = $this->user_id
        $user_data = User::withoutGlobalScopes()->where('id', $this->user_id)->first();

        if (!empty($user_data)) {
            $out = '';
            $out .= '<p>';
            $out .= $user_data->name;
            $out .= '</p>';
        }
        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPaymentMethodNameHtml()
    {
        $out = '--';

        if (!empty($this->paymentMethod)) {
            $paymentMethodUrl = admin_url('payment_methods/' . $this->payment_method_id . '/edit');
            $paymentMethodUrl = "javascript:void()";

            $out = '';
            $out .= '<a href="' . $paymentMethodUrl . '">';
            if ($this->paymentMethod->name == 'offlinepayment') {
                $out .= trans('offlinepayment::messages.Offline Payment');
            } else {
                $out .= $this->paymentMethod->display_name;
            }
            $out .= '</a>';
        }

        return $out;
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function getCreatedAtFormattedAttribute($value)
    {
        $value = new Carbon($this->attributes['created_at']);
        $value->timezone(Date::getAppTimeZone());

        $value = Date::formatFormNow($value);

        return $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */


}
