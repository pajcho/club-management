<div class="row">
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('first_name') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('first_name', 'First Name', array('class' => 'control-label')) }}
            {{ Form::text('first_name', null, array('class' => 'form-control')) }}
            @if($errors->has('first_name'))
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span class="help-block">{{ $errors->first('first_name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('last_name') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('last_name', 'Last Name', array('class' => 'control-label')) }}
            {{ Form::text('last_name', null, array('class' => 'form-control')) }}
            @if($errors->has('last_name'))
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span class="help-block">{{ $errors->first('last_name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('group_id') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('group_id', 'Group', array('class' => 'control-label')) }}
            {{ Form::select('group_id', $groups, null, array('class' => 'form-control')) }}

            @if($errors->has('group_id'))
                <span class="help-block">{{ $errors->first('group_id') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('uid') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('uid', 'Unique Identifier', array('class' => 'control-label')) }}
            {{ Form::text('uid', null, array('class' => 'form-control')) }}

            <span class="help-block">* Unique identifier for every member</span>
            @if($errors->has('uid'))
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span class="help-block">{{ $errors->first('uid') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('phone') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('phone', 'Phone', array('class' => 'control-label')) }}
            {{ Form::text('phone', null, array('class' => 'form-control')) }}

            <span class="help-block">* If you have more than one phone number, you can put it in notes section below</span>
            @if($errors->has('phone'))
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span class="help-block">{{ $errors->first('phone') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('active') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('active', 'Active', array('class' => 'control-label')) }}
            {{ Form::select('active', array('1' => 'Yes', '0' => 'No'), null, array('class' => 'form-control')) }}

            <span class="help-block">* Inactive members won't show up in generated PDF lists</span>
            @if($errors->has('active'))
                <span class="help-block">{{ $errors->first('active') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('dob') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('dob', 'Date of Birth', array('class' => 'control-label')) }}

            <div class="input-group date datepicker">
                {{ Form::text('dob', is_object(Form::getValueAttribute("dob")) ? Form::getValueAttribute("dob")->format('F j, Y') : Form::getValueAttribute("dob"), array('class' => 'form-control', 'autocomplete' => 'off')) }}
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>

            <span class="help-block">Example: October 12, 2014</span>
            @if($errors->has('dob'))
                <span class="help-block">{{ $errors->first('dob') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('dos') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('dos', 'Date of Subscription', array('class' => 'control-label')) }}

            <div class="input-group date datepicker">
                {{ Form::text('dos', is_object(Form::getValueAttribute("dos")) ? Form::getValueAttribute("dos")->format('F j, Y') : Form::getValueAttribute("dos"), array('class' => 'form-control', 'autocomplete' => 'off')) }}
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>

            <span class="help-block">Example: October 12, 2014</span>
            @if($errors->has('dos'))
                <span class="help-block">{{ $errors->first('dos') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('doc') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('doc', 'Doctors Check', array('class' => 'control-label')) }}

            <div class="input-group date datepicker">
                {{ Form::text('doc', is_object(Form::getValueAttribute("doc")) ? Form::getValueAttribute("doc")->format('F j, Y') : Form::getValueAttribute("doc"), array('class' => 'form-control', 'autocomplete' => 'off')) }}
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>

            <span class="help-block">Example: October 12, 2014</span>
            @if($errors->has('doc'))
                <span class="help-block">{{ $errors->first('doc') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('notes') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('notes', 'Notes', array('class' => 'control-label')) }}
    {{ Form::textarea('notes', null, array('class' => 'form-control')) }}

    <span class="help-block">* Here you can write anything you want about this member. For example parent names, additional contact details, etc.</span>
    @if($errors->has('notes'))
        <span class="help-block">{{ $errors->first('notes') }}</span>
    @endif
</div>