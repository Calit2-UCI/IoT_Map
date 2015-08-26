'use strict';

angular.module('filters', []).filter('checkempty', function() {
	return function(input, text) {
		if(text === undefined) {
			text = '-';
		} else {
			return input ? input : text;
		}
	};
}).filter('truncate', function () {
	return function (text, length, end) {

		if (text === undefined) {
			return '';
		}
		if (end === undefined) {
			end = '...';
		}
		if (isNaN(length)) {
			length = 10;
		}

		if (text.length <= length || text.length - end.length < length) {
			return text;
		} else {
			return String(text).substring(0, length-end.length) + end;
		}
	};
}).filter('escape', function () {
	return function (value) {
		if (value === undefined) {
			return '';
		} else {
			return escape(value);
		}
	};
}).filter('int', function () {
	return function (value) {
		return parseInt(value);
	};
}).filter('cur', ['appSettings', function(appSettings) {
	return function (value) {
		return appSettings.get('properties').currency.abbr + value;
	};
}]).filter('curf', ['appSettings', function(appSettings) {
	return function (value) {
		return '<span>' + appSettings.get('properties').currency.abbr + '</span>' + value;
	};
}]).filter('pgDate', function($filter) {
	var stdDateFilter = $filter('date');
	return function(dateToFormat, format) {
		if('daysLeft' === format) {
			var now = new Date();
			var date = new Date(dateToFormat * 1000);
			return now.till(date);
		} else {
			return stdDateFilter(dateToFormat, format);
		}
	};
});