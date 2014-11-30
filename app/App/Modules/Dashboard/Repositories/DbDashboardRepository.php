<?php namespace App\Modules\Dashboard\Repositories;

use App\Modules\Members\Models\Member;
use App\Modules\Members\Models\MemberGroup;
use App\Modules\Users\Models\User;
use App\Repositories\DbBaseRepository;
use Illuminate\Support\Facades\DB;

class DbDashboardRepository extends DbBaseRepository implements DashboardRepositoryInterface {

    private $members;
    private $memberGroups;
    private $users;

    public function __construct(Member $members, MemberGroup $memberGroups, User $users)
    {
        $this->members = $members;
        $this->memberGroups = $memberGroups;
        $this->users = $users;
    }

    public function totalMembers()
    {
        return $this->members->count();
    }

    public function totalActiveMembers()
    {
        return $this->members->all()->filter(function($item){
            return $item->activeOnDate(date('Y'), date('m'));
        })->count();
    }

    public function totalGroups()
    {
        return $this->memberGroups->count();
    }

    public function totalTrainers()
    {
        return $this->users->count();
    }

    public function upcomingBirthdays()
    {
        $date = date('Y-m-d');
        return $this->members->select(DB::raw("*,
            DATE_FORMAT('$date', '%Y') - DATE_FORMAT(dob, '%Y') + IF(
            DATE_FORMAT(dob, '%m%d') < DATE_FORMAT('$date', '%m%d'), 1, 0) AS new_age,
            DATEDIFF(dob + INTERVAL YEAR('$date') - YEAR(dob) +
            IF(DATE_FORMAT('$date', '%m%d') > DATE_FORMAT(dob, '%m%d'), 1, 0) YEAR,
            '$date') AS days_to_birthday"))
            ->having('days_to_birthday', '<', 14)
            ->orderBy('days_to_birthday', 'asc')->limit(5)->get();
    }

    public function newMonthlyMembers()
    {
        return $this->members->select(DB::raw('COUNT(id) as total, dos as month, dos'))
            ->groupBy(DB::raw('YEAR(dos)'))->groupBy(DB::raw('MONTH(dos)'))->get()->lists('total', 'month');
    }

    public function newYearlyMembers()
    {
        return $this->members->select(DB::raw('COUNT(id) as total, dos as year, dos'))
            ->groupBy(DB::raw('YEAR(dos)'))->get()->lists('total', 'year');
    }

    public function membersYearOfBirth($year = null)
    {
        $data = $this->members->select(DB::raw('COUNT(id) as total, dob as year, dob'));

        if(!is_null($year)) $data = $data->where(DB::raw("YEAR(dos)"), $year);

        return $data->groupBy(DB::raw('YEAR(dob)'))->get()->lists('total', 'year');
    }

    /** HELPER FUNCTIONS */

    public function getSubscriptionYears()
    {
        return $this->members->select(DB::raw('YEAR(dos) as year, dos'))
            ->groupBy(DB::raw('YEAR(dos)'))->get()->lists('year');
    }

    public function getBirthYears()
    {
        return $this->members->select(DB::raw('YEAR(dob) as year, dob'))
            ->groupBy(DB::raw('YEAR(dob)'))->get()->lists('year');
    }
}