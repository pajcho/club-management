<?php


/*
|--------------------------------------------------------------------------
| Delete form macro
|--------------------------------------------------------------------------
|
| This macro creates a form with only a submit button.
| We'll use it to generate forms that will post to a certain url with the DELETE method,
| following REST principles.
|
*/
Form::macro('delete',function($url, $button_label = 'Delete', $button_options = array()){

    $button_options['data-method'] = 'delete';
    return link_to($url, $button_label, $button_options);

});