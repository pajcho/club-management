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
Form::macro('delete',function($url, $button_label = 'Delete', $form_parameters = array(), $button_options = array()){

    if(empty($form_parameters))
    {
        $form_parameters = array(
            'method'    => 'DELETE',
            'class'     => 'delete-form',
            'url'       => $url
        );
    }
    else
    {
        $form_parameters['url'] = $url;
        $form_parameters['method'] = 'DELETE';
        $form_parameters['class'] = trim($form_parameters['class'] . ' delete-form');
    }

    $button_options['type'] = 'submit';

    return Form::open($form_parameters)
    . Form::button($button_label, $button_options)
    . Form::close();
});