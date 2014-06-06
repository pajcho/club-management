<?php namespace Api\Modules\Members\Transformers;

use App\Modules\Members\Models\MemberGroupDetails;
use League\Fractal\TransformerAbstract;

class MemberGroupDetailsTransformer extends TransformerAbstract
{
    public $availableIncludes = array(
        'group', 'history'
    );

    public $defaultIncludes = array(
        //
    );

    public function transform(MemberGroupDetails $memberGroupDetails)
    {
        return [
            'id'              => (int) $memberGroupDetails->id,
            'group_id'        => (int) $memberGroupDetails->group_id,
            'year'            => (int) $memberGroupDetails->year,
            'year123'            => (int) $memberGroupDetails->year,
            'month'           => (int) $memberGroupDetails->month,
            'details'         => $memberGroupDetails->details(),
            'created_at'      => (string) $memberGroupDetails->created_at,
            'updated_at'      => (string) $memberGroupDetails->updated_at,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/groups/'.$memberGroupDetails->group_id.'/details/'.$memberGroupDetails->year.'/'.$memberGroupDetails->month,
                ]
            ]
        ];
    }

    public function includeGroup(MemberGroupDetails $memberGroupDetails)
    {
        $group = $memberGroupDetails->group;

        return $this->item($group, new MemberGroupTransformer);
    }
}