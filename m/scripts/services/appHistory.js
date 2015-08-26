'use strict';

angular.module('datingMobile').service('appHistory', function($rootScope, $location) {
	var _historyLength = 5;
	var _history = [];
	var _backLink = [];
	var _self = this;

	_self.saveBackLink = function(url) {
		url = url || $location.$$path;
		if(0 === _backLink.length || url !== _backLink[0]) {
			_backLink.unshift(url);
		}
	};

	_self.shiftBackLink = function() {
		if (_backLink.length) {
			return _backLink.shift();
		} else {
			return false;
		}
	};

	_self.goBack = function(url) {
		$rootScope.go(_self.shiftBackLink() || url || _history[1] || '/main', false);
	};

	var saveHistory = function(path) {
		if (_history.length > _historyLength) {
			_history.splice(_history.length - 1);
		}
		_history.unshift(path);
	};

	_self.init = function() {
		$rootScope.$on('$routeChangeStart', function(/*next, current*/) {
			saveHistory($location.$$path);
		});
	};

});