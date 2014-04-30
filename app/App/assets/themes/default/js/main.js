$(document).ready(function(){

    var dateFormat = 'DD.MM.YYYY';
    var timeFormat = 'HH:mm';

    var applicationInit = function()
    {
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

        // Close alert messages after some time
        // except when message is error
        if($('.alert-message').length > 0)
        {
            $('.alert-message').each(function(){
                if(!$(this).hasClass('alert-danger'))
                {
                    var element = $(this);
                    window.setTimeout(function(){ return $('.close', element).click(); }, 3000);
                }
            });
        }

        // Select2 bindings
        if($('select').length > 0)
        {
            $('select').each(function(){
                $(this).select2({
                    minimumResultsForSearch: 5
                });
            });
        }
    }

    applicationInit();

    /**
    * PJAX pagination bindings
    */
    $(document).pjax('.pjax-pagination a', '#pjax-container');

    $(document).on('click', 'form[data-pjax] [type=submit]', function() {
        $(this).after('<input type="hidden" name="' + this.name + '" value="' + this.value + '"/>');
    });

    $(document).on('submit', 'form[data-pjax]', function(event) {
        $.pjax.submit(event, '#pjax-container');
    });

    $(document).on('pjax:send', function() {
        NProgress.start();
    });
    $(document).on('pjax:complete', function() {
        NProgress.done();
    });
    // disable the fallback timeout behavior if a spinner is being shown
    $(document).on('pjax:timeout', function(event) {
        // Prevent default timeout redirection behavior
        event.preventDefault();
    });
});