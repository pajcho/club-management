<?php namespace Api\Modules\Members\Transformers;

use Api\Modules\Users\Transformers\UserTransformer;
use App\Modules\Members\Models\Member;
use League\Fractal\TransformerAbstract;

class MemberTransformer extends TransformerAbstract
{
    public $availableIncludes = array(
        'group', 'activeHistory', 'freeOfChargeHistory', 'results', 'trainers', 'history'
    );

    public $defaultIncludes = array(
        //
    );

    public function transform(Member $member)
    {
        return [
            'id'            => (int) $member->id,
            'group_id'      => (int) $member->group_id,
            'uid'           => $member->uid,
            'full_name'     => $member->full_name,
            'first_name'    => $member->first_name,
            'last_name'     => $member->last_name,
            'phone'         => $member->phone,
            'notes'         => $member->notes,
            'dob'           => (string) $member->dob,
            'dos'           => (string) $member->dos,
            'doc'           => (string) $member->doc,
            'active'        => (int) $member->active,
            'freeOfCharge'  => (int) $member->freeOfCharge,
            'created_at'    => (string) $member->created_at,
            'updated_at'    => (string) $member->updated_at,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/members/'.$member->id,
                ]
            ]
        ];
    }

    public function includeGroup(Member $member)
    {
        $group = $member->group;

        return $this->item($group, new MemberGroupTransformer);
    }

    public function includeTrainers(Member $member)
    {
        $trainers = $member->trainers;

        return $this->collection($trainers, new UserTransformer);
    }
}