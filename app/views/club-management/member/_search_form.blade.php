{{ Form::open(array('method' => 'GET', 'route' => 'member.index', 'class' => 'form')) }}

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {{ Form::label('name', 'Name', array('class' => 'control-label')) }}
            {{ Form::text('name', Input::get('name', ''), array('class' => 'form-control')) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('group_id', 'Group', array('class' => 'control-label')) }}
            {{ Form::select('group_id', array('' => 'Group') + $groups, Input::get('group_id', ''), array('class' => 'form-control')) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('location', 'Location', array('class' => 'control-label')) }}
            {{ Form::select('location', array('' => 'Location') + $locations, Input::get('location', ''), array('class' => 'form-control')) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('active', 'Active', array('class' => 'control-label')) }}
            {{ Form::select('active', array('' => 'All members', '1' => 'Active Members', '0' => 'Inactive Members'), Input::get('active', ''), array('class' => 'form-control')) }}
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            {{ Form::label('search', '&nbsp;', array('class' => 'control-label')) }}
            {{ Form::submit('Search', array('class' => 'btn btn-info form-control')) }}
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            {{ Form::label('search', '&nbsp;', array('class' => 'control-label')) }}
            {{ link_to_route('member.index', 'Reset', null, array('class' => 'btn btn-default form-control')) }}
        </div>
    </div>
</div>

{{ Form::close() }}