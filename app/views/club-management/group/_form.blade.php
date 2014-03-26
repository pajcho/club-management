<div class="row">
    <div class="col-md-6">
        {{ Former::text('name')->label('Name')->required() }}
    </div>
    <div class="col-md-6">
        {{ Former::text('location')->label('Location')->required() }}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @foreach(array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') as $dayInWeek)

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Former::label($dayInWeek)->value('&nbsp;') }}
                        {{ Former::label($dayInWeek)->value(ucfirst($dayInWeek))->class('form-control btn-' . (is_object(Former::getValue("training")) && Former::getValue("training")->{$dayInWeek} ? 'success' : 'default') . ' active') }}
                    </div>
                </div>

                <div class="col-md-4">
                    {{ Former::text('training[' . $dayInWeek . '][start]')->autocomplete('off')->label('Training starts')->addGroupClass('date timepicker') }}
                </div>
                <div class="col-md-4">
                    {{ Former::text('training[' . $dayInWeek . '][end]')->autocomplete('off')->label('Training ends')->addGroupClass('date timepicker') }}
                </div>
            </div>

        @endforeach

        <span class="help-block">* Only days that have both start and end time populated will be used as active group training days. Other days will just be ignored.</span>
    </div>

    <div class="col-md-6">
        {{ Former::textarea('description')->label('Description')->rows(10)->help('* Here you can write anything you want about this member group. For example who is working on this location and with this group.') }}
    </div>
</div>

