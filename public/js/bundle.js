(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
 * Create a confirm modal
 * We want to send an HTTP DELETE request
 *
 * @usage  <a href="posts/2" data-method="delete" data-modal-text="Are you sure you want to delete">
 *
 */
'use strict';

var confirmPopup = {
    initialize: function initialize() {
        this.methodLinks = $('a[data-method]');
        this.registerEvents();
    },

    registerEvents: function registerEvents() {
        this.methodLinks.on('click', this.handleMethod);
    },

    handleMethod: function handleMethod(e) {
        e.preventDefault();
        var link = $(this);

        var httpMethod = link.data('method').toUpperCase();
        var allowedMethods = ['PUT', 'DELETE'];
        var extraMsg = link.data('modal-text');

        // Set default message ending if none is defined
        if (typeof extraMsg == "undefined") extraMsg = "do this?";

        var msg = '<i class="fa fa-warning modal-icon text-danger"></i>&nbsp;Are you sure you want to&nbsp;' + extraMsg;

        // If the data-method attribute is not PUT or DELETE,
        // then we don't know what to do. Just ignore.
        if ($.inArray(httpMethod, allowedMethods) === -1) {
            return;
        }

        bootbox.dialog({
            message: msg,
            title: "Please Confirm",
            buttons: {
                success: {
                    label: "OK",
                    className: "btn-danger",
                    callback: function callback() {
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': link.attr('href')
                        });

                        var hiddenInput = $('<input>', {
                            'name': '_method',
                            'type': 'hidden',
                            'value': link.data('method')
                        });

                        var hiddenToken = $('<input>', {
                            'name': '_token',
                            'type': 'hidden',
                            'value': link.data('token')
                        });

                        form.append(hiddenInput).append(hiddenToken).appendTo('body').submit();
                    }
                },
                danger: {
                    label: "Cancel",
                    className: "btn-default"
                }
            }
        });
    }
};

$(document).ready(function () {

    alert('Alert!!');

    var dateFormat = 'DD.MM.YYYY';
    var timeFormat = 'HH:mm';

    var applicationInit = function applicationInit() {
        $('.datetimepicker input').each(function () {
            $(this).attr('data-format', dateFormat + ' ' + timeFormat);
            $(this).datetimepicker({});
        });

        $('.datepicker input').each(function () {
            $(this).attr('data-format', dateFormat);
            $(this).datetimepicker({
                pickTime: false
            });
        });

        $('.timepicker input').each(function () {
            $(this).attr('data-format', timeFormat);
            $(this).datetimepicker({
                pickDate: false
            });
        });

        $(document).on('submit', '.delete-form', function () {
            return confirm('Are you sure you want to delete this item?');
        });

        // Close alert messages after some time
        // except when message is error
        if ($('.alert-message').length > 0) {
            $('.alert-message').each(function () {

                var message = $('span', this).html();
                var options = {
                    // Toastr configuration options
                    closeButton: true,
                    positionClass: 'toast-bottom-right'
                };

                if ($(this).hasClass('alert-success')) toastr.success(message, 'Success', options);
                if ($(this).hasClass('alert-warning')) toastr.warning(message, 'Warning', options);
                if ($(this).hasClass('alert-info')) toastr.info(message, 'Info', options);
                if ($(this).hasClass('alert-danger')) toastr.error(message, 'Error', options);

                //if(!$(this).hasClass('alert-danger'))
                //{
                //    var element = $(this);
                //    window.setTimeout(function(){ return $('.close', element).click(); }, 3000);
                //}
            });
        }

        // Select2 bindings
        if ($('select').length > 0) {
            $('select').each(function () {
                $(this).select2({
                    minimumResultsForSearch: 5
                });
            });
        }

        $('.ajax-content').each(function () {
            var element = $(this).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax($(this).data('url')).done(function (response) {
                element.html(response);
            });
        });

        // Tooltips
        $('[title]').tooltip();

        // Confirmation popups
        confirmPopup.initialize();
    };

    applicationInit();

    /**
    * PJAX pagination bindings
    */
    $(document).pjax('.pjax-pagination a', '#pjax-container');

    $(document).on('click', 'form[data-pjax] [type=submit]', function () {
        $(this).after('<input type="hidden" name="' + this.name + '" value="' + this.value + '"/>');
    });

    $(document).on('submit', 'form[data-pjax]', function (event) {
        $.pjax.submit(event, '#pjax-container');
    });

    $(document).on('pjax:send', function () {
        NProgress.start();
    });
    $(document).on('pjax:complete', function () {
        NProgress.done();
        applicationInit();
    });
    // disable the fallback timeout behavior if a spinner is being shown
    $(document).on('pjax:timeout', function (event) {
        // Prevent default timeout redirection behavior
        event.preventDefault();
    });
});

},{}],2:[function(require,module,exports){
'use strict';

window.SearchGlobal = {};

},{}],3:[function(require,module,exports){
// import './libraries/jquery-1.11.0.min.js';
// import './libraries/jquery.pjax.js';
// import './libraries/bootstrap.js';
// import './libraries/moment.min.js';
// import './libraries/bootstrap-datetimepicker.js';
// import './libraries/nprogress.js';
// import './libraries/select2.js';
// import './libraries/bootbox.js';
// import './libraries/d3.v3.js';
// import './libraries/c3.js';
// import './libraries/toastr.js';

'use strict';

require('./_main.js');

require('./_search-global.js');

},{"./_main.js":1,"./_search-global.js":2}]},{},[3]);
