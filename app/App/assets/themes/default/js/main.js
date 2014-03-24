$(document).ready(function(){

    var dateFormat = 'DD.MM.YYYY';
    var timeFormat = 'HH:mm';

    $('.datetimepicker input').each(function(){
        $(this).attr('data-format', dateFormat + ' ' + timeFormat);
        $(this).datetimepicker({});
    });

    $('.datepicker input').each(function(){
        $(this).attr('data-format', dateFormat);
        $(this).datetimepicker({
            pickTime: false
        });
    });

    $('.timepicker input').each(function(){
        $(this).attr('data-format', timeFormat);
        $(this).datetimepicker({
            pickDate: false
        });
    });

    $(document).on('submit', '.delete-form', function(){
        return confirm('Are you sure you want to delete this item?');
    });

    // CLose alert messages after some time
    if($('.alert-message').length > 0)
    {
        window.setTimeout(function(){ return $('.alert-message .close').click(); }, 3000);
    }

});