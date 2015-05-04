<?php namespace App\Internal;

use Illuminate\Support\Facades\Auth;

trait HistorableTrait {

    public static function boot()
    {
        parent::boot();

        static::created(function($model)
        {
            $model->postCreate();
        });

        static::updated(function($model)
        {
            $model->postUpdate();
        });

        static::deleted(function($model)
        {
            $model->postDelete();
        });

    }

    public function history()
    {
        return $this->morphMany('\App\Modules\History\Models\History', 'historable');
    }

    /**
     * Get table name string to use for generating messages
     *
     * @return mixed
     */
    public function historyTable()
    {
        return str_singular($this->getTable());
    }

    /**
     * Get title to use for generating messages
     *
     * @return mixed
     */
    public function historyTitle()
    {
        return $this->getKey();
    }

    public function postCreate()
    {
        $this->createHistory('created new');
    }

    public function postUpdate()
    {
        $this->createHistory('updated');
    }

    public function postDelete()
    {
        $this->createHistory('deleted');
    }

    private function createHistory($actionString)
    {
        if($this->shouldBeLogged($actionString))
        {
            $historyRepo = app()->make('App\Modules\History\Repositories\HistoryRepositoryInterface');

            $historyRepo->create(array(
                'historable_type' => get_class($this),
                'historable_id' => $this->getKey(),
                'user_id' => $this->getUserId(),
                'message' => $this->getMessage($actionString),
            ));
        }
    }

    /**
     * Attempt to find the user id of the currently logged in user
     **/
    private function getUserId()
    {
        try
        {
            if(Auth::check())
            {
                return Auth::user()->getAuthIdentifier();
            }
        }
        catch(\Exception $e)
        {
            return null;
        }

        return null;
    }

    /**
     * Get currently logged user object
     */
    private function getUser()
    {
        if($userId = $this->getUserId())
        {
            $user_model = app('config')->get('auth.model');
            return $user_model::find($userId);
        }

        return null;
    }

    /**
     * Generate history message
     *
     * @param $actionString
     * @return string
     */
    private function getMessage($actionString)
    {
        $user = $this->getUser();
        $title = $actionString == 'deleted' ? strip_tags($this->historyTitle(), '<strong>') : $this->historyTitle();

        return sprintf(
            '%s %s %s %s',
            $user ? $user->full_name : 'Anonymous',
            $actionString,
            $this->historyTable(),
            $title
        );
    }

    /**
     * Check if history should be logged
     * This is hardcoded at this moment until we figure a way to make it more flexible
     *
     * @param $actionString
     * @return boolean
     */
    private function shouldBeLogged($actionString)
    {
        $user = $this->getUser();

        if($actionString == 'updated')
        {
            // For now we don't want to log any change that user makes on his own account
            // This is mainly because every time user logs in cookie is refreshed and profile updated
            // And each time this is logged in history, and we dont want this to happen any more from now on
            if($this->historyTable() == 'user' && $user->full_name == $this->getFullNameAttribute()) return false;
        }

        return true;
    }

}