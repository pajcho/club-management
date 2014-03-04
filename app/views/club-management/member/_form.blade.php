<div class="form-group {{ $errors->has('first_name') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('first_name', 'First Name', array('class' => 'control-label')) }}
    {{ Form::text('first_name', null, array('class' => 'form-control')) }}
    @if($errors->has('first_name'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('first_name') }}</span>
    @endif
</div>

<div class="form-group {{ $errors->has('last_name') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('last_name', 'Last Name', array('class' => 'control-label')) }}
    {{ Form::text('last_name', null, array('class' => 'form-control')) }}
    @if($errors->has('last_name'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('last_name') }}</span>
    @endif
</div>

<div class="form-group {{ $errors->has('dob') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('dob', 'Date of Birth', array('class' => 'control-label')) }}
    {{ Form::text('dob', null, array('class' => 'form-control')) }}
    @if($errors->has('dob'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('dob') }}</span>
    @endif
</div>

<div class="form-group {{ $errors->has('dos') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('dos', 'Date of Subscription', array('class' => 'control-label')) }}
    {{ Form::text('dos', null, array('class' => 'form-control')) }}
    @if($errors->has('dos'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('dos') }}</span>
    @endif
</div>