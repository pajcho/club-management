<div class="row">
    <div class="col-md-6">
        {{ Former::text('name')->label('Name')->required() }}
    </div>
    <div class="col-md-6">
        {{ Former::hidden('parent_id')->value(0) }}
    </div>
</div>