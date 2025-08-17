<?php

namespace App\Helpers\Search\Traits;

use App\Helpers\Search\Traits\Relations\CategoryRelation;
use App\Helpers\Search\Traits\Relations\PaymentRelation;

trait Relations
{
    use CategoryRelation, PaymentRelation;

    protected function setRelations()
    {
        if (!isset($this->posts)) {
            dd('Fatal Error: Search relations cannot be applied.');
        }

        // category
//		$this->setCategoryRelation();

        // postType
        $this->posts->with('postType')->has('postType');

        // latestPayment
        $this->setPaymentRelation();

        // city
        $this->posts->with('postDetail')->has('postDetail');
        $this->posts->with('postDetail.city')->has('postDetail.city');
        $this->posts->with('postMeta')->has('postMeta');
        $this->posts->with('employeeskill')->has('employeeskill');

        // salaryType
        $this->posts->with('salaryType')->has('salaryType');

        // savedByLoggedUser
        $this->posts->with('savedByLoggedUser');
    }
}
