<div class="row">
    <div class="col-lg-8 col-md-12">
        <div class="row">
            <div class="col-md-6">
                {!! Former::text('first_name')->label('First Name')->required() !!}
            </div>
            <div class="col-md-6">
                {!! Former::text('last_name')->label('Last Name')->required() !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! Former::text('email')->label('Email') !!}
            </div>
            <div class="col-md-6">
                {!! Former::select('group_id')->label('Group')->options($groups)->required() !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                {!! Former::text('uid')->label('Unique Identifier')->help('* Unique identifier for every member') !!}
            </div>
            <div class="col-md-6">
                {!! Former::text('phone')->label('Phone')->help('* If you have more than one phone number, you can put it in notes section below') !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        {!! Former::select('active')->label('Active')->options(array('1' => 'Yes', '0' => 'No'))->help('* Inactive members won\'t show up in generated PDF lists') !!}
                    </div>
                    <div class="col-md-6">
                        {!! Former::select('freeOfCharge')->label('Free Of Charge')->options(array('0' => 'No', '1' => 'Yes'))->help('* Members that don\'t pay (like family and friends)') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                {!!
                    Former::text('doc')->forceValue(Former::getPost('doc', is_object(Former::getValue('doc')) ? Former::getValue('doc')->format('d.m.Y') : Former::getValue('doc')))
                        ->label('Medical Examination')
                        ->addClass(is_object(Former::getValue('doc')) ? (Former::getValue('doc')->gte(\Carbon\Carbon::now()->startOfDay()) ? 'btn-success' : 'btn-danger') : 'btn-warning')
                        ->addGroupClass('date datepicker')
                        ->append('<i class="fa fa-calendar"></i>')
                        ->help('Example: 21.10.2014 <i>(Enter date of expire)</i>')
                        ->autocomplete('off')
                !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                {!!
                    Former::text('dob')->forceValue(Former::getPost('dob', is_object(Former::getValue('dob')) ? Former::getValue('dob')->format('d.m.Y') : Former::getValue('dob')))
                        ->label('Date of Birth')
                        ->required()
                        ->addGroupClass('date datepicker')
                        ->append('<i class="fa fa-calendar"></i>')
                        ->help('Example: 21.10.2014')
                        ->autocomplete('off')
                !!}
            </div>
            <div class="col-md-6">
                {!!
                    Former::text('dos')->forceValue(Former::getPost('dos', is_object(Former::getValue('dos')) ? Former::getValue('dos')->format('d.m.Y') : Former::getValue('dos')))
                        ->label('Date of Subscription')
                        ->required()
                        ->addGroupClass('date datepicker')
                        ->append('<i class="fa fa-calendar"></i>')
                        ->help('Example: 12.10.2014')
                        ->autocomplete('off')
                !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="row">
            <div class="col-md-12">
                {!! Former::textarea('notes')->label('Notes')->rows(20)->help('* Here you can write anything you want about this member. For example parent names, additional contact details, etc.') !!}
            </div>
        </div>
    </div>
</div>
