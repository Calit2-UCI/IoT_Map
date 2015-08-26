'use strict';

angular.module('datingMobile')
	.factory('Helpers', function($rootScope, $window, $location, appHistory) {

		$rootScope.go = function(path, saveBackLink) {
			if ((undefined === saveBackLink) || saveBackLink) {
				appHistory.saveBackLink();
			}
			if('http' === path.substring(0, 4)) {
				$window.location = path;
			} else {
				$location.path(path);
			}
		};

		$rootScope.goToMainSite = function() {
			$rootScope.go($rootScope.apd.data.siteUrl + '?mobile_detect=denied');
		};

		$rootScope.getAppUrl = function() {
			if('undefined' === typeof $rootScope.apd.data.appUrls) {
				return '';
			} else if('iosBrowser' === $rootScope.device && $rootScope.apd.data.appUrls.ios_url.length) {
				return  $rootScope.apd.data.appUrls.ios_url;
			} else if('androidBrowser' === $rootScope.device && $rootScope.apd.data.appUrls.android_url.length) {
				return  $rootScope.apd.data.appUrls.android_url;
			} else {
				return '';
			}
		};

		$rootScope.keys = function(obj) {
			return obj ? Object.keys(obj) : [];
		};

		// <lang>
		$rootScope.dependOnLang = function(fn, runNow) {
			if(runNow !== false) {
				fn();
			}
			$rootScope.$on('langsUpdated', function() {
				fn();
			});
		};

		$rootScope.l = function(gid, replace) {
			if (null === $rootScope.apd.l || !$rootScope.apd.l[gid]) {
				return gid;
			} else if(!replace) {
				return $rootScope.apd.l[gid];
			} else {
				var lang = $rootScope.apd.l[gid];
				for(var key in replace) {
					lang = lang.replace('[' + key + ']', replace[key]);
				}
				return lang;
			}
		};
		// </lang>

		var prototypes = function() {
			Array.prototype.has = function(needle){
				return this.indexOf(needle) > -1 || this.indexOf(parseInt(needle)) > -1;
			};
			Array.prototype.unique = function(){
				var u = {}, a = [];
				for(var i = 0, l = this.length; i < l; ++i){
				   if(u.hasOwnProperty(this[i])) {
					  continue;
				   }
				   a.push(this[i]);
				   u[this[i]] = 1;
				}
				return a;
			 };
			Array.prototype.arrayOf = function(key) {
				var values = this.valueOf();
				var result = [];
				for(var i in values) {
					if('object' === typeof(values[i])) {
						result.push(values[i][key]);
					}
				}
				return result;
			};
			Date.prototype.toYMD = function() {
				var year, month, day;
				year = String(this.getFullYear());
				month = String(this.getMonth() + 1);
				if (1 === month.length) {
					month = "0" + month;
				}
				day = String(this.getDate());
				if (1 === day.length) {
					day = "0" + day;
				}
				return year + "-" + month + "-" + day;
			};
			Date.prototype.till = function(date, interval) {
				interval = interval || 'days';
				if(!['days', 'hours', 'minutes', 'seconds'].has(interval)) {
					console.error('Wrong interval');
					return 0;
				}
				var fract = 1;
				switch (interval) {
					case 'days':
						fract *= 24;
					case 'hours':
						fract *= 60;
					case 'minutes':
						fract *= 60;
					case 'seconds':
						fract *= 1000;
				}
				var diff = date.getTime() - this.getTime();
				return Math.round(diff / fract);
			};
			Number.prototype.formatMoney = function(c, d, t){
				var n = this,
					dash = ('-' === c || '\u2013' === c ? true : false),
					c = dash ? 0 : isNaN(c = Math.abs(c)) ? 2 : c,
					d = typeof(d) === 'undefined' ? ',' : d,
					t = t === undefined ? '.' : t,
					s = n < 0 ? '-' : '',
					i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + '',
					j = (j = i.length) > 3 ? j % 3 : 0;
				return s + (j ? i.substr(0, j) + t : '')
						+ i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t)
						+ (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '')
						+ (dash ? d + '\u2013' : '');
			};
			String.prototype.endsWith = function(suffix) {
				return this.indexOf(suffix, this.length - suffix.length) !== -1;
			};
		};
		prototypes();

		return {
			isObjEmpty: function(obj) {
				for (var i in obj) {
					if (obj.hasOwnProperty(i)) {
						return false;
					}
				}
				return true;
			}
		};
	});
