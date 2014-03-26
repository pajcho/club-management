<div class="row">
    <div class="col-md-4">
        {{ Former::text('first_name')->label('First Name')->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::text('last_name')->label('Last Name')->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('group_id')->label('Group')->options($groups)->required() }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        {{ Former::text('uid')->label('Unique Identifier')->required()->help('* Unique identifier for every member') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('phone')->label('Phone')->help('* If you have more than one phone number, you can put it in notes section below') }}
    </div>
    <div class="col-md-4">
        {{ Former::select('active')->label('Active')->options(array('1' => 'Yes', '0' => 'No'))->help('* Inactive members won\'t show up in generated PDF lists') }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        {{
            Former::text('dob')->forceValue(Former::getPost('dob', is_object(Former::getValue('dob')) ? Former::getValue('dob')->format('d.m.Y') : Former::getValue('dob')))
                ->label('Date of Birth')
                ->required()
                ->addGroupClass('date datepicker')
                ->append('<i class="glyphicon glyphicon-calendar"></i>')
                ->help('Example: 21.10.2014')
        }}
    </div>
    <div class="col-md-4">
        {{
            Former::text('dos')->forceValue(Former::getPost('dos', is_object(Former::getValue('dos')) ? Former::getValue('dos')->format('d.m.Y') : Former::getValue('dos')))
                ->label('Date of Subscription')
                ->required()
                ->addGroupClass('date datepicker')
                ->append('<i class="glyphicon glyphicon-calendar"></i>')
                ->help('Example: 12.10.2014')
        }}
    </div>
    <div class="col-md-4">
        {{
            Former::text('doc')->forceValue(Former::getPost('doc', is_object(Former::getValue('doc')) ? Former::getValue('doc')->format('d.m.Y') : Former::getValue('doc')))
                ->label('Medical Examination')
                ->addClass(is_object(Former::getValue('doc')) ? (Former::getValue('doc')->gte(\Carbon\Carbon::now()->startOfDay()) ? 'btn-success' : 'btn-danger') : 'btn-warning')
                ->addGroupClass('date datepicker')
                ->append('<i class="glyphicon glyphicon-calendar"></i>')
                ->help('Example: 21.10.2014')
        }}
    </div>
</div>

{{ Former::textarea('notes')->label('Notes')->rows(5)->help('* Here you can write anything you want about this member. For example parent names, additional contact details, etc.') }}
