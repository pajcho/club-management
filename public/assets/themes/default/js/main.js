$(document).ready(function(){
    $('.datetimepicker input').each(function(){
        $(this).attr('data-format', 'MMMM D, YYYY hh:mm A');
        $(this).datetimepicker({});
    });
    $('.datepicker input').each(function(){
        $(this).attr('data-format', 'MMMM D, YYYY');
        $(this).datetimepicker({
            pickTime: false
        });
    });
    $('.timepicker input').each(function(){
        $(this).attr('data-format', 'hh:mm A');
        $(this).datetimepicker({
            pickDate: false
        });
    });

    $(document).on('submit', '.delete-form', function(){
        return confirm('Are you sure you want to delete this item?');
    });

});