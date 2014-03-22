$(document).ready(function(){
    $('.datetimepicker input').each(function(){
        $(this).attr('data-format', 'DD.MM.YYYY hh:mm A');
        $(this).datetimepicker({});
    });
    $('.datepicker input').each(function(){
        $(this).attr('data-format', 'DD.MM.YYYY');
        $(this).datetimepicker({
            pickTime: false
        });
    });
    $('.timepicker input').each(function(){
        $(this).attr('data-format', 'HH:mm');
        $(this).datetimepicker({
            pickDate: false
        });
    });

    $(document).on('submit', '.delete-form', function(){
        return confirm('Are you sure you want to delete this item?');
    });

});