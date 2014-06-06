<?php namespace Api\Modules\Members\Transformers;

use Api\Modules\Users\Transformers\UserTransformer;
use App\Modules\Members\Models\MemberGroup;
use League\Fractal\TransformerAbstract;

class MemberGroupTransformer extends TransformerAbstract
{
    public $availableIncludes = array(
        'members', 'trainers', 'details', 'history'
    );

    public $defaultIncludes = array(
        //
    );

    public function transform(MemberGroup $memberGroup)
    {
        return [
            'id'                    => (int) $memberGroup->id,
            'name'                  => $memberGroup->name,
            'location'              => $memberGroup->location,
            'description'           => $memberGroup->description,
            'training'              => $memberGroup->training,
            'trainer_ids'           => $memberGroup->trainer_ids,
            'total_monthly_time'    => $memberGroup->total_monthly_time,
            'created_at'            => (string) $memberGroup->created_at,
            'updated_at'            => (string) $memberGroup->updated_at,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/groups/'.$memberGroup->id,
                ]
            ]
        ];
    }

    public function includeMembers(MemberGroup $memberGroup)
    {
        $members = $memberGroup->members;

        return $this->collection($members, new MemberTransformer);
    }

    public function includeTrainers(MemberGroup $memberGroup)
    {
        $trainers = $memberGroup->trainers;

        return $this->collection($trainers, new UserTransformer);
    }

    public function includeDetails(MemberGroup $memberGroup)
    {
        $details = $memberGroup->details;

        return $this->collection($details, new MemberGroupDetailsTransformer);
    }
}