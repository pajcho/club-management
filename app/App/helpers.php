<?php

    use Illuminate\Support\Facades\Config;
    use App\Service\Theme;

    /**
     * Site Title
     *
     * Helper that allows you to easily get the site title
     *
     * @return string
     */
    function site_title()
    {
        return Config::get('settings.site_title');
    }

    /**
     * Theme Path
     *
     * Helper that allows you to easily get a theme path inside the views.
     * Example: @extends(theme_path('layout'))
     *
     * @param string $file - The file to load
     *
     * @return string
     */
    function theme_path($file = null)
    {
        return Theme::path($file);
    }

    /**
     * Theme View Path
     *
     * Helper that allows you to easily get a theme view path inside the views.
     * Example: @extends(theme_path('layout'))
     *
     * @param string $file - The file to load
     *
     * @return string
     */
    function theme_view($file = null)
    {
        return Theme::view($file);
    }

    /**
     * Theme Name
     *
     * Helper that allows you to easily get a theme name.
     *
     * @param string $file - The file to load
     *
     * @return string
     */
    function theme_name()
    {
        return Theme::name();
    }