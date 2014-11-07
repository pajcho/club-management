<div class="navbar navbar-default">

    <!-- Responsive part of search filters -->
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".filter">
        <i class="fa fa-search"></i>
    </button>
    <button class2="navbar-brand" type="button" class="navbar-toggle pull-left" style="margin-left: 15px;" data-toggle="collapse" data-target=".filter">{{ $filters_title }}</button>
    <div class="clearfix"></div>
    <!-- Responsive part of search filters -->

    <div class="collapse navbar-collapse filter" style="padding-top: 10px;">

        {{ Former::open()->method('GET')->action(route('result.category.index'))->dataPjax() }}
            <div class="row">
                <div class="col-lg-10 col-md-8">{{ Former::text('name')->label('Filter result categories') }}</div>
                <div class="col-lg-2 col-md-4">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            {{ Former::label('&nbsp;') }}
                            {{ HTML::decode(Former::info_button('search')->type('submit')->addClass('form-control')->value('<i class="fa fa-search"></i>')) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            {{ Former::label('&nbsp;') }}
                            {{ HTML::decode(Former::default_link('<i class="fa fa-remove"></i>', route('result.category.index'))->addClass('form-control')) }}
                        </div>
                    </div>
                </div>
            </div>
        {{ Former::close() }}

    </div>

</div>