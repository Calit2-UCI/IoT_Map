'use strict';

angular.module('datingMobile').controller('StartCtrl', function ($rootScope, Layout, appSettings){
	// Главная страница до авторизации

	$rootScope.dependOnLang(function() {
		$rootScope.actions = {
			text: $rootScope.l('start')
		};
	});

	$rootScope.rightBtn = {
		icon: 'fa fa-search w',
		href: 'search'
	};

	if('true' === appSettings.get('isLogged')) {
		$rootScope.go('main');
	}

}).controller('RedirectCtrl', function ($rootScope, Layout) {
	if (null === $rootScope.apd.data) {
		var stopWatch = $rootScope.$watch('apd.data', function () {
			Layout.suggestMobApp();
			stopWatch();
		});
	} else {
		Layout.suggestMobApp();
	}
	$rootScope.go('start');
});
