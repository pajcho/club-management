<?php namespace App\Modules\Search\Repositories;

use App\Modules\Members\Models\Member;
use App\Modules\Members\Models\MemberGroup;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use App\Modules\Users\Models\User;
use App\Repositories\DbBaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DbSearchRepository extends DbBaseRepository implements SearchRepositoryInterface {

    private $members;
    private $memberGroups;
    private $users;

    public function __construct(MemberRepositoryInterface $members, MemberGroupRepositoryInterface $memberGroups, User $users) {
        $this->members = $members;
        $this->memberGroups = $memberGroups;
        $this->users = $users;
    }

    public function findMembers($searchQuery) {
        return $this->members->paginate(5)->filter(['name' => $searchQuery]);
    }

    public function findMemberGroups($searchQuery) {
        return $this->memberGroups->paginate(5)->filter(['search' => $searchQuery]);
    }

    public function findUsers($searchQuery) {
        $names = explode(' ', $searchQuery, 2);

        switch(count($names)) {
            case 1:
                $this->users = $this->users->where(function($query) use ($names){
                    $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                    $query->orWhere('last_name', 'LIKE', '%' . $names[0] . '%');
                    $query->orWhere('email', 'LIKE', '%' . $names[0] . '%');
                });
                break;
            case 2:
                $this->users = $this->users->where(function($query) use ($names){
                    $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                    $query->where('last_name', 'LIKE', '%' . $names[1] . '%');
                })->orWhere(function($query) use ($names){
                    $query->where('last_name', 'LIKE', '%' . $names[0] . '%');
                    $query->where('first_name', 'LIKE', '%' . $names[1] . '%');
                });
                break;
        }

        return $this->users->paginate(5);
    }
}
