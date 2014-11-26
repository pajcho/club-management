<?php namespace App\Modules\Dashboard\Repositories;

use App\Modules\Members\Models\Member;
use App\Modules\Members\Models\MemberGroup;
use App\Repositories\DbBaseRepository;
use Illuminate\Support\Facades\DB;

class DbDashboardRepository extends DbBaseRepository implements DashboardRepositoryInterface {

    private $members;
    private $memberGroups;

    public function __construct(Member $members, MemberGroup $memberGroups)
    {
        $this->members = $members;
        $this->memberGroups = $memberGroups;
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