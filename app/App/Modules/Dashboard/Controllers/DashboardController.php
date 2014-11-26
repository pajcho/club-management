<?php namespace App\Modules\Dashboard\Controllers;

use App\Controllers\AdminController;
use App\Modules\Dashboard\Repositories\DashboardRepositoryInterface;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use App\Service\Theme;
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
        $monthlyMembers = $this->dashboard->newMonthlyMembers();
        $yearlyMembers = $this->dashboard->newYearlyMembers();
        $membersYearOfBirthPie = $this->dashboard->membersYearOfBirth();

        $totalMembers = array_sum($membersYearOfBirthPie);
        foreach($membersYearOfBirthPie as $key => $value)
        {
            $membersYearOfBirthPie[] = [(date('Y') - date('Y', strtotime($key))) . ' years', $value];
            unset($membersYearOfBirthPie[$key]);
        }

        $data = [
            'monthlyMembers' => [
                ['x'] + array_keys($monthlyMembers),
                ['New members per month'] + array_values($monthlyMembers)
            ],
            'yearlyMembers' => [
                ['x'] + array_keys($yearlyMembers),
                ['New members per year'] + array_values($yearlyMembers)
            ],
            'membersYearOfBirthPie' => $membersYearOfBirthPie,
            'membersYearOfBirth' => [],
            'memberYearsOfBirthAxes' => [],
        ];

//        dd($data['membersYearOfBirthPie']);

        foreach($this->dashboard->getSubscriptionYears() as $key => $year)
        {
            $memberYears = $this->dashboard->membersYearOfBirth($year);

            if(!empty($memberYears))
            {
                $data['memberYearsOfBirthAxes'][$year] = 'x' . ($key + 1);

                $yearsArray = array_keys($memberYears);
                foreach($yearsArray as $yearsArrayKey => $yearsArrayValue)
                {
                    $yearsArray[$yearsArrayKey] = $year - date('Y', strtotime($yearsArrayValue));
                }

                $data['membersYearOfBirth'][] = ['x' . ($key + 1)] + $yearsArray;
                $data['membersYearOfBirth'][] = [$year] + array_values($memberYears);
            }
        }

//        echo json_encode($data['memberYearsOfBirthAxes']); exit;

        $data = json_encode($data);

        return View::make(Theme::view('dashboard.index'))->with(compact('data'));
    }

}
