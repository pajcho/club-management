{{ Former::open()->method('GET')->action(route('member.index')) }}

    <div class="row">
        <div class="col-md-3">{{ Former::text('name') }}</div>
        <div class="col-md-2">{{ Former::select('group_id')->options(array('' => 'Group') + $groups)->label('Group') }}</div>
        <div class="col-md-2">{{ Former::select('location')->options(array('' => 'Location') + $locations) }}</div>
        <div class="col-md-2">{{ Former::select('active')->options(array('' => 'All members', '1' => 'Active Members', '00' => 'Inactive Members')) }}</div>
        <div class="col-md-1">
            {{ Former::label('&nbsp') }}
            {{ Former::info_button('search')->type('submit')->addClass('form-control') }}
        </div>
        <div class="col-md-1">
            {{ Former::label('&nbsp') }}
            {{ Former::default_link('Reset', route('member.index'))->addClass('form-control') }}
        </div>
    </div>

{{ Former::close() }}