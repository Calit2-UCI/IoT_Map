'use strict';

angular.module('datingMobile').service('backend', function($interval, Api, Helpers) {

	var self = this;
	var interval = 5000;
	var intervalId;
	var requests = {};
	var callbacks = {};

	self.add = function(obj) {
		requests[obj.name] = obj.data;
		callbacks[obj.name] = obj.callback;
		return self;
	};

	self.unset = function(name) {
		delete requests[name];
		return self;
	};

	self.reset = function() {
		self.stop();
		requests = {};
		callbacks = {};
		return self;
	};

	self.start = function() {
		if(angular.isDefined(intervalId)) {
			$interval.cancel(intervalId);
		}
		var request = function() {
			if(!Helpers.isObjEmpty(requests)) {
				Api.query({module: 'start', method: 'backend'}, {data: requests}).then(function(resp){
					if(undefined === resp.data) {
						console.error('backend start error');
						return false;
					}
					for(var name in requests) {
						if('function' === typeof callbacks[name]) {
							callbacks[name](resp.data[name]);
						}
					}
				});
			}
		};
		request();
		intervalId = $interval(function() {
			request();
		}, interval);
		return self;
	};

	self.stop = function() {
		if(angular.isDefined(intervalId)) {
			$interval.cancel(intervalId);
		}
		return self;
	};

});