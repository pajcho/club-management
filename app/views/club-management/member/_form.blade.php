<div class="row">
    <div class="col-md-4">
        {{ Former::text('first_name')->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::text('last_name')->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('group_id')->options($groups)->required()->label('Group') }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        {{ Former::text('uid')->required()->label('Unique Identifier')->help('* Unique identifier for every member') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('phone')->help('* If you have more than one phone number, you can put it in notes section below') }}
    </div>
    <div class="col-md-4">
        {{ Former::select('active')->options(array('1' => 'Yes', '0' => 'No'))->help('* Inactive members won\'t show up in generated PDF lists') }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        {{
            Former::text('dob')->forceValue(Former::getPost('dob', is_object(Former::getValue('dob')) ? Former::getValue('dob')->format('F j, Y') : Former::getValue('dob')))
                ->required()
                ->addGroupClass('date datepicker')
                ->append('<i class="glyphicon glyphicon-calendar"></i>')
                ->label('Date of Birth')
                ->help('Example: October 12, 2014')
        }}
    </div>
    <div class="col-md-4">
        {{
            Former::text('dos')->forceValue(Former::getPost('dos', is_object(Former::getValue('dos')) ? Former::getValue('dos')->format('F j, Y') : Former::getValue('dos')))
                ->required()
                ->addGroupClass('date datepicker')
                ->append('<i class="glyphicon glyphicon-calendar"></i>')
                ->label('Date of Subscription')
                ->help('Example: October 12, 2014')
        }}
    </div>
    <div class="col-md-4">
        {{
            Former::text('doc')->forceValue(Former::getPost('doc', is_object(Former::getValue('doc')) ? Former::getValue('doc')->format('F j, Y') : Former::getValue('doc')))
                ->addGroupClass('date datepicker')
                ->append('<i class="glyphicon glyphicon-calendar"></i>')
                ->label('Doctors Check')
                ->help('Example: October 12, 2014')
        }}
    </div>
</div>

{{ Former::textarea('notes')->rows(5)->help('* Here you can write anything you want about this member. For example parent names, additional contact details, etc.') }}
