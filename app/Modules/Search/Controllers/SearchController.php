<?php namespace App\Modules\Search\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Modules\Search\Repositories\SearchRepositoryInterface;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;

class SearchController extends AdminController
{
    private $search;

    public function __construct(SearchRepositoryInterface $search, Request $request)
    {
        parent::__construct();

        $this->search = $search;
        $this->request = $request;
    }

    public function all() {

        $results = [];
        $members = false;
        $memberGroups = false;
        $users = false;
        $searchQuery = $this->request->input('query');

        // See if we are filtering only one type, because if we start query with
        // type name we will not display other types at all
        if(starts_with($searchQuery, 'groups:') || starts_with($searchQuery, 'g:')) {
            $searchQuery = trim(str_replace('g:', '', $searchQuery));
            $searchQuery = trim(str_replace('groups:', '', $searchQuery));
            $memberGroups = $this->search->findMemberGroups($searchQuery);
        } else if(starts_with($searchQuery, 'members:') || starts_with($searchQuery, 'm:')) {
            $searchQuery = trim(str_replace('m:', '', $searchQuery));
            $searchQuery = trim(str_replace('members:', '', $searchQuery));
            $members = $this->search->findMembers($searchQuery);
        } else if(starts_with($searchQuery, 'trainers:') || starts_with($searchQuery, 't:')) {
            $searchQuery = trim(str_replace('t:', '', $searchQuery));
            $searchQuery = trim(str_replace('trainers:', '', $searchQuery));
            $users = $this->search->findUsers($searchQuery);
        } else {
            $members = $this->search->findMembers($searchQuery);
            $memberGroups = $this->search->findMemberGroups($searchQuery);
            $users = $this->search->findUsers($searchQuery);
        }

        if($members) {
            foreach($members as $key => $member) {
                $results[] = [
                    'active' => false,
                    'type' => 'Members',
                    'title' => $member->full_name,
                    'group' => $member->group ? $member->group->name : '',
                    'link' => [
                        'attendance' => route('member.payments.index', $member->id),
                        'edit' => route('member.update', $member->id),
                    ]
                ];
            }
        }

        if($memberGroups) {
            foreach($memberGroups as $key => $memberGroup) {
                $results[] = [
                    'active' => false,
                    'type' => 'Groups',
                    'title' => $memberGroup->name,
                    'subtitle' => '',
                    'link' => [
                        'attendance' => route('group.data.index', $memberGroup->id),
                        'edit' => route('group.update', $memberGroup->id),
                    ]
                ];
            }
        }

        if($users) {
            foreach($users as $key => $user) {
                $results[] = [
                    'active' => false,
                    'type' => 'Trainers',
                    'title' => $user->full_name,
                    'subtitle' => '',
                    'link' => [
                        'attendance' => route('user.attendance.index', $user->id),
                        'edit' => route('user.update', $user->id),
                    ]
                ];
            }
        }

        return $results;
    }

}
