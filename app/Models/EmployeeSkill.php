<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\matches;

class EmployeeSkill extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employee_skills';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['skill', 'status'];

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
    protected $dates = ['created_at', 'updated_at'];

    public static function getAllskillWithEmplyeeCount($request)
    {
        $countryCode = $request->get('country');
        $data = DB::table('employee_skills')
            ->leftJoin('users', function ($join) use ($countryCode) {
                $join->on(DB::raw("TRIM(BOTH ' ' FROM users.skill_set)"), 'like', DB::raw("CONCAT('%', TRIM(BOTH ' ' FROM employee_skills.skill), '%')"));
                if (!empty($countryCode)) {
                    $join->where('users.country_code', '=', $countryCode);
                }
            })
            ->select('employee_skills.skill', DB::raw('COUNT(users.id) as user_count'))
            ->where('employee_skills.status', 1)
            ->orderBy('employee_skills.skill')
            ->groupBy('employee_skills.skill');
        return $data->get();
    }

    public static function get_All_skill_With_Post_Count()
    {
        return DB::table('employee_skills')
            ->leftJoin('posts', 'employee_skills.id', '=', 'posts.category_id')
            ->select('employee_skills.*', DB::raw('COUNT(posts.id) as post_count'))
            ->where('employee_skills.status', 1)
            ->where('employee_skills.is_deleted', 0)
            ->where(function ($query) {
                $query->where('posts.is_active', 1)
                    ->orWhere('posts.is_active', null);
            })
            ->where(function ($query) {
                $query->where('posts.is_deleted', 0)
                    ->orWhere('posts.is_deleted', null);
            })
            ->where(function ($query) {
                $query->where('posts.is_post_expire', 0)
                    ->orWhere('posts.is_post_expire', null);
            })
            ->groupBy('employee_skills.id')
            ->orderBy('post_count', 'desc')
            ->get();
    }

    public static function get_all_skill_with_post_count_api()
    {
        return DB::table('employee_skills')
            ->join('posts', 'employee_skills.id', '=', 'posts.category_id')
            ->select('employee_skills.*', DB::raw('COUNT(posts.id) as post_count'))
            ->where('employee_skills.status', 1)
            ->where('employee_skills.is_deleted', 0)
            ->where(function ($query) {
                $query->where('posts.is_active', 1)
                    ->orWhere('posts.is_active', null);
            })
            ->where(function ($query) {
                $query->where('posts.is_deleted', 0)
                    ->orWhere('posts.is_deleted', null);
            })
            ->where(function ($query) {
                $query->where('posts.is_post_expire', 0)
                    ->orWhere('posts.is_post_expire', null);
            })
            ->groupBy('employee_skills.id')
            ->orderBy('post_count', 'desc')
            ->get();
    }

    public static function get_all_featured_skills()
    {
        return EmployeeSkill::where('is_deleted', 0)
            ->where('status', 1)
            ->where('add_feature', 1)
            ->orderBy('skill', 'asc')
            ->get();
    }

    public static function getAllskill()
    {
        return EmployeeSkill::where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('skill', 'asc')
            ->get();
    }

    public static function getAllFeaturedskill()
    {
        return EmployeeSkill::where('is_deleted', 0)
            ->where('status', 1)
            ->where('add_feature', 1)
            ->orderBy('skill', 'asc')
            ->get();
    }

    public static function getAllSkillSetAndEmployer()
    {
        return EmployeeSkill::where('is_deleted', 0)
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere(function ($innerQuery) {
                        $innerQuery->where('employer_id', auth()->user()->id)
                            ->where('status', 0);
                    });
            })
            ->orderBy('skill', 'asc')
            ->get();
    }

    public static function getAllskillsSets($request)
    {
        $search = !empty($request->get('search')) ? $request->get('search') : '';
        $limit = (!empty(request('limit')) ? request('limit') : 30);
        $limit = (!empty(request('length')) ? request('length') : $limit);
        $status = (!empty(request('status')) ? request('status') : '');

        $skill = EmployeeSkill::with('user');
        $skill = $skill->where('is_deleted', 0);
        if (!empty($search)) {
            $skill = $skill->where('skill', 'like', '%' . $search . '%');
        }
        if (!empty($status)) {
            if ($status == 'pending') {
                $skill = $skill->where('status', '0');
            } elseif ($status == 'approved') {
                $skill = $skill->where('status', '1');
            }
        }
        $skill = $skill->orderBy('skill', 'asc');
        if (!empty(request()->get('start'))) {
            return $skill->skip(request()->get('start'))->take($limit)->get();
        }
        $skill = $skill->paginate($limit)->appends(request()->query());
        return $skill;
    }

    public static function getAllskillsSetsCount($all = false)
    {
        $skill = EmployeeSkill::with('user');
        if ($all == false) {
            $search = !empty(request('search')) ? request('search') : '';
            $limit = (!empty(request('limit')) ? request('limit') : 30);
            $limit = (!empty(request('length')) ? request('length') : $limit);
            $skill = $skill->where('is_deleted', 0);
            if (!empty($search)) {
                $skill = $skill->where('skill', 'like', '%' . $search . '%');
            }
            
        }

        $skill = $skill->count();
        return $skill;
    }

    public static function getAllskillsSetsByUserId()
    {
        return EmployeeSkill::where('employer_id', auth()->user()->id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();

    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public static function get_skill_name_by_id($id)
    {
        return EmployeeSkill::where('id', $id)->pluck('skill')->first();
    }

}
