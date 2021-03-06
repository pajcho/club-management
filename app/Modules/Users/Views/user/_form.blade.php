<?php $columnClass = $currentUser->isAdmin() ? 'col-md-4' : 'col-md-6'; ?>

<div class="row">
    <div class="col-lg-8 col-md-12">
        <div class="row">
            <div class="{{ $columnClass }}">
                {!! Former::text('first_name')->label('First Name')->required() !!}
            </div>
            <div class="{{ $columnClass }}">
                {!! Former::text('last_name')->label('Last Name')->required() !!}
            </div>
            @if($currentUser->isAdmin())
            <div class="col-md-4">
                    {!! Former::select('groups[]')->label('Groups')->options($groups)->multiple()->dataPlaceholder('Select groups') !!}
            </div>
            @endif
        </div>

        <div class="row">
            <div class="{{ $columnClass }}">
                {!! Former::text('username')->label('username')->required()->help('* Used for logging in. Must be unique.') !!}
            </div>
            <div class="{{ $columnClass }}">
                {!! Former::text('email')->label('Email')->help('* Must be unique.') !!}
            </div>
            @if($currentUser->isAdmin())
            <div class="col-md-4">
                {!! Former::select('type')->label('Type')->options($types)->required() !!}
            </div>
            @endif
        </div>

        <div class="row">
            <div class="{{ $columnClass }}">
                {!! Former::text('phone')->label('Phone') !!}
            </div>
            <div class="{{ $columnClass }}">
                {!! Former::text('address')->label('Address') !!}
            </div>
        </div>

        {{--On update page we want to hide password fields by default because we dont need them that often--}}
        {{--We will use javascript trigger to show them on page once required--}}
        @if($form == 'update')
            <div class="row">
                <div class="col-md-12">
                    {!! Former::checkbox('change_password')->text('Change password? ') !!}
                </div>
            </div>
        @endif
        <div class="row {{ $form == 'update' ? 'change_password hidden' : '' }}">
            <div class="{{ $columnClass }}">
                @if($form == 'update')
                    {!! Former::password('password')->label('Password') !!}
                @else
                    {!! Former::password('password')->label('Password')->required() !!}
                @endif
            </div>
            <div class="{{ $columnClass }}">
                @if($form == 'update')
                    {!! Former::password('password_confirm')->label('Confirm Password') !!}
                @else
                    {!! Former::password('password_confirm')->label('Confirm Password')->required() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="row">
            <div class="col-md-12">
                {!! Former::textarea('notes')->label('Notes')->rows(20) !!}
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">

        var showHidePasswords = function(){
            if($("#change_password").is(":checked"))
            {
                $('.change_password').removeClass('hidden');
            }
            else
            {
                $('.change_password').addClass('hidden')
            }
        }

        $(document).on('change', '#change_password', function(){
            showHidePasswords();
        });

        showHidePasswords()

    </script>
@stop
