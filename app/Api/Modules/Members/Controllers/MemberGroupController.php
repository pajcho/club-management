<?php namespace Api\Modules\Members\Controllers;

use Api\Controllers\ApiController;
use App\Modules\Members\Internal\Validators\MemberGroupValidator;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class MemberGroupController extends ApiController {

    private $memberGroups;
    private $users;

	public function __construct(MemberGroupRepositoryInterface $memberGroups, UserRepositoryInterface $users)
	{
		parent::__construct();

        $this->memberGroups = $memberGroups;
        $this->users = $users;
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $paginate = Input::get('paginate', 'true') === 'true';
        $embeds = array_filter(explode(',', Input::get('embeds')));

		// Get all member groups
        return $this->memberGroups->filterWith($embeds, Input::get(), $paginate);
	}

    /**
	 * Return additional values to be used
	 */
	public function filters()
	{
        $users = $this->users->getForSelect();

        return compact('users');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        //
	}

    /**
     * Store a newly created resource in storage.
     *
     * @throws StoreResourceFailedException
     * @return Response
     */
	public function store()
	{
        $validator = new MemberGroupValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            return $this->memberGroups->create($validator->data());
        }

        // validation failed
        throw new StoreResourceFailedException('Could not create new member group.', $validator->errors());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $embeds = array_filter(explode(',', Input::get('embeds')));

        $memberGroup = $this->memberGroups->findWith($id, $embeds);

        if(!$memberGroup) App::abort(404);

        return $memberGroup;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        //
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @throws UpdateResourceFailedException
     * @return Response
     */
	public function update($id)
	{
        $validator = new MemberGroupValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            return $this->memberGroups->update($id, $validator->data());
        }

        // validation failed
        throw new UpdateResourceFailedException('Could not update member group.', $validator->errors());
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @throws \Dingo\Api\Exception\DeleteResourceFailedException
     * @return Response
     */
	public function destroy($id)
	{
        if($this->memberGroups->find($id))
        {
            // We can not delete group that has members
            if(!$this->memberGroups->canBeDeleted($id))
            {
                throw new DeleteResourceFailedException('Member group already has members! In order to delete this group first remove all members from it.');
            }

            $this->memberGroups->delete($id);
        }

        return array(
            'deleted'   => true,
            'id'        => $id,
        );
	}
}
