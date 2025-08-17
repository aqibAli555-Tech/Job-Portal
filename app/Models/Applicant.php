<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Applicant extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applicants';


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone', 'user_id', 'to_user_id', 'post_id', 'file', 'status', 'unlocked', 'rejected_reason_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'deleted_at', 'archived_at', 'deletion_mail_sent_at', 'deleted_at'];

    public static function get_applicant_counts($request)
    {

        $applicant = self::with(['user', 'post', 'company', 'rejectedReason']);

        if (!empty($request->get('post'))) {
            $applicant = $applicant->where('post_id', $request->get('post'));
        }
        if (!empty($request->get('company'))) {
            $applicant = $applicant->where('to_user_id', $request->get('company'));
        }
        if (!empty($request->get('status'))) {
            $applicant = $applicant->where('status', $request->get('status'));
        }

        if (!empty($request->get('search'))) {
            $applicant = $applicant->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('email', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($request->get('unlock_applicant')) && $request->get('unlock_applicant') == 'unlock_applicant') {
            $applicant = $applicant->where('post_id', 0);
        }
        if (!empty($request->get('normal_applicant'))) {
            $applicant = $applicant->where('post_id', '!=', 0);
        }


        $applicant = $applicant->orderBy('id', 'DESC')->get()->count();
        return $applicant;
    }

    public static function get_applicant_counts_by_status($status)
    {
        return self::where('status', $status)->get()->count();

    }

    public static function get_applicant($request)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        $applicant = self::with(['user', 'user.unlock', 'post', 'company', 'rejectedReason']);

        if (!empty($request->get('post'))) {
            $applicant = $applicant->where('post_id', $request->get('post'));
        }

        if (!empty($request->get('status'))) {
            $applicant = $applicant->where('status', $request->get('status'));
            $applicant = $applicant->orderBy('updated_at', 'DESC');
        }

        if (!empty($request->get('search'))) {
            $applicant = $applicant->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('email', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($request->get('unlock_applicant')) && $request->get('unlock_applicant') == 'unlock_applicant') {
            $applicant = $applicant->where('post_id', 0);
        }

        if (!empty($request->get('company'))) {
            $applicant = $applicant->where('to_user_id', $request->get('company'));
        }
        if ($request->get('is_deleted') == 'yes') {
            $applicant = $applicant->where('is_deleted', 1);
        } else if ($request->get('is_deleted') == 'no') {
            $applicant = $applicant->where('is_deleted', 0);
        }
        if (!empty($request->get('daterange'))) {
            $date_range = preg_replace('/\s+/', '', $request->get('daterange'));
            $date_range = explode('-', trim($date_range));
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $applicant->whereRaw('DATE(applicants.updated_at) BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
        }
        if (!empty($request->get('unlock_applicant')) && $request->get('unlock_applicant') == 'total_unlocked') {
            $applicant = $applicant->where(function ($query) use ($request) {
                $query->whereHas('user.unlock', function ($subQuery) use ($request) {
                    $subQuery->where('unlock_user.is_unlock', 1);
            
                    if (!empty($request->get('company'))) {
                        $subQuery->where('unlock_user.to_user_id', $request->get('company'));
                    }
                })->where('post_id','!=', 0);
            });  
        }

        if (!empty($request->get('start'))) {
            return $applicant->skip($request->get('start'))->take($limit)->get();
        }

        $applicant = $applicant->orderBy('applicants.id', 'DESC')->paginate($limit)->appends(request()->query());
        return $applicant;
    }

    public static function get_applicant_count($request, $all = false)
    {

        $applicant = self::with(['user', 'post', 'company', 'rejectedReason']);

        if ($all == false) {
            if (!empty($request->get('post'))) {
                $applicant = $applicant->where('post_id', $request->get('post'));
            }
            if (!empty($request->get('company'))) {
                $applicant = $applicant->where('to_user_id', $request->get('company'));
            }
            if (!empty($request->get('status'))) {
                $applicant = $applicant->where('status', $request->get('status'));
            }

            if (!empty($request->get('search'))) {
                $applicant = $applicant->where(function ($query) use ($request) {
                    $search = $request->get('search');
                    $query->where('email', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }
            if (!empty($request->get('unlock_applicant')) && $request->get('unlock_applicant') == 'unlock_applicant') {
                $applicant = $applicant->where('post_id', 0);
            }


            if ($request->get('is_deleted') == 'yes') {
                $applicant = $applicant->where('is_deleted', 1);
            } else if ($request->get('is_deleted') == 'no') {
                $applicant = $applicant->where('is_deleted', 0);

            }
        }

        if (!empty($request->get('daterange'))) {
            $date_range = preg_replace('/\s+/', '', $request->get('daterange'));
            $date_range = explode('-', trim($date_range));
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $applicant->whereRaw('DATE(applicants.updated_at) BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
        }

        if (!empty($request->get('unlock_applicant')) && $request->get('unlock_applicant') == 'total_unlocked') {
            $applicant = $applicant->where(function ($query) use ($request) {
                $query->whereHas('user.unlock', function ($subQuery) use ($request) {
                    $subQuery->where('unlock_user.is_unlock', 1);
            
                    if (!empty($request->get('company'))) {
                        $subQuery->where('unlock_user.to_user_id', $request->get('company'));
                    }
                })->where('post_id','!=', 0);
            });  
        }
        $applicant = $applicant->count();
        return $applicant;
    }

    public static function get_applicants_data_by_status($request, $status, $check_user_package = null)
    {
        $data = Applicant::with('rejectedReason', 'User.nationalityData', 'Post')->select('applicants.*', 'users.name as name')
            ->where(function ($query) {
                $query->whereDoesntHave('post')
                    ->orWhereHas('post', function ($query) {
                        $query->where('posts.archived', 0)->where('posts.is_post_expire', 0);
                    });
            });
        $data = $data->join('users', 'applicants.user_id', '=', 'users.id');
        if (!empty($request->get('skill_set'))) {
            $data = $data->where('users.skill_set', 'like', "%{$request->get('skill_set')}%");
        }
        if (!empty($request->get('search'))) {
            $data = $data->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('applicants.name', 'LIKE', "%{$search}%")
                    ->orWhere('applicants.email', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($request->get('post_id'))) {
            $data = $data->where('post_id', $request->get('post_id'));
        }

        if (empty($request->get('show_not_accurate_employee')) || $request->get('show_not_accurate_employee') == 'No') {
            $data = $data->where(function ($query) {
                $query->where('skill_accuracy', '!=', 'Not Accurate')
                    ->orWhereNull('skill_accuracy');
            });
        }

        if (!empty($check_user_package) && $check_user_package->isEmpty()) {
            if (empty($request->get('show_unlock_from_cv')) || $request->get('show_unlock_from_cv') == 'No') {
                $data = $data->where('post_id', '!=', '0');
            }
        }

        $data = $data->where('status', $status);
        $data = $data->where('to_user_id', auth()->user()->id);
        $data = $data->where('is_deleted', 0);
        $data = $data->orderBy('created_at', 'desc');
        $data = $data->paginate(15)->appends(request()->query());
        return $data;
    }

    public static function get_applicants_data_by_status_count($status)
    {
        return Applicant::where(function ($query) {
            $query->whereDoesntHave('post')
                ->orWhereHas('post', function ($query) {
                    $query->where('posts.archived', 0)->where('posts.is_post_expire', 0);
                });
        })
            ->when(empty(request()->get('show_not_accurate_employee')) || request()->get('show_not_accurate_employee') == 'No', function ($query) {
                $query->where(function ($query) {
                    $query->where('skill_accuracy', '!=', 'Not Accurate')
                        ->orWhereNull('skill_accuracy');
                });
            })
            ->where('status', $status)
            ->orderBy('created_at', 'DESC')
            ->where('is_deleted', 0)
            ->where('to_user_id', auth()->user()->id)
            ->count();
    }


    public static function get_archived_applicants_count($archive_posts_ids, $status)
    {
        $data = Applicant::with('User')->with('Post')->where('to_user_id', auth()->user()->id)->whereIn('post_id', $archive_posts_ids);
        if (!empty($status)) {
            $data = $data->where('applicants.status', $status);
        }

        if (empty(request()->get('show_not_accurate_employee')) || request()->get('show_not_accurate_employee') == 'No') {
            $data = $data->where(function ($query) {
                $query->where('skill_accuracy', '!=', 'Not Accurate')
                    ->orWhereNull('skill_accuracy');
            });
        }
        $data = $data->count();
        return $data;
    }

    public static function get_pending_applicants_count()
    {
        return Applicant::where('status', 'pending')->count();
    }


    public static function get_unlock_applicants()
    {
        $usersIds = [];
        $respose_applicant = Unlock::get_unlock_contact_count();
        if (!empty($respose_applicant)) {
            foreach ($respose_applicant as $contact_card) {
                $usersIds[] = $contact_card->user_id;
            }
        }
        $applicant = Applicant::whereIn('user_id', $usersIds)->where('to_user_id', auth()->user()->id)->where('status', 'applied')->get();
        return $applicant;
    }

    public static function check_application_status_by_employer_id()
    {
        $status = true;
        $applicant = self::get_unlock_applicants();
        if (count($applicant) >= 5) {
            $status = false;
        }
        return ['status' => $status, 'applicants' => $applicant];
    }

    public static function not_accurate_not_read_employee_archived($archive_posts_ids)
    {

        $applicant_counts = Applicant::with([
            'Post'
        ])->whereIn('post_id', $archive_posts_ids)->where('skill_accuracy', 'Not Accurate')
            ->where('to_user_id', auth()->user()->id)->count();
        return $applicant_counts;
    }

    public static function not_accurate_not_read_employee()
    {
        $applicant_counts = Applicant::where(function ($query) {
            $query->whereDoesntHave('post')
                ->orWhereHas('post', function ($query) {
                    $query->where('posts.archived', 0)
                        ->where('posts.is_post_expire', 0);
                });
        })->where('skill_accuracy', 'Not Accurate')
            ->where('to_user_id', auth()->user()->id)->count();
        return $applicant_counts;
    }

    public static function update_is_read_status_of_not_accurate_employees_on_the_base_of_status($status)
    {
        $data['is_read'] = 1;
        return Applicant::where('to_user_id', auth()->user()->id)->where('status', $status)->withoutTimestamps()->update($data);
    }


    public static function get_applicant_count_by_status($status, $postid, $not_accurate = false)
    {
        $data = Applicant::with('User')->whereIn('post_id', $postid)->where('status', $status)->where('to_user_id', auth()->user()->id);
        if (empty($not_accurate) || $not_accurate == 'No') {
            $data = $data->where(function ($query) {
                $query->where('skill_accuracy', '!=', 'Not Accurate')
                    ->orWhereNull('skill_accuracy');
            });
        }
        return $data->count();
    }

    public static function get_employer_applicants($status)
    {
        DB::connection()->enableQueryLog();

        $sql = Applicant:: where('post_id', 0)->where('to_user_id', auth()->user()->id)->where('is_deleted', 0)->where('status', $status)->count();
        if (!empty($_GET['debugr'])) {
            $queries = DB::getQueryLog();
            dd($queries);
        } else {
            return $sql;
        }
    }

    public static function get_all_applied($user_id)
    {
        return Applicant::with(['User', 'companyData', 'post'])->where('user_id', $user_id)->where('status', '!=', 'not')->orderBy('id', 'DESC')->get();
    }

    public static function check_applied_user($id)
    {
        return Applicant::where('user_id', auth()->user()->id)
            ->where('post_id', $id)
            ->get()
            ->toArray();
    }

    public static function check_user_applied_api($employee_id, $post_id)
    {
        return Applicant::where('user_id', $employee_id)
            ->where('post_id', $post_id)
            ->get()
            ->toArray();
    }

    public static function total_applicants()
    {
        return Applicant::count();
    }

    public static function total_search_cv_applicants()
    {
        return Applicant::where('post_id', 0)->count();
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rejectedReason()
    {
        return $this->belongsTo(RejectedReason::class, 'rejected_reason_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'to_user_id', 'c_id');
    }

    public function companyData()
    {
        return $this->belongsTo(Company::class, 'to_user_id', 'c_id');
    }

    public static function track_applicant_user_by_employer($user_id, $employer_id)
    {
        return Applicant::with(['User', 'companyData', 'post'])->where('user_id', $user_id)->where('to_user_id', $employer_id)->where('status', '!=', 'not')->where('is_deleted', 0)->orderBy('id', 'DESC')->get();
    }

    public function InterviewApplicantTrack()
    {
        return $this->hasOne(InterviewApplicantTrack::class, 'applicant_id');
    }

    public static function get_applicants_that_have_in_interview_state_from_2_weeks()
    {
        return Applicant::with(['User', 'post', 'companyData', 'InterviewApplicantTrack'])
            ->where('status', 'interview')
            ->where('is_deleted', 0)
            ->where('updated_at', '<=', Carbon::now()->subWeeks(2))
            ->whereDoesntHave('InterviewApplicantTrack')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function scopeDateFilter($query, $date, $key)
    {
        return $query->where('updated_at', $key, $date);
    }

    public function scopeWhereCompany($query, $company_id)
    {
        return $query->where('to_user_id', $company_id);
    }

    public function scopeInterviewed($query)
    {
        return $query->where('status', 'interview');
    }

    public static function get_applicants_older_than_two_months($company_id)
    {
        $twoMonthsAgo = Carbon::now()->subMonths(3);
        $applicant = Applicant::whereCompany($company_id)
            ->where('is_deleted', 0)
            ->Interviewed()
            ->dateFilter($twoMonthsAgo, '<')
            ->get();
        return $applicant;
    }


    public static function get_archived_applicants($request, $archive_posts_ids)
    {
        $companyId = auth()->user()->id;
        $data = Applicant::with([
            'User' => function ($query) {
                $query->select('id', 'name', 'skill_set', 'city', 'country_code', 'nationality', 'created_at', 'thumbnail');
            },
            'User.cityData:id,name',
            'User.country:code,name',
            'User.nationalityData:id,name',
            'Post:id,title,archived,is_post_expire,created_at'
        ])->where('status', '!=', 'pending')
            ->selectRaw('applicants.*, 
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM unlock_user 
                        WHERE unlock_user.is_unlock = 1 
                        AND unlock_user.to_user_id = ? 
                        AND unlock_user.user_id = applicants.user_id
                    ) 
                    THEN 1 ELSE 0 
                END AS user_unlocked', [$companyId])
            ->where('is_deleted', 0)
            ->where('to_user_id', auth()->user()->id)
            ->whereIn('post_id', $archive_posts_ids);

        $data = self::filter_applicants($request, $data);
        $applicants = $data->orderBy('applicants.created_at', 'DESC')->get();
        foreach ($applicants as $applicant) {
            if (isset($applicant->user)) {
                $applicant->image_url = \App\Helpers\Helper::getImageOrThumbnailLink($applicant->user);
            }
        }
        return $applicants;
    }

    public static function get_applicants_data($request, $check_user_package = null)
    {
        $companyId = auth()->user()->id;

        $data = Applicant::with([
            'User' => function ($query) {
                $query->select('id', 'name', 'skill_set', 'city', 'country_code', 'nationality', 'created_at', 'thumbnail');
            },
            'User.cityData:id,name',
            'User.country:code,name',
            'User.nationalityData:id,name',
            'Post:id,title,archived,is_post_expire,created_at'
        ])->selectRaw('applicants.*, 
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM unlock_user 
                        WHERE unlock_user.is_unlock = 1 
                        AND unlock_user.to_user_id = ? 
                        AND unlock_user.user_id = applicants.user_id
                    ) 
                    THEN 1 ELSE 0 
                END AS user_unlocked', [$companyId])
            ->where(function ($query) {
                $query->whereDoesntHave('post')
                    ->orWhereHas('post', function ($query) {
                        $query->where('posts.archived', 0)
                            ->where('posts.is_post_expire', 0);
                    });
            });

        $data = self::filter_applicants($request, $data, $check_user_package);

        $data = $data->where('to_user_id', auth()->user()->id)
            ->where('applicants.is_deleted', 0)
            ->orderBy('applicants.created_at', 'desc');
        $applicants = $data->get();
        foreach ($applicants as $applicant) {
            if (isset($applicant->user)) {
                $applicant->image_url = \App\Helpers\Helper::getImageOrThumbnailLink($applicant->user);
            }
        }
        return $applicants;
    }


    public static function get_applicant_count_by_status_all($request = null, $check_user_package = null, $total = false)
    {
        $applicant_counts = Applicant::where(function ($query) {
            $query->whereDoesntHave('post')
                ->orWhereHas('post', function ($query) {
                    $query->where('posts.archived', 0)
                        ->where('posts.is_post_expire', 0);
                });
        })
            ->where('is_deleted', 0)
            ->where('to_user_id', auth()->user()->id)
            ->whereIn('status', ['applied', 'interview', 'hired', 'rejected']);

        $applicant_counts = $applicant_counts->selectRaw('status, COUNT(*) as count');
        $applicant_counts = self::filter_applicants($request, $applicant_counts, $check_user_package)
            ->groupBy('status')
            ->get();
        $data = [
            'applied_count' => $applicant_counts->where('status', 'applied')->first()->count ?? 0,
            'interview_count' => $applicant_counts->where('status', 'interview')->first()->count ?? 0,
            'hired_count' => $applicant_counts->where('status', 'hired')->first()->count ?? 0,
            'rejected_count' => $applicant_counts->where('status', 'rejected')->first()->count ?? 0,
        ];
        if ($total) {
            return $total_applicants_count = $data['applied_count'] + $data['interview_count'] + $data['hired_count'] + $data['rejected_count'];
        } else {
            return $data;
        }

    }

    public static function get_applicants_count()
    {
        $result = Applicant::where(function ($query) {
            $query->WhereHas('post', function ($query) {
                $query->where('posts.archived', 0)
                    ->where('posts.is_post_expire', 0);
            });
        })->where('to_user_id', auth()->user()->id)->where('is_deleted', 0);

        dd($result->toSql());
    }

    private static function filter_applicants($request, $data, $check_user_package = null)
    {
        if (!empty($request->get('skill_set'))) {
            $skillSet = $request->get('skill_set');
            $data = $data->whereHas('User', function ($query) use ($skillSet) {
                $query->where('skill_set', 'like', "%{$skillSet}%");
            });
        }
        if (!empty($request->get('search'))) {

            $data = $data->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('applicants.name', 'LIKE', "%{$search}%")
                    ->orWhere('applicants.email', 'LIKE', "%{$search}%");
            });
        }
//        if (!empty($request->get('status'))) {
//            dd($request->get('status'));
//            $data = $data->where('applicants.status', $request->get('status'));
//        }
        if (!empty($request->get('post_id'))) {
            $data = $data->where('applicants.post_id', $request->get('post_id'));
        }

        if (empty($request->get('show_not_accurate_employee')) || $request->get('show_not_accurate_employee') == 'No') {
            $data = $data->where(function ($query) {
                $query->where('skill_accuracy', '!=', 'Not Accurate')
                    ->orWhereNull('skill_accuracy');
            });
        }

        if (!empty($check_user_package) && $check_user_package->isEmpty()) {
            if (empty($request->get('show_unlock_from_cv')) || $request->get('show_unlock_from_cv') == 'No') {
                $data = $data->where('post_id', '!=', '0');
            }
        }

        return $data;
    }

    public static function get_archived_applicants_count_all($archive_posts_ids, $request, $total = false)
    {
        $data = Applicant::with('User')->with('Post')->where('to_user_id', auth()->user()->id)->whereIn('post_id', $archive_posts_ids);

        if (empty(request()->get('show_not_accurate_employee')) || request()->get('show_not_accurate_employee') == 'No') {
            $data = $data->where(function ($query) {
                $query->where('skill_accuracy', '!=', 'Not Accurate')
                    ->orWhereNull('skill_accuracy');
            });
        }
        if (!empty($request)) {
            $data = self::filter_applicants($request, $data);
            $data = $data->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();
            $data_all = [
                'applied_count' => $data->where('status', 'applied')->first()->count ?? 0,
                'interview_count' => $data->where('status', 'interview')->first()->count ?? 0,
                'hired_count' => $data->where('status', 'hired')->first()->count ?? 0,
                'rejected_count' => $data->where('status', 'rejected')->first()->count ?? 0,
            ];
            if ($total) {
                return $total_applicants_count = $data_all['applied_count'] + $data_all['interview_count'] + $data_all['hired_count'] + $data_all['rejected_count'];
            }
            return $data_all;
        }
    }

}