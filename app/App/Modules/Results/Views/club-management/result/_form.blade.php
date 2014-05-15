<div class="row">
    <div class="col-md-4">
        {{ Former::select('member_id')->label('Member')->options($members)->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('category_id')->label('Category')->options($categories)->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('subcategory')->label('Subcategory')->options($subcategories)->required() }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        {{ Former::select('year')->label('Year')->options($years)->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('place')->label('Place')->options($places)->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('type')->label('Type')->options($types)->required() }}
    </div>
</div>

{{ Former::textarea('notes')->label('Notes')->rows(5)->help('* Here you can write anything you want about this result.') }}
