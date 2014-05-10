<?php namespace App\Controllers;

use App\Repositories\HistoryRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class HistoryController extends AdminController {

    private $history;

    public function __construct(HistoryRepositoryInterface $history)
	{
		parent::__construct();

        View::share('activeMenu', 'history');

        $this->history = $history;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	public function index()
	{
        // Get all history
        $history = $this->history->filter(Input::get());

        // Generate filters title
        $filters_title = 'Filter history';
        if(Input::get('message') ?: false) $filters_title = Input::get('message');

        return View::make(Theme::view('history.index'))->with(compact('history', 'filters_title'));
	}
}
