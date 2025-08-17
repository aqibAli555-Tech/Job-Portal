<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPackages extends Model
{
    use HasFactory;

    protected $table = 'company_packages';


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['employer_id', 'package_id', 'total_post', 'remaining_post', 'total_credits', 'remaining_credits', 'unlimited', 'start_date', 'package_expire_date', 'package_type', 'yearly_package_expire_date','is_subscription_cancelled'];

    public static function get_latest_package_subscribed($id = null)
    {
        $id = $id ?? auth()->user()->id;
        $data = CompanyPackages::where('employer_id', $id)->where('is_package_expire', 0)->orderBy('id', 'desc')->first();
        if (!empty($data->yearly_package_expire_date)) {
            return $data->yearly_package_expire_date;
        } else {
            if (!empty($data->package_expire_date)) {
                return $data->package_expire_date;
            }
        }
    }

    public static function check_cancel_subscription($id = null)
    {
        $id = $id ?? auth()->user()->id;
        $data = CompanyPackages::where('employer_id', $id)->where('is_package_expire', 0)->orderBy('id', 'desc')->first();
        return $data->is_subscription_cancelled;
    }

    public static function get_subscribed_package_details($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = auth()->user()->id;
        }
        return CompanyPackages::select('company_packages.*', 'packages.name')
            ->leftJoin('packages', 'packages.id', '=', 'company_packages.package_id')
            ->where('company_packages.employer_id', $user_id)
            ->where('company_packages.is_package_expire', 0)
            //->orderBy('company_packages.id','desc')
            ->groupBy('company_packages.id')
            ->get();
    }

    public static function get_paid_package_details($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = auth()->user()->id;
        }
        return CompanyPackages::select('company_packages.*', 'packages.name')
            ->leftJoin('packages', 'packages.id', '=', 'company_packages.package_id')
            ->where('company_packages.employer_id', $user_id)
            ->where('company_packages.is_package_expire', 0)
            ->whereNotIn('company_packages.package_id', [5])
            ->groupBy('company_packages.id')
            ->get();
    }


    public static function get_latest_employer_package_details($employer_id)
    {
        return CompanyPackages::where('employer_id', $employer_id)->orderBy('id', 'desc')->first();

    }

    public static function get_all_subscibed_packages($employer_id, $package_id)
    {
        return CompanyPackages::where('employer_id', $employer_id)->where('package_id', $package_id)->where('is_package_expire', 0)->first();
    }

    public static function check_credit_available($employer_id)
    {

        $cridets = CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->orderBy('id', 'desc')->first();
        if (!empty($cridets) && $cridets->unlimited_credit == 1) {
            return 'unlimited';
        } else {
            return CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->sum('remaining_credits');
        }

    }

    public function Package()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }

    public static function get_latest_package_details()
    {
        return CompanyPackages::with('Package')->where('employer_id', auth()->user()->id)->orderBy('id', 'desc')->first();
    }

    public static function check_post_available($employer_id)
    {
        $posts = CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->orderBy('id', 'desc')->first();

        if (!empty($posts) && $posts->unlimited == 1) {
            return 'unlimited';
        } else {
            return CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->sum('remaining_post');
        }
    }

    public static function check_total_post($employer_id)
    {
        $posts = CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->orderBy('id', 'desc')->first();
        if (!empty($posts) && $posts->unlimited == 1) {
            return 'unlimited';
        } else {
            return CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->sum('total_post');
        }
    }

    public static function check_available_credits($employer_id)
    {
        $credits = CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->orderBy('id', 'desc')->first();
        if (!empty($credits) && $credits->unlimited == 1) {
            return 'unlimited';
        } else {
            return CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->sum('remaining_credits');
        }
    }

    public static function check_total_credit($employer_id)
    {
        $credits = CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->orderBy('id', 'desc')->first();
        if (!empty($credits) && $credits->unlimited_credit == 1) {
            return 'unlimited';
        } else {
            return CompanyPackages::where('employer_id', $employer_id)->where('is_package_expire', 0)->sum('total_credits');
        }
    }

    public static function current_subscription_users_count($type = 1)
    {
        $data = CompanyPackages::where('company_packages.is_package_expire', 0)->where('users.user_type_id', $type)
            ->groupBy('company_packages.employer_id')
            ->select('company_packages.employer_id', \DB::raw('count(*) as count'))
            ->join('users', 'users.id', '=', 'company_packages.employer_id')
            ->get();
        return count($data);
    }

    public static function check_company_has_premium_package()
    {
        $latest_package = CompanyPackages::get_latest_package_details();
        $valid_package = false;
        if (!empty($latest_package) && $latest_package->is_package_expire == 0) {
            if ($latest_package->Package->is_premium == 1) {
                $valid_package = true;
            }
        }
        return $valid_package;
    }

    public static function update_yearly_package_data_on_the_base_of_month($package_data, $company_package_id)
    {
        $old_package_data = self::get_package_with_id($company_package_id);
        $currentDate = Carbon::now();
        $today = $currentDate->format('Y-m-d H:i:s');
        $newDate = $currentDate->addDays(30);
        $lastDate = $newDate->format('Y-m-d H:i:s');
        $new_Record = $old_package_data->replicate();
        $new_Record->is_package_expire = 0;
        $new_Record->package_expire_date = $lastDate;
        $new_Record->start_date = $today;
        $new_Record->remaining_post = $package_data->number_of_posts;
        $new_Record->remaining_credits = $package_data->number_of_cards;
        $new_Record->save();
    }

    public static function get_package_with_id($id)
    {
        return self::where('id', $id)->first();
    }

    public static function get_expire_user_packages($company_id = 0)
    {
        $currentDate = Carbon::now();
        $date = $currentDate->format('Y-m-d H:i:s');
        $updated_at = $currentDate->format('Y-m-d');
        $all_packages = CompanyPackages::where('is_subscription_cancelled', 0)->where(function ($query) use ($date, $updated_at) {
            $query->where(function ($subQuery) use ($date) {
                $subQuery->where('is_package_expire', 0)
                    ->where(function ($subSubQuery) use ($date) {
                        $subSubQuery->where('package_expire_date', '<=', $date)
                            ->orWhere(function ($subSubSubQuery) use ($date) {
                                $subSubSubQuery->where('package_type', 'yearly')
                                    ->where('yearly_package_expire_date', '<=', $date);
                            });
                    });
            })->orWhere(function ($subQuery) use ($updated_at) {
                $subQuery->where('sub_renew_hit', 1)
                    ->whereDate('updated_at', '<>', $updated_at);
            });
        });
        if (!empty($company_id)) {
            $all_packages = $all_packages->where('employer_id', $company_id);
        }
        $all_packages=   $all_packages->groupBy('employer_id', 'package_id')->orderBy('id', 'asc')
            ->get();
        return $all_packages;
    }



    public static function get_premium_packages_for_last_login($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = auth()->user()->id;
        }
        return CompanyPackages::select('company_packages.*', 'packages.name')
            ->leftJoin('packages', 'packages.id', '=', 'company_packages.package_id')
            ->where('company_packages.employer_id', $user_id)
            ->where('company_packages.is_package_expire', 0)
            ->whereNotIn('company_packages.package_id', [5,4])
            //->orderBy('company_packages.id','desc')
            ->groupBy('company_packages.id')
            ->get();
    }

}
