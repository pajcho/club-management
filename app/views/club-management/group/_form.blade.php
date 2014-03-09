<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('name', 'Name', array('class' => 'control-label')) }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
            @if($errors->has('name'))
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span class="help-block">{{ $errors->first('name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('location') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('location', 'Location', array('class' => 'control-label')) }}
            {{ Form::text('location', null, array('class' => 'form-control')) }}
            @if($errors->has('location'))
                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                <span class="help-block">{{ $errors->first('location') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @foreach(array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') as $dayInWeek)

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label($dayInWeek, '&nbsp;', array('class' => 'control-label')) }}
                        {{ Form::label($dayInWeek, ucfirst($dayInWeek), array('class' => 'form-control btn-' . (is_object(Form::getValueAttribute("training")) && Form::getValueAttribute("training")->{$dayInWeek} ? 'success' : 'default') . ' active')) }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group date timepicker">
                        {{ Form::label('training[' . $dayInWeek . '][start]', 'Training starts', array('class' => 'control-label')) }}
                        {{ Form::text('training[' . $dayInWeek . '][start]', null, array('class' => 'form-control', 'autocomplete' => 'off')) }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group date timepicker">
                        {{ Form::label('training[' . $dayInWeek . '][end]', 'Training ends', array('class' => 'control-label')) }}
                        {{ Form::text('training[' . $dayInWeek . '][end]', null, array('class' => 'form-control', 'autocomplete' => 'off')) }}
                    </div>
                </div>
            </div>

        @endforeach

        <span class="help-block">* Only days that have both start and end time populated will be used as active group training days. Other days will just be ignored.</span>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('description') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('description', 'Description', array('class' => 'control-label')) }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}

            <span class="help-block">* Here you can write anything you want about this member group. For example who is working on this location and with this group.</span>
            @if($errors->has('description'))
                <span class="help-block">{{ $errors->first('description') }}</span>
            @endif
        </div>
    </div>
</div>

