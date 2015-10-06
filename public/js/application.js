(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

angularApp.constant('cfg', {
    baseUrl: 'http://' + window.location.hostname + '/'
});

},{}],2:[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var DataProvider = (function () {
    function DataProvider($http, $q, cfg) {
        _classCallCheck(this, DataProvider);

        this.$http = $http;
        this.$q = $q;
        this.cfg = cfg;

        this.requestMap = {};
    }

    _createClass(DataProvider, [{
        key: 'get',
        value: function get(options) {
            var _this = this;

            //temporary here, after refactoring that section could be removed and function params decomposition used
            if (!_.isObject(options)) {
                var args = Array.prototype.slice.call(arguments);

                options = {};
                options.url = args[0];
                options.params = args[1];
                options.headers = args[2];
            }

            var url = this.cfg.baseUrl + options.url;
            var params = options.params || {};
            var headers = options.headers || {};

            var p;

            var wholeUrl = _.isEmpty(params) ? url : url + '?' + $.param(params);

            var requestUrl = 'GET' + wholeUrl;

            if (this.requestMap[requestUrl]) return this.requestMap[requestUrl];

            if (headers) p = this.$http({ method: 'get', url: url, params: params, headers: headers });else p = this.$http.get(url, { params: params });

            p = p.then(function (response) {
                // Reject a promisse in order to be able to catch this as an exception later
                if (response.data.isError) return _this.$q.reject(response.data.message ? response.data.message : response.data.isError);

                return response.data;
            })['finally'](function () {
                delete _this.requestMap[requestUrl];
            });

            this.requestMap[requestUrl] = p;

            return p;
        }
    }, {
        key: 'post',
        value: function post(url, params, headers) {
            var _this2 = this;

            var wholeUrl = _.isEmpty(params) ? url : url + '?' + $.param(params);
            var requestUrl = 'POST ' + wholeUrl;
            if (this.requestMap[requestUrl]) return this.requestMap[requestUrl];
            var p = this.$http({
                method: 'POST',
                url: this.cfg.baseUrl + url,
                data: params,
                headers: headers
            }).then(function (response) {
                // Reject a promisse in order to be able to catch this as an exception later
                if (response.data.isError) return _this2.$q.reject(response.data.message ? response.data.message : response.data.isError);

                _this2.requestMap = _.without(_this2.requestMap, requestUrl);
                return response.data;
            })['finally'](function () {
                delete _this2.requestMap[requestUrl];
            });
            this.requestMap[requestUrl] = p;
            return p;
        }
    }]);

    return DataProvider;
})();

angularApp.service('DataProvider', DataProvider);

},{}],3:[function(require,module,exports){
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var SearchCtrl = (function () {
    function SearchCtrl(DataProvider, $window) {
        _classCallCheck(this, SearchCtrl);

        this.DataProvider = DataProvider;
        this.$window = $window;
        this.loading = false;

        this.data = [];
    }

    _createClass(SearchCtrl, [{
        key: 'onQueryChange',
        value: function onQueryChange() {
            var _this = this;

            if (!this.query) return;

            this.loading = true;
            this.results = {};
            this.DataProvider.get('search/all', { query: this.query }).then(function (results) {
                _this.data = results;
                _this.convertResultSet();
                _this.loading = false;
            });
        }
    }, {
        key: 'convertResultSet',
        value: function convertResultSet() {
            var _this2 = this;

            var results = angular.copy(this.data);
            this.results = {};

            results.forEach(function (result) {
                if (!_this2.results[result.type]) _this2.results[result.type] = [];
                _this2.results[result.type].push(result);
            });
        }
    }, {
        key: 'onKeyPress',
        value: function onKeyPress(key) {
            var active = this.data.findIndex(function (item) {
                return item.active;
            });
            switch (key) {
                case 38:
                    this.onKeyUp(active);
                    break;
                case 40:
                    this.onKeyDown(active);
                    break;
                case 13:
                    this.onEnter(active);
                    break;
            }
            this.convertResultSet();
        }
    }, {
        key: 'onKeyUp',
        value: function onKeyUp(active) {
            if (active > 0) {
                this.data[active].active = false;
                this.data[active - 1].active = true;
            }
        }
    }, {
        key: 'onKeyDown',
        value: function onKeyDown(active) {
            if (active < this.data.length - 1) {
                if (active >= 0) this.data[active].active = false;
                this.data[active + 1].active = true;
            }
        }
    }, {
        key: 'onEnter',
        value: function onEnter(active) {
            this.$window.location.href = this.data.find(function (item) {
                return item.active;
            }).link.edit;
        }
    }]);

    return SearchCtrl;
})();

angularApp.directive('appSearch', function (cfg) {
    return {
        restrict: 'E',
        templateUrl: cfg.baseUrl + 'html/components/search/search.html',
        controllerAs: 'search',
        controller: SearchCtrl,
        link: function link(scope, element, attributes) {
            element.bind("keydown keypress", function (event) {
                if ([13, 38, 40].indexOf(event.which) >= 0) {
                    scope.$apply(function () {
                        return scope.search.onKeyPress(event.which);
                    });
                    event.preventDefault();
                }
            });
        }
    };
});

},{}],4:[function(require,module,exports){
'use strict';

window.angularApp = angular.module('angularApp', []);

/**
 * Create a confirm modal
 * We want to send an HTTP DELETE request
 *
 * @usage  <a href="posts/2" data-method="delete" data-modal-text="Are you sure you want to delete">
 *
 */
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

},{}],5:[function(require,module,exports){
'use strict';

require('./_main.js');

require('../components/common/config.js');

require('../components/common/dataProvider.service.js');

require('../components/search/search.directive.js');

},{"../components/common/config.js":1,"../components/common/dataProvider.service.js":2,"../components/search/search.directive.js":3,"./_main.js":4}]},{},[5]);
