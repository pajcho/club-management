<?php namespace App\Service;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class Theme
{

    /**
     * The default theme used by the app.
     *
     * @var string
     */
    protected static $theme = null;

    /**
     * Return view file name based on current theme
     * defined in configuration file
     *
     * @param type $file = View file name
     *
     * @return string
     */
    public static function view($file = null)
    {
        $viewName = static::name() . '.' . $file;

        if (!View::exists($viewName)) {
            $viewName = Config::get('club-management.default_theme') . '.' . $file;
        }

        return $viewName;
    }

    /**
     * Return view file path on current theme
     * defined in configuration file
     *
     * @param type $file = View file name
     *
     * @return type
     */
    public static function path($file = null)
    {
        // Check if theme asset exists, and if not load default asset
        $asset = asset('/' . Config::get('club-management.theme_dir') . '/' . static::name() . '/' . $file);
        $asset_path = public_path('/' . Config::get('club-management.theme_dir') . '/' . static::name() . '/' . $file);

        if (!file_exists($asset_path)) {
            $asset = asset('/' . Config::get('club-management.theme_dir') . '/' . Config::get('club-management.default_theme') . '/' . $file);
        }

        return $asset;
    }

    /**
     * Return current theme name from configuration file
     * @return type
     */
    public static function name()
    {
        return static::$theme ?: Config::get('club-management.theme');
    }

    /**
     * Return current theme name from configuration file
     *
     * @param type $theme = Theme name
     *
     * @return type
     */
    public static function set($theme)
    {
        return static::$theme = $theme;
    }
}
