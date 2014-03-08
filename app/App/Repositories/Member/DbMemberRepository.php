<?php namespace App\Repositories\Member;

use App\Models\Member;

class DbMemberRepository implements MemberRepositoryInterface {

    protected $perPage = 15;

    /**
     * Get all members
     * 
     * @return type
     */
    public function getAll()
    {
        return Member::all();
    }

    /**
     * Filter members
     */
    public function filter($params = array())
    {
        $members = new Member();

        // Default filter by every database column
        foreach($members->getColumnNames() as $column)
        {
            if(isset($params[$column]) && ($params[$column] === '0' || !empty($params[$column])))
            {
                $members = $members->where($column, '=', $params[$column]);
            }
        }

        // Filter by name
        if(isset($params['name']) && !empty($params['name']))
        {
            $names = explode(' ', $params['name'], 2);

            switch(count($names))
            {
                case 1:
                    $members = $members->where(function($query) use ($names){
                        $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                        $query->orWhere('last_name', 'LIKE', '%' . $names[0] . '%');
                    });
                    break;
                case 2:
                    $members = $members->where(function($query) use ($names){
                        $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                        $query->where('last_name', 'LIKE', '%' . $names[1] . '%');
                    })->orWhere(function($query) use ($names){
                        $query->where('last_name', 'LIKE', '%' . $names[0] . '%');
                        $query->where('first_name', 'LIKE', '%' . $names[1] . '%');
                    });
                    break;
            }
        }

        // Filter by location
        if(isset($params['location']) && !empty($params['location']))
        {
            $members = $members->whereHas('group', function($query) use ($params){
                $query->where('location', '=', $params['location']);
            });
        }

        return $members->orderBy('dos', 'desc')->paginate($this->perPage);
    }

    /**
     * Get member by ID
     * 
     * @param type $id = Member ID
     * @return type
     */
    public function getById($id)
    {
        return Member::find((int)$id);
    }
    
    /**
     * Create new member
     * 
     * @param type $data = Input data
     */
    public function create($data)
    {
        Member::create($data);
    }
}