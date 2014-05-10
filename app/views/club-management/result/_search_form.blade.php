<div class="navbar navbar-default">

    <!-- Responsive part of search filters -->
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".filter">
        <i class="glyphicon glyphicon-search"></i>
    </button>
    <button class2="navbar-brand" type="button" class="navbar-toggle pull-left" style="margin-left: 15px;" data-toggle="collapse" data-target=".filter">{{ $filters_title }}</button>
    <div class="clearfix"></div>
    <!-- Responsive part of search filters -->

    <div class="collapse navbar-collapse filter" style="padding-top: 10px;">

        {{ Former::open()->method('GET')->action(route('result.index'))->dataPjax() }}
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::text('name')->label('Name') }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::select('type')->label('Type')->options($types) }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::select('year')->label('Year')->options($years) }}</div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">{{ Former::select('category_id')->label('Category')->options($categories) }}</div>
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
                            {{ HTML::decode(Former::default_link('<i class="glyphicon glyphicon-remove"></i>', route('result.index'))->addClass('form-control')) }}
                        </div>
                    </div>
                </div>
            </div>
        {{ Former::close() }}

    </div>

</div>