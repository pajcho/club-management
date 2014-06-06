<?php namespace Api\Modules\Users\Transformers;

use App\Modules\Users\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public $availableIncludes = array(
        'groups'
    );

    public $defaultIncludes = array(
        //
    );

    public function transform(User $user)
    {
        return [
            'id'            => (int) $user->id,
            'full_name'     => $user->full_name,
            'first_name'    => $user->first_name,
            'last_name'     => $user->last_name,
            'username'      => $user->username,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'group_ids'     => $user->group_ids,
            'created_at'    => (string) $user->created_at,
            'updated_at'    => (string) $user->updated_at,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/users/'.$user->id,
                ]
            ]
        ];
    }
}