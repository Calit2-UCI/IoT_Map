'use strict';

angular.module('datingMobile').controller('ServicesCtrl', function ($scope, Api, appSettings) {

	$scope.services = [];
	$scope.packages = [];
	$scope.myServices = [];
	$scope.myPackages = [];
	$scope.user = appSettings.get('userData');

	// В данных, приходящих при авторизации нет состояния счёта
	Api.query({module: 'users', method: 'get'}).then(function(resp){
		$scope.user = resp.data;
	});

}).controller('ServicesListCtrl', function ($rootScope, $scope, appSettings, Api) {
	// Список доступных для покупки сервисов и пакетов

	$rootScope.actions = {
		text: $rootScope.l('page_services')
	};
	Api.query({module: 'services', method: 'buy_list'}, {lang_id: appSettings.get('lang').id}).then(function(resp){
		$scope.$parent.services = resp.data;
	});
	Api.query({module: 'packages', method: 'index'}).then(function(resp){
		$scope.$parent.packages = resp.data;
	});

}).controller('ServicesMyCtrl', function ($rootScope, $scope, $filter, Api, Layout, appSettings) {
	// Купленные сервисы и пакеты

	$rootScope.actions = {
		text: $rootScope.l('page_account')
	};
	$scope.ngFilter = $filter;

	// Изменение внешнего вида сервиса на активированный
	var setActive = function(id) {
		// Простой перебор, пока не найдём нужный
		var walkServices = function(services) {
			for(var serviceId in services) {
				if(serviceId === id) {
					services[serviceId].is_active = false;
					return true;
				}
			}
		};
		if(walkServices($scope.$parent.myServices)) {
			return true;
		}
		for(var packageId in $scope.$parent.myPackages) {
			if(walkServices($scope.$parent.myPackages[packageId].user_services)) {
				return true;
			}
		}
	};

	var activate = function(id, gid) {
		var data = {
			id_user_service: id,
			gid: gid
		};
		Api.query({module: 'services', method: 'user_service_activate'}, data).then(function(resp){
			if(resp.messages) {
				Layout.addAlert('info', resp.messages);
			}
			setActive(id);
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
		});
	};

	$scope.confirmActivation = function(id, gid) {
		Layout.showModal({
			position: 'bottom',
			buttons: [
				{
					text: $rootScope.l('services_btn_activate'),
					class: 'btn-primary',
					action: function() {
						activate(id, gid);
					}
				},
				{
					text: $rootScope.l('btn_cancel'),
					class: 'btn-default',
					action: function() {
						$scope.removeIds = [];
					}
				}
			]
		});
	};

	Api.query({module: 'services', method: 'my'}, {lang_id: appSettings.get('lang').id}).then(function(resp){
		$scope.$parent.myServices = resp.data;
	});
	Api.query({module: 'packages', method: 'my'}).then(function(resp){
		$scope.$parent.myPackages = resp.data;
	});

}).controller('ServicesViewServiceCtrl', function ($rootScope, $scope, $routeSegment, Api, appHistory, Layout) {
	// Просмотр сервиса

	$scope.serviceGid = $routeSegment.$routeParams.serviceGid;
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('services');
		}
	};
	if(!$scope.serviceGid) {
		appHistory.goBack('services');
	} else if(0 !== $scope.$parent.services.length) {
		for (var service in $scope.$parent.services) {
			if($scope.$parent.services[service].gid === $scope.serviceGid) {
				$scope.service = $scope.$parent.services[service];
				break;
			}
		}
	} else {
		Api.query({module: 'services', method: 'get'}, {gid: $scope.serviceGid}).then(function(resp){
			$scope.service = resp.data;
		});
	}

	$rootScope.actions = {
		text: $rootScope.l('page_payment')
	};
	$scope.form = {
		activate_immediately: true,
		service_gid: $scope.serviceGid,
		payment_type: 'account'
	};

	$scope.writeOff = function() {
		Api.query({module: 'services', method: 'form'}, $scope.form).then(function(resp){
			$scope.$parent.user.account -= $scope.service.price;
			if(resp.messages) {
				Layout.addAlert('info', resp.messages);
			}
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
		});
	};

}).controller('ServicesViewPackageCtrl', function ($rootScope, $scope, $routeSegment, Api, appHistory, Layout) {
	// Просмотр пакета

	$scope.packageGid = $routeSegment.$routeParams.packageGid;
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('services');
		}
	};

	var getTotalPrice = function(packagesList) {
		var totalPrice = 0;
		for(var srvc in packagesList) {
			totalPrice += parseFloat(packagesList[srvc].price * packagesList[srvc].service_count);
		}
		return totalPrice;
	};

	if(!$scope.packageGid) {
		appHistory.goBack('services');
	} else if(0 !== $scope.$parent.packages.length) {
		for (var pkg in $scope.$parent.packages) {
			if($scope.$parent.packages[pkg].gid === $scope.packageGid) {
				$scope.package = $scope.$parent.packages[pkg];
				$scope.package.totalPrice = getTotalPrice($scope.$parent.packages[pkg].services_list);
				break;
			}
		}
	} else {
		Api.query({module: 'packages', method: 'get'}, {gid: $scope.packageGid}).then(function(resp){
			$scope.package = resp.data;
			$scope.package.totalPrice = getTotalPrice($scope.package.services_list);
		});
	}

	$scope.form = {
		package_gid: $scope.packageGid,
		payment_type: 'account'
	};
	$scope.writeOff = function() {
		Api.query({module: 'packages', method: 'package'}, $scope.form).then(function(resp){
			$scope.$parent.user.account -= $scope.package.price;
			if(resp.messages) {
				Layout.addAlert('info', resp.messages);
			}
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
		});
	};

});