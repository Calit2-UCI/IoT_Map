'use strict';

angular.module('datingMobile').controller('RegisterCtrl', function($route, $rootScope, $scope, appSettings, Helpers) {
	if (!$rootScope.canRegister){
		$rootScope.go('start');
	}
	if ('register' === $route.current.segment
		|| ('register/step1' !== $route.current.segment && Helpers.isObjEmpty($scope.regData))) {
		$rootScope.go('register/step1');
	}
	$scope.age = appSettings.get('properties').age;
	$scope.userTypes = appSettings.get('properties').userTypes;
	$scope.regData = {
		user_type: Object.getOwnPropertyNames($scope.userTypes.option)[0]
	};

}).controller('RegisterStep1Ctrl', function($rootScope, $scope, Api, Layout, appHistory) {
	if ($rootScope.apd.isLogged) {
		$rootScope.go('main');
	}
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function() {
			appHistory.goBack('start');
		}
	};
	$rootScope.actions = {
		text: $rootScope.l('registration')
	};

	var today = new Date();
	$scope.date = {
		today: today,
		min: new Date(new Date(today).setYear(today.getYear() - $scope.$parent.age.max)),
		max: new Date(new Date(today).setDate(today.getDate() - ($scope.$parent.age.min * 365) - Math.round($scope.$parent.age.min/4)))
	};
	$scope.$parent.regData.birth_date = $scope.date.max;
	$scope.dateOptions = {
		'year-format': "'yyyy'",
		'starting-day': 1
	};
	//$scope.formats = ['dd.MM.yyyy', 'yyyy/MM/dd', 'shortDate'];
	$scope.dateFormat = 'dd.MM.yyyy';//$scope.formats[0];

	$scope.showTerms = function() {
		Api.query({module: 'content', method: 'get'}, {gid: 'legal-terms'}).then(function(resp) {
			Layout.showModal({
				text: resp.data.page_data.content,
				buttons: [{
					text: $rootScope.l('btn_close'),
					class: 'btn-primary'
				}]
			});
		});
	};

	$scope.submit = function() {
		$scope.$parent.regData.repassword = $scope.$parent.regData.password;
		if ('object' === typeof $scope.$parent.regData.birth_date) {
			$scope.$parent.regData.birth_date = $scope.$parent.regData.birth_date.toYMD();
		}
		if ($scope.registerForm.$invalid) {

		} else {
			Api.query({module: 'users', method: 'validate'}, $scope.$parent.regData).then(function(resp) {
				if (resp.messages) {
					Layout.addAlert('info', resp.messages);
				}
				$rootScope.go('register/step2');
			}, function(resp) {
				Layout.addAlert('danger', resp.errors);
			});
		}
	};

	$scope.checkForm = function() {
		return false;
	};

}).controller('RegisterStep2Ctrl', function($rootScope, $scope, Layout) {
	$rootScope.actions = {
		text: $rootScope.l('step') + ' ' + $rootScope.l('n_of_m').replace('[n]', 2).replace('[m]', 3)
	};

	Layout.addAlert('info', $rootScope.l('alert_reg_step2'));
	$scope.fileSelect = function(file) {
		$scope.$parent.regData.user_icon = file[0];
	};

	$scope.submit = function() {
		$rootScope.go('register/step3');
	};

}).controller('RegisterStep3Ctrl', function($rootScope, $scope, Api, Layout, appSettings, Init, iOS) {
	$rootScope.actions = {
		text: $rootScope.l('step') + ' ' + $rootScope.l('n_of_m').replace('[n]', 3).replace('[m]', 3)
	};
	$scope.submit = function() {
		$scope.$parent.regData.min_age += 0;
		var data = $scope.$parent.regData;
		Api.query({module: 'users', method: 'registration'}, data, 'user_icon', data.user_icon).then(function(resp) {
			Layout.addAlert('info', resp.messages, true);
			if($rootScope.device == 'iosDevice'){
				iOS.call('setSignUp', {}, function(){
					$rootScope.canRegister = false;
					$scope.login($scope.$parent.regData.email, $scope.$parent.regData.password);
				}, function(){
					$rootScope.canRegister = false;
					$scope.login($scope.$parent.regData.email, $scope.$parent.regData.password);
				});
			}else{
				$scope.login($scope.$parent.regData.email, $scope.$parent.regData.password);
			}
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
		});
	};

	$scope.login = function(email, password){
		Api.updateToken(email, password).then(function() {
			$rootScope.apd.isLogged = true;
			appSettings.save(true, 'isLogged');
			Init.setUp(true).then(function() {
				$rootScope.go('main');
			});
		}, function(err) {
			Layout.addAlert('danger', err);
			$rootScope.go('confirm');
		});
	};
});
