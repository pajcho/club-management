<div class="row">
    <div class="col-md-4">
        {{ Former::text('first_name')->label('First Name')->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::text('last_name')->label('Last Name')->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('type')->label('Type')->options($types)->required() }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        {{ Former::text('username')->label('username')->required()->help('* Used for logging in. Must me unique.') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('email')->label('Email')->required()->help('* Must be unique.') }}
    </div>
    <div class="col-md-4">
        {{ Former::select('groups[]')->label('Groups')->options($groups)->required()->multiple()->dataPlaceholder('Select groups') }}
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