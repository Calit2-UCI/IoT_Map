'use strict';

angular.module('datingMobile').controller('SettingsListCtrl', function ($rootScope) {

	$rootScope.leftBtn = false;
	$rootScope.actions = {
		text: $rootScope.l('page_settings')
	};

	// TODO: Левая кнопка почему-то "назад"
}).controller('SettingsEmailCtrl', function ($rootScope, $scope, Api, appHistory, Layout) {

	$rootScope.actions = {
		text: $rootScope.l('page_settings_change_email')
	};

	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('settings');
		}
	};

	Api.query({module: 'users', method: 'settings'}).then(function(resp){
		$scope.email = resp.data.user.email;
	});

	$scope.submit = function() {
		var data = {
			contact_save: true,
			email: $scope.email
		};
		Api.query({module: 'users', method: 'settings'}, data).then(function(resp){
			Layout.addAlert('info', resp.messages);
			appHistory.goBack('settings');
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
		});
	};

}).controller('SettingsPasswordCtrl', function ($rootScope, $scope, Api, appHistory, Layout) {
	$rootScope.actions = {
		text: $rootScope.l('page_settings_change_password')
	};
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('settings');
		}
	};

	$scope.submit = function() {
		var data = {
			password_save: true,
			password: $scope.password,
			repassword: $scope.repassword
		};
		Api.query({module: 'users', method: 'settings'}, data).then(function(resp){
			Layout.addAlert('info', resp.messages);
			appHistory.goBack('settings');
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
		});
	};
}).controller('SettingsLangCtrl', function ($rootScope, $scope, appSettings, appHistory, Api, Layout, Init) {
	// TODO: Загружать список языков

	$rootScope.dependOnLang(function() {
		$rootScope.actions = {
			text: $rootScope.l('page_settings_change_lang')
		};
	});
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('settings');
		}
	};

	$scope.langs = appSettings.get('langs');
	$rootScope.apd.lang = appSettings.get('lang');
	$scope.setLang = function(id) {
		Api.indicator.setSmall();
		Api.query({module: 'mobile', method: 'change_lang'}, {lang_id: id}).then(function(resp){
			appSettings.save(resp.data.language, 'lang');
			$rootScope.apd.lang = resp.data.language;
			$rootScope.apd.l = resp.data.l;

			var properties = appSettings.get('properties');
			properties.userTypes = resp.data.properties.userTypes;
			appSettings.save(properties, 'properties');

			Init.initMenu();
			Api.indicator.setNormal();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			Api.indicator.setNormal();
		});
	};

});