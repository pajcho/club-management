<?php

    use Illuminate\Support\Facades\Config;

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