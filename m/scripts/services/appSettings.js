'use strict';

angular.module('datingMobile').service('appSettings', ['$rootScope', '$cookieStore', function Settings($rootScope, $cookieStore) {
	this.isLS = ('localStorage' in window && window['localStorage'] !== null);

	var self = this;
	var forceCookiesKeys = ['lang', 'gallerySlide'];
	self.get = function(key){
		key = key || 'settings';
		var value;
		if (self.isLS && !forceCookiesKeys.has(key)) {
			value = localStorage[key];
		}else{
			value = $cookieStore.get(key);
		}
		if(!value || typeof(value) !== 'string') {
			return null;
		}
		if(value.substring(0, 1) === '='){
			return value.substring(1);
		}else{
			if(key === 'l'){
				value = LZString.decompress(value);
			}
			if(value === ''){
				delete localStorage[key];
				return '';
			}			
			return JSON.parse(value);
		}
	};

	self.save = function(value, key){
		key = key || 'settings';
		var settings;
		if(typeof value === 'object'){
			settings = JSON.stringify(value);
			if(key === 'l'){
				settings = LZString.compress(settings);
			}
		}else if(typeof value !== 'undefined'){
			settings = '=' + value.toString();
		}
		if(typeof settings !== 'undefined'){
			if (self.isLS && !forceCookiesKeys.has(key)) {
				localStorage[key] = settings;
			}else{
				$cookieStore.put(key, settings);
			}
		}
	};
}]);