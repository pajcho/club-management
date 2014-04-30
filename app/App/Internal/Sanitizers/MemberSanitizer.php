<?php namespace App\Internal\Sanitizers;

use Carbon\Carbon;
use DateTime;

class MemberSanitizer extends BaseSanitizer
{
    protected $inputDateFormat = 'd.m.Y';
    protected $outputDateFormat = 'Y-m-d';

    public function sanitize($data)
    {
        if(isset($data['first_name']))
            $data['first_name'] = ucwords(trim($data['first_name']));
        
        if(isset($data['last_name']))
            $data['last_name'] = ucwords(trim($data['last_name']));

        if(isset($data['active']))
            $data['active'] = (int)$data['active'];

        // Handle date formats
        $dates = array('dob', 'dos', 'doc');
        foreach($dates as $date)
        {
            if(isset($data[$date]))
            {
                if(!empty($data[$date]) && $this->validateDate($data[$date], $this->inputDateFormat))
                {
                    $data[$date] = Carbon::createFromFormat($this->inputDateFormat, $data[$date])->format($this->outputDateFormat);
                }
                else
                {
                    $data[$date] = NULL;
                }
            }
        }

        return $data;
    }

    /**
     * Check if string is valid date or not
     *
     * @param $date
     * @param string $format
     * @return bool
     */
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}