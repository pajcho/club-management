<?php

return array(

    // Site title
    'title'         => 'Club Management',
    
    // Theme directory
    'theme_dir'     => 'assets/themes',
    
    // Application theme name 
    // (Locatied under app/views/themes/theme_name)
	'theme'         => 'club-management',
    
    // Application default theme name 
    // (Locatied under app/views/themes/theme_name)
	'default_theme' => 'default',

    // Emails in this array will be in BCC of every email
    'admin_emails' => array(

    ),

    // Results options
    'result' => array(
        'types' => array(
            'Pojedinacno', 'Ekipno', 'Po spravi',
        ),
        'subcategories' => array(
            'Predselekcija',
            'I selekcija',
            'II selekcija',
            'III selekcija',
            'IV selekcija',
            'Pioniri',
            'Kadeti',
            'Juniori',
            'Seniori',
            'I - IV razred',
            'V - VI razred',
            'VII - VIII razred',
            'Srednja skola',
        ),
    ),

);