@extends(theme_view('layouts/auth'))

@section('content')
    <div class="container">
        <div class="row vertical-offset-100">
            <div class="col-md-4 col-md-offset-4">

                <h1 class="panel-heading text-center" style="color: white;">

                    {{ site_title() }}

                </h1>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please sign in</h3>
                    </div>
                    <div class="panel-body">
                        {{ Former::open()->action(route('login')) }}

                            <fieldset>
                                <div class="form-group">
                                    {{ Former::text('username')->placeholder('Username')->required() }}
                                </div>
                                <div class="form-group">
                                    {{ Former::password('password')->placeholder('Password')->required() }}
                                </div>
                                <div class="form-group">
                                    {{ Former::checkbox('remember')->text('Remember Me') }}
                                </div>

                                {{ Former::success_lg_block_submit('Login') }}
                            </fieldset>

                        {{ Former::close() }}
                    </div>

                    <div class="panel-footer">
                        <!-- Notifications -->
                        @include(theme_view('includes/notifications'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop