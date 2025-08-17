<?php

namespace App\Helpers\Search\Traits\Filters;

trait SalaryFilter
{
    protected function applySalaryFilter()
    {
        if (!isset($this->having)) {
            return;
        }

        $minSalary = null;
        if (request()->filled('minSalary')) {
            $minSalary = request()->get('minSalary');
        }

        $maxSalary = null;
        if (request()->filled('maxSalary')) {
            $maxSalary = request()->get('maxSalary');
        }

        if (!empty($minSalary) && empty($maxSalary)) {
            $this->having[] = 'salary_min >= ' . $minSalary;
        } else {
            if (request()->has('minSalary')) {
                $this->having[] = '(salary_min BETWEEN  ' . $minSalary . ' and ' . $maxSalary . ' or salary_max BETWEEN  ' . $minSalary . ' and ' . $maxSalary . ')';
            }
        }
        if (!empty($maxSalary)) {
            $this->orHaving[] = '(salary_max BETWEEN  ' . $minSalary . ' and ' . $maxSalary . ')';
        }
    }
}
