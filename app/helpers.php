<?php

    /**
     * Site Title
     *
     * Helper that allows you to easily get the site title
     *
     * @return string
     */
    function site_title()
    {
        return app('config')->get('settings.site_title');
    }