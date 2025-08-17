<?php

namespace App\Observers;

use App\Helpers\Helper;
use App\Models\Applicant;
use App\Models\RejectedReason;

class ApplicantObserver
{
    /**
     * Handle the Applicant "created" event.
     *
     * @param \App\Models\Applicant $applicant
     * @return void
     */
    public function created(Applicant $applicant)
    {
        //
    }


    public function updating(Applicant $applicant)
    {
        if ($applicant->isDirty('status')) {
            $oldStatus = $applicant->getOriginal('status');
            $newStatus = $applicant->status;
            $company_name = auth()->user()->name;
            $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $employee_url = admin_url() . '/job-seekers?search=' . $applicant->user->email;
            $employee_name = $applicant->user->name;

            if (auth()->user()->is_admin == 1) {
                $author_name = 'admin';
            } else {
                $author_name = 'company';
            }
            $description = "The $author_name <a href='$profile_url'><strong>$company_name</strong></a> has updated the status of an applicant. The status was changed from <strong>$oldStatus</strong> to <strong>$newStatus</strong> for the job seeker <a href='$employee_url'><strong>$employee_name</strong></a>.";
            if ($newStatus == 'rejected') {
                $reason_rejected = RejectedReason::get_reason_with_id($applicant->rejected_reason_id);
                $reason_rejected_name = !empty($reason_rejected->title) ? $reason_rejected->title : '';
                $description .= " Rejecetd Reason: $reason_rejected_name";
            }
            Helper::activity_log($description);
        }
    }

    /**
     * Handle the Applicant "updated" event.
     *
     * @param \App\Models\Applicant $applicant
     * @return void
     */
    public function updated(Applicant $applicant)
    {
        //
    }

    /**
     * Handle the Applicant "deleted" event.
     *
     * @param \App\Models\Applicant $applicant
     * @return void
     */
    public function deleted(Applicant $applicant)
    {
        //
    }

    /**
     * Handle the Applicant "restored" event.
     *
     * @param \App\Models\Applicant $applicant
     * @return void
     */
    public function restored(Applicant $applicant)
    {
        //
    }

    /**
     * Handle the Applicant "force deleted" event.
     *
     * @param \App\Models\Applicant $applicant
     * @return void
     */
    public function forceDeleted(Applicant $applicant)
    {
        //
    }
}
