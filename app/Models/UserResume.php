<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class UserResume extends Model
{
    use HasFactory;

    protected $table = 'user_resume';
    protected $fillable = ['user_id', 'cv', 'is_approved', 'created_at', 'updated_at'];

    public static function check_unapproved_user_cv($user_id)
    {
        return self::where('user_id', $user_id)->where('is_approved', 0)->first();
    }

    public static function check_new_user_cv($user_id)
    {
        return self::where('user_id', $user_id)->where('is_read', 0)->orderBy('id', 'DESC')->first();
    }

    public static function update_read_status($id)
    {
        self::where('id', $id)->update(['is_read' => 1]);
    }

    public static function update_status($id, $status)
    {
        $userResume = self::find($id);
        
        if (!$userResume) {
            return false;
        }

        $updated = self::where('user_id', $userResume->user_id)
            ->update([
                'is_approved' => $status,
                'updated_at' => now(),
            ]);

        if (!$updated) {
            return false;
        }

        $user = User::find($userResume->user_id);
        if ($status == 1) {
            self::deleteIfExists($user->employee_cv);
            self::deleteIfExists($user->cv_no_contact);

            $user->employee_cv = $userResume->cv;
            $user->cv_no_contact = null;
            $user->is_approved_no_contact_cv = 3;
            $user->is_resume_uploaded_on_aws = 0;
            $user->save();
            
        } else {
            self::deleteIfExists($userResume->cv);
        }

        Notification::add_new_notification($userResume->user_id, 'resume', 0);

        return true;
    }

    private static function deleteIfExists($filePath)
    {
        if (!empty($filePath) && file_exists(public_path('/') .'storage/'. $filePath)) {
            unlink('public/storage/' . $filePath);
        }
    }



    // public static function update_status($id, $status)
    // {
    //     $user_resume = self::where('id', $id)->first();
    //     if (!empty($user_resume)) {
    //         $all_user_Cv =self::where('user_id', $user_resume->user_id)
    //             ->update([
    //                 'is_approved' => $status,
    //                 'updated_at' => now()
    //             ]);
    //         if ($all_user_Cv) {
    //             if ($status == 1) {
    //                 $user_data = User::where('id', $user_resume->user_id)->first();
    //                 $remoteFileUrl_employee_cv = 'public/storage/' . $user_data->employee_cv;
    //                 if (!empty($user_data->employee_cv)) {
    //                     if (file_exists($remoteFileUrl_employee_cv)) {
    //                         unlink($remoteFileUrl_employee_cv);
    //                     }
    //                 }
    //                 $remoteFileUrl_cv_no_contact = 'public/storage/' . $user_data->cv_no_contact;
    //                 if (!empty($user_data->cv_no_contact)) {
    //                     if (file_exists($remoteFileUrl_cv_no_contact)) {
    //                         unlink($remoteFileUrl_cv_no_contact);
    //                     }
    //                 }

    //                 $user_data->employee_cv = $user_resume->cv;
    //                 $user_data->cv_no_contact = null;
    //                 $user_data->is_approved_no_contact_cv = 2;
    //                 $user_data->is_resume_uploaded_on_aws = 0;
    //                 $user_data->save();
    //                 Notification::add_new_notification($user_resume->user_id, 'resume', 0);
    //             } else {
    //                 $user_data = User::where('id', $user_resume->user_id)->first();
    //                 $remoteFileUrl_employee_cv = 'public/storage/' . $user_resume->cv;
    //                 if (!empty($user_data->employee_cv)) {
    //                     if (file_exists($remoteFileUrl_employee_cv)) {
    //                         unlink($remoteFileUrl_employee_cv);
    //                     }
    //                 }
    //                 Notification::add_new_notification($user_resume->user_id, 'resume', 0);
    //             }
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } else {
    //         return false;
    //     }
    // }

    public static function get_all_unapproved_cv()
    {
        return self::where('is_approved', 0)->get();
    }
}
