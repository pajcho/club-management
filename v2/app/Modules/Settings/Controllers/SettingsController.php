<?php namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\AdminController;
use App\Modules\Settings\Repositories\SettingsRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class SettingsController extends AdminController {

    private $settings;

    public function __construct(SettingsRepositoryInterface $settings)
	{
		parent::__construct();

        View::share('activeMenu', 'settings');

        $this->settings = $settings;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	public function index()
	{
		// Get all settings
        $settings = $this->settings->all();

        return view('settings.index')->with(compact('settings'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $this->settings->saveSettings(Input::get('settings'));

        return redirect(route('settings.index'))->withSuccess('Settings updated!');
	}

    /**
     * Clear all application cache.
     *
     * @return Response
     */
	public function clearCache()
	{
        Cache::flush();

        return back()->withSuccess('Cache cleared!');
	}
}
