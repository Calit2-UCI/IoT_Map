'use strict';

angular.module('datingMobile').controller('MainCtrl', function ($rootScope, $scope, appSettings, Api){
	// Главная страница после авторизации

	if(!appSettings.get('isLogged') || 'false' === appSettings.get('isLogged')) {
		$rootScope.go('start');
		return;
	} else {
		$rootScope.dependOnLang(function() {
			$rootScope.actions = {
				text: $rootScope.l('page_home'),
				href: '/'
			};
		});
	};
	$scope.users = [];

	var newUsersCount = 3;
	var isLoading = false;
	var updNewUsers = function(cunt, userType) {
		if(isLoading) {
			return false;
		}
		isLoading = true;
		Api.query({module: 'users', method: 'get_new'}, {count: cunt, user_type: userType}).then(function(respUsers){
			if('object' === typeof(respUsers.data)) {
				$scope.users = respUsers.data;
				isLoading = false;
				Api.query({module: 'media', method: 'get_media_count'}, {user_ids: $scope.users.arrayOf('id')}).then(function(respMedia){
					$scope.mediaCount = respMedia.data;
				});
			}
		});
	};
	updNewUsers(newUsersCount, appSettings.get('userData').looking_user_type);

});