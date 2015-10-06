<?php namespace App\Modules\Search\Repositories;

use App\Modules\Members\Models\Member;
use App\Modules\Members\Models\MemberGroup;
use App\Modules\Users\Models\User;
use App\Repositories\DbBaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DbSearchRepository extends DbBaseRepository implements SearchRepositoryInterface {

    private $members;
    private $memberGroups;
    private $users;

    public function __construct(Member $members, MemberGroup $memberGroups, User $users) {
        $this->members = $members;
        $this->memberGroups = $memberGroups;
        $this->users = $users;
    }

    public function findMembers($searchQuery) {
        return $this->filterUsers($this->members->with('group'), $searchQuery);
    }

    public function findMemberGroups($searchQuery) {
        return $this->filterGroups($this->memberGroups, $searchQuery);
    }

    public function findUsers($searchQuery) {
        return $this->filterUsers($this->users, $searchQuery);
    }

    public function filterUsers($model, $searchQuery) {
        $names = explode(' ', $searchQuery, 2);

        switch(count($names)) {
            case 1:
                $model = $model->where(function($query) use ($names){
                    $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                    $query->orWhere('last_name', 'LIKE', '%' . $names[0] . '%');
                    $query->orWhere('email', 'LIKE', '%' . $names[0] . '%');
                });
                break;
            case 2:
                $model = $model->where(function($query) use ($names){
                    $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                    $query->where('last_name', 'LIKE', '%' . $names[1] . '%');
                })->orWhere(function($query) use ($names){
                    $query->where('last_name', 'LIKE', '%' . $names[0] . '%');
                    $query->where('first_name', 'LIKE', '%' . $names[1] . '%');
                });
                break;
        }

        return $model->paginate(5);
    }

    public function filterGroups($model, $searchQuery) {
        $model = $model->where(function($query) use ($searchQuery){
            $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            $query->orWhere('description', 'LIKE', '%' . $searchQuery . '%');
        });

        return $model->paginate(5);
    }
}
