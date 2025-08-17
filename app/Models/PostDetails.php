<?php

namespace App\Models;

use App\Helpers\Ip;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostDetails extends BaseModel
{
    use HasFactory;

    protected $table = 'posts_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'post_id',
        'city_id',
        'longitude',
        'latitude',
        'ip_address',
        'transportation_available',
        'overtime_pay',
        'housing_available',
        'gender',
        'experiences',
        'nationality',
        'post_type',
        'skills_set',
        'hide_company_logo',
        'who_can_apply',
    ];

    public static function save_post_details_request($request, $city, $post_id)
    {

        if (empty($request->input('post_id'))) {
            $post_details = new PostDetails();
        } else {
            $post_details = PostDetails::where('post_id', $request->input('post_id'))->first();
        }
        $post_details->post_id = $post_id;
        $post_details->city_id = $city->id;
        $post_details->longitude = $city->longitude;
        $post_details->latitude = $city->latitude;
        $post_details->ip_address = Ip::get();
        $post_details->who_can_apply = !empty($request->get('who_can_apply')) ? $request->get('who_can_apply') : null;

        $post_details->transportation_available = $request->get('transportation_available');
        $post_details->overtime_pay = $request->get('overtime_pay');
        $post_details->housing_available = $request->get('housing_available');
        $post_details->gender = !empty($request->get('gender')) ? $request->get('gender') : 'Not Specified';
        $post_details->experiences = $request->get('experience');
        $post_details->hide_company_logo = !empty($request->get('hide_company_logo')) ? $request->get('hide_company_logo') : 0;
        if (!empty($request->get('nationality'))) {
            $post_details->nationality = implode(",", $request->get('nationality'));
        } else {
            $post_details->nationality = "1";
        }

        $post_details->post_type = $request->get('post_type');
        if (!empty($request->get('skill_set'))) {
            $post_details->skills_set = implode(",", $request->get('skill_set'));
        } else {
            $post_details->skills_set = "";
        }
        // track model save error

        // try {
        //     $saved = $post_details->save();

        //     if ($saved) {
        //         // Model saved successfully
        //         // Additional logic here
        //     } else {
        //         // Model failed to save
        //         // Log the error
        //        dd('Failed to save model: ' . $post_details->toJson());

        //     }
        // } catch (\Exception $e) {
        //     // Exception occurred during save operation
        //     // Log the error
        //     Log::error('Exception while saving model: ' . $e->getMessage());
        // }
        if ($post_details->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public static function get_post_details_by_post_id($post_id)
    {
        return PostDetails::where('post_id', $post_id)->first();

    }
}
