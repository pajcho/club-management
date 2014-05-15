<div class="row">
    <div class="col-lg-8 col-md-12">
        <h4 class="page-header alert alert-info">User Details</h4>
        <div class="row">
            <div class="col-md-4">
                {{ Former::text('first_name')->label('First Name')->required() }}
            </div>
            <div class="col-md-4">
                {{ Former::text('last_name')->label('Last Name')->required() }}
            </div>
            <div class="col-md-4">
                {{ Former::select('groups[]')->label('Groups')->options($groups)->required()->multiple()->dataPlaceholder('Select groups') }}
            </div>
        </div>

        <h4 class="page-header alert alert-info">Login Details</h4>
        <div class="row">
            <div class="col-md-4">
                {{ Former::text('username')->label('username')->required()->help('* Used for logging in. Must be unique.') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('email')->label('Email')->help('* Must be unique.') }}
            </div>
            <div class="col-md-4">
                {{ Former::select('type')->label('Type')->options($types)->required() }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                @if($form == 'update')
                {{ Former::password('password')->label('Password') }}
                @else
                {{ Former::password('password')->label('Password')->required() }}
                @endif
            </div>
            <div class="col-md-4">
                @if($form == 'update')
                {{ Former::password('password_confirm')->label('Confirm Password') }}
                @else
                {{ Former::password('password_confirm')->label('Confirm Password')->required() }}
                @endif
            </div>
        </div>

        <h4 class="page-header alert alert-info">Additional Details</h4>
        <div class="row">
            <div class="col-md-4">
                {{ Former::text('phone')->label('Phone') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('address')->label('Address') }}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <h4 class="page-header alert alert-info">Additional Notes</h4>
        <div class="row">
            <div class="col-md-12">
                {{ Former::textarea('notes')->label('Notes')->rows(20) }}
            </div>
        </div>
    </div>
</div>
