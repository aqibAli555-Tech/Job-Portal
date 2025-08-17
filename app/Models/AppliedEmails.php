<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliedEmails extends Model
{
    use HasFactory;

    protected $table = 'applied_emails';
    protected $fillable = ['user_id', 'post_id', 'company_id', 'created_at', 'updated_at'];


    public function scopeIsEmailSent($query, $value = 0)
    {
        return $query->where('is_email_sent', $value);
    }

    public static function get_applied_emails()
    {
        $results = self::select('applied_emails.user_id', 'applied_emails.company_id', 'applied_emails.post_id')
        ->join('applicants', function ($join) {
            $join->on('applicants.user_id', '=', 'applied_emails.user_id')
                ->on('applicants.post_id', '=', 'applied_emails.post_id');
        })
        ->where('applicants.status', 'applied')
        ->isEmailSent(0)
        ->get();

        $groupedResults = [];
        foreach ($results as $result) {
            $company_id = $result->company_id;
            $post_id = $result->post_id;
            $user_id = $result->user_id;

            if (!isset($groupedResults[$company_id])) {
                $groupedResults[$company_id] = [];
            }

            if (!isset($groupedResults[$company_id][$post_id])) {
                $groupedResults[$company_id][$post_id] = [
                    'post_id' => $post_id,
                    'user_ids' => [],
                ];
            }

            if (!in_array($user_id, $groupedResults[$company_id][$post_id]['user_ids'])) {
                $groupedResults[$company_id][$post_id]['user_ids'][] = $user_id;
            }
        }

        foreach ($groupedResults as $company_id => &$posts) {
            foreach ($posts as $post_id => &$post) {
                $post['count'] = count($post['user_ids']);
            }
        }

        return ['groupedResults' => $groupedResults];
    }
}
