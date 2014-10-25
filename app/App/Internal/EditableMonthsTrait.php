<?php namespace App\Internal;

use Carbon\Carbon;

trait EditableMonthsTrait {

    /**
     * Get editable months for group
     *
     * @param int $startYear
     * @param int $startMonth
     * @return array
     */
    private function getEditableMonths($startYear = 2013, $startMonth = 1)
    {
        $months = array();
        $start = Carbon::createFromDate($startYear, $startMonth);
        $end = Carbon::now()->addMonth();

        while($end->gte($start))
        {
            array_push($months, $end->copy());
            $end->subMonth();
        }

        return $months;

    }

} 