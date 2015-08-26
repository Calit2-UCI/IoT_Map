'use strict';

angular.module('datingMobile').factory('iOS', function($rootScope) {
	return {
		call: function(name, args, success, error){
			iOS_callbacks.push(function(status, data){
				if(status){
					if(!angular.isUndefined(success)) success(data);
				}else{
					if(!angular.isUndefined(error)) error(data);
				}
			});
			calliOSFunction(name, args, 'iOSSuccess', 'iOSError');
		}
	};
});

