'use strict';

class DataProvider {
    constructor($http, $q, cfg) {
        this.$http = $http;
        this.$q = $q;
        this.cfg = cfg;

        this.requestMap = {};
    }

    get(options) {
        //temporary here, after refactoring that section could be removed and function params decomposition used
        if (!_.isObject(options)) {
            let args = Array.prototype.slice.call(arguments);

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

        if (headers)
            p = this.$http({method: 'get', url: url, params: params, headers: headers});
        else
            p = this.$http.get(url, {params: params});

        p = p.then((response) => {
            // Reject a promisse in order to be able to catch this as an exception later
            if(response.data.isError) return this.$q.reject(response.data.message ? response.data.message : response.data.isError);

            return response.data;
        }).finally(() => {
            delete this.requestMap[requestUrl];
        });

        this.requestMap[requestUrl] = p;

        return p;
    }

    post(url, params, headers) {
        var wholeUrl = _.isEmpty(params) ? url : url + '?' + $.param(params);
        var requestUrl = 'POST ' + wholeUrl;
        if (this.requestMap[requestUrl]) return this.requestMap[requestUrl];
        var p = this.$http({
            method: 'POST',
            url: this.cfg.baseUrl + url,
            data: params,
            headers: headers
        }).then( (response) => {
            // Reject a promisse in order to be able to catch this as an exception later
            if(response.data.isError) return this.$q.reject(response.data.message ? response.data.message : response.data.isError);

            this.requestMap = _.without(this.requestMap, requestUrl);
            return response.data;
        }).finally( () => {
            delete this.requestMap[requestUrl];
        });
        this.requestMap[requestUrl] = p;
        return p;
    }
}

angularApp.service('DataProvider', DataProvider);
