<?php namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\AdminController;
use App\Modules\Dashboard\Repositories\DashboardRepositoryInterface;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use App\Services\Theme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class DashboardController extends AdminController
{
    private $dashboard;

    public function __construct(DashboardRepositoryInterface $dashboard)
    {
        parent::__construct();

        View::share('activeMenu', 'dashboard');

        $this->dashboard = $dashboard;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dashboard = $this->dashboard;

        // Refresh dashboard data daily
        $dataRaw = Cache::remember('dashboard', 24*60, function() use ($dashboard){
            $monthlyMembers = $dashboard->newMonthlyMembers();
            $yearlyMembers = $dashboard->newYearlyMembers();
            $membersYearOfBirthPie = $dashboard->membersYearOfBirth();

            foreach ($membersYearOfBirthPie as $key => $value) {
                $membersYearOfBirthPie[] = [(date('Y') - date('Y', strtotime($key))) . ' years', $value];
                unset($membersYearOfBirthPie[ $key ]);
            }

            return [
                'monthlyMembers'        => [
                    ['x'] + array_keys($monthlyMembers),
                    ['New members per month'] + array_values($monthlyMembers)
                ],
                'yearlyMembers'         => [
                    ['x'] + array_keys($yearlyMembers),
                    ['New members per year'] + array_values($yearlyMembers)
                ],
                'membersYearOfBirthPie' => $membersYearOfBirthPie,
                'membersYearOfBirth'    => $this->generateMembersYearOfBirthData(),
                'totalMembers'          => $dashboard->totalMembers(),
                'totalActiveMembers'    => $dashboard->totalActiveMembers(),
                'totalGroups'           => $dashboard->totalGroups(),
                'totalTrainers'         => $dashboard->totalTrainers(),
                'upcomingBirthdays'     => $dashboard->upcomingBirthdays(),
            ];
        });

        $data = json_encode($dataRaw);

        return view(Theme::view('dashboard.index'))->with(compact('data', 'dataRaw'));
    }

    private function generateMembersYearOfBirthData()
    {
        $return = [];
        $arrayOfMemberYears = [];
        $memberYearsOfBirth = [];
        $subscriptionYears = $this->dashboard->getSubscriptionYears();

        foreach ($subscriptionYears as $key => $year) {
            $memberYearsOfBirth[ $year ] = $this->dashboard->membersYearOfBirth($year);
            $memberYears = $memberYearsOfBirth[ $year ];

            if (!empty($memberYears)) {
                foreach ($memberYears as $i => $o) {
                    $value = ($year - date('Y', strtotime($i)));
                    if (!in_array($value, $arrayOfMemberYears))
                        array_push($arrayOfMemberYears, $value);
                }
            }
        }

        $max = max($arrayOfMemberYears);

        $arrayOfMemberYears = range(1, $max + 1);

        sort($arrayOfMemberYears);
        array_unshift($arrayOfMemberYears, 'x');
        $return[] = $arrayOfMemberYears;

        foreach ($subscriptionYears as $key => $year) {
            $memberYears = $memberYearsOfBirth[ $year ];

            if (!empty($memberYears)) {
                foreach ($memberYears as $i => $o) {
                    $memberYears[ $year - date('Y', strtotime($i)) + 1 ] = $o;
                    unset($memberYears[ $i ]);
                }

                foreach ($arrayOfMemberYears as $i => $o) {
                    if (is_numeric($o) && !array_key_exists($o, $memberYears)) {
                        $memberYears[ $o ] = '0';
                    }
                }

                ksort($memberYears);

                $tmpArray = array_values($memberYears);
                array_unshift($tmpArray, $year);
                $return[] = $tmpArray;
            }
        }

        return $return;
    }

}
