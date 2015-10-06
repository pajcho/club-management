class SearchCtrl {
    constructor(DataProvider, $window) {
        this.DataProvider = DataProvider;
        this.$window = $window;
        this.loading = false;

        this.data = [];
    }

    onQueryChange() {
        if(!this.query) return;

        this.loading = true;
        this.results = {};
        this.DataProvider.get('search/all', {query: this.query}).then(results => {
            this.data = results;
            this.convertResultSet();
            this.loading = false;
        });
    }

    convertResultSet() {
        let results = angular.copy(this.data);
        this.results = {};

        results.forEach(result => {
            if(!this.results[result.type]) this.results[result.type] = [];
            this.results[result.type].push(result);
        });
    }

    onKeyPress(key) {
        let active = this.data.findIndex((item) => item.active);
        switch(key){
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

    onKeyUp(active) {
        if(active > 0) {
            this.data[active].active = false;
            this.data[active - 1].active = true;
        }
    }

    onKeyDown(active) {
        if(active < this.data.length - 1) {
            if(active >= 0) this.data[active].active = false;
            this.data[active + 1].active = true;
        }
    }

    onEnter(active) {
        this.$window.location.href = this.data.find((item) => item.active).link.edit;
    }
}

angularApp.directive('appSearch', (cfg) => {
    return {
        restrict: 'E',
        templateUrl: cfg.baseUrl + 'html/components/search/search.html',
        controllerAs: 'search',
        controller: SearchCtrl,
        link: (scope, element, attributes) => {
            element.bind("keydown keypress", function (event) {
                if([13, 38, 40].indexOf(event.which) >= 0) {
                    scope.$apply(() => scope.search.onKeyPress(event.which));
                    event.preventDefault();
                }
            });
        }
    }
});
