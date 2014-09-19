<div class="navbar navbar-default">

    <!-- Responsive part of search filters -->
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".filter">
        <i class="glyphicon glyphicon-search"></i>
    </button>
    <button class2="navbar-brand" type="button" class="navbar-toggle pull-left" style="margin-left: 15px;" data-toggle="collapse" data-target=".filter">{{ $filters_title }}</button>
    <div class="clearfix"></div>
    <!-- Responsive part of search filters -->

    <div class="collapse navbar-collapse filter" style="padding-top: 10px;">

        {{ Former::open()->method('GET')->action(route('member.index'))->dataPjax() }}
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::text('name')->label('Name or Email') }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::select('group_id')->label('Group')->options($groups) }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::select('location')->label('Location')->options($locations) }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::select('active')->label('Active')->options($member_status) }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::select('freeOfCharge')->label('Free Of Charge')->options($member_free) }}</div>
                <div class="col-lg-2 col-md-8">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            {{ Former::label('&nbsp;') }}
                            {{ HTML::decode(Former::info_button('search')->type('submit')->addClass('form-control')->value('<i class="glyphicon glyphicon-search"></i>')) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            {{ Former::label('&nbsp;') }}
                            {{ HTML::decode(Former::default_link('<i class="glyphicon glyphicon-remove"></i>', route('member.index'))->addClass('form-control')) }}
                        </div>
                    </div>
                </div>
            </div>
        {{ Former::close() }}

    </div>

</div>