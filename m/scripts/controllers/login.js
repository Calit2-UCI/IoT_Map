'use strict';

angular.module('datingMobile').controller('LoginCtrl', function ($rootScope, $scope, $location, $window, Init, Layout, appSettings, Api, appHistory) {

	$rootScope.dependOnLang(function() {
		$rootScope.actions = {
			text: $rootScope.l('login')
		};
	});
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('start');
		}
	};

	$scope.form = {
		email: '',
		password: ''
	};

	$scope.restoreForm = {
		email: ''
	};

	$scope.showRestoreForm = false;

	$scope.toggleRestoreForm = function() {
		$scope.showRestoreForm = !$scope.showRestoreForm;
		if($scope.showRestoreForm) {
			$rootScope.leftBtn.click = function() {
				$scope.toggleRestoreForm();
			};
		} else {
			$rootScope.leftBtn.click = function(){
				appHistory.goBack('start');
			};
		}
	};

	$scope.isSaveDisabled = function() {
		return $scope.form.$invalid;
	};

	$scope.restore = function() {
		Api.query({module: 'users', method: 'restore'}, $scope.restoreForm).then(function(resp){
			Layout.addAlert('info', resp.messages, true);
			$scope.toggleRestoreForm();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors, true);
		});
	};

	$scope.oauthLogin = function (id, gid, link) {
		if ($scope.lock) {
			return;
		}
		$scope.lock = true;
		try {
			if (injectedObject) {
				$location.path('/account/oauth_phone/' + id + '/' + gid);
			}
		} catch (e) {
			$window.location.href = link + '/' + '?redirect=' +
					$location.absUrl().replace('/#!/account/login', '');
		}
	};

	$scope.login = function(){
		Api.updateToken($scope.form.email, $scope.form.password).then(function(resp){
			Layout.removeAlerts(true);
			Init.setUp(true).then(function() {
				appHistory.goBack('main');
			}, function(resp) {
				console.log(resp);
			});
		}, function(err){
			Layout.addAlert('danger', err, true);
		});
	};

	$scope.logOff = function(){
		Api.query({module: 'users', method: 'logout'}).then(function(resp){
			Api.setToken(resp.data.token);
			Layout.removeAlerts(true);
			appSettings.save(false, 'userData');
			Init.setUp(true).then(function() {
				appHistory.goBack('start');
			});
		}, function(resp) {
			Layout.addAlert('danger', resp.errors, true);
		});
	};

	$scope.social_apps = [];
	Api.query({module: 'mobile', method: 'social_login'}).then(function(resp){
		if('undefined' === typeof resp.data.applications) {
			return;
		}
		$scope.social_apps = resp.data.applications;
		console.log($scope.social_apps);
	}, function(errors){

	});

}).controller('ConfirmCtrl', function ($rootScope, $scope, Init, Layout, appSettings, Api, appHistory, $location) {
	
	$rootScope.dependOnLang(function() {
		$rootScope.actions = {
			text: $rootScope.l('confirm')
		};
	});
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('login');
		}
	};
	
	$scope.form = {
		confirmation_code: ''
	};

	$scope.confirm = function() {
		Api.query({module: 'users', method: 'confirm', uri: $scope.form.confirmation_code}).then(function(resp){
			Layout.addAlert('info', resp.messages, true);
			
			Api.setToken(resp.data.token);
			
			Init.setUp(true).then(function() {
				$location.path('profile');
			}, function(resp) {
			});

			$location.path('profile');
		}, function(resp) {
			Layout.addAlert('danger', resp.errors, true);
			$location.path('main');
		});
	};

});