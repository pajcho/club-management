<?php namespace App\Internal;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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
        $this->createHistory($this->getMessage('created new'));
    }

    public function postUpdate()
    {
        $this->createHistory($this->getMessage('updated'));
    }

    public function postDelete()
    {
        $this->createHistory($this->getMessage('deleted'));
    }

    private function createHistory($message)
    {
        $historyRepo = App::make('App\Modules\History\Repositories\HistoryRepositoryInterface');

        $historyRepo->create(array(
            'historable_type' => get_class($this),
            'historable_id' => $this->getKey(),
            'user_id' => $this->getUserId(),
            'message' => $message,
        ));
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
            $user_model = Config::get('auth.model');
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

}