'use strict';

angular.module('datingMobile').controller('SearchCtrl', function ($routeSegment, $rootScope, $scope, Api, appSettings, appHistory, Layout){
	var searchType = $routeSegment.$routeParams.type || 'form';

	if(searchType && 'new' === searchType) {
		// Новые пользователи
		var form = {
			online_status: null,
			id_country: null,
			id_region: null,
			id_city: null,
			order: 'date_created'
		};
		appSettings.save(form, 'searchForm');
		$rootScope.go('search/results', false);
		return true;
	}
	$rootScope.actions = {
		text: $rootScope.l('search')
	};

	$scope.userTypes = appSettings.get('properties').userTypes;
	$scope.userData = appSettings.get('userData');
	$scope.age = {
		min: 18,
		max: 125,
		proposedMin: 18,
		proposedMax: 35
	};
	$scope.itemsOnPage = 12;

	$scope.form = {
		order: 'default',
		order_direction: 'DESC',
		age_min: $scope.age.proposedMin,
		age_max: $scope.age.proposedMax,
		user_type: $scope.userData ? $scope.userData.looking_user_type : null,
		online_status: false,
		id_country: $scope.userData.id_country,
		id_region: $scope.userData.id_region,
		id_city: $scope.userData.id_city
	};
	angular.extend($scope.form, appSettings.get('searchForm'));
	$scope.form.items_on_page = $scope.itemsOnPage;
	$scope.form.page = 1;

	$scope.pageCount = 1;
	$scope.resultCount = 0;
	$scope.isLoading = false;

	$scope.userTypeClicked = function (id) {
		if(id === $scope.form.user_type) {
			$scope.form.user_type = null;
		} else {
			$scope.form.user_type = id;
		}
	};
	$scope.isOnlineClicked = function(value) {
		$scope.form.online_status = value;
	};

	$scope.errors = {};
	$scope.isSearchDisabled = function() {
		if(!$scope.form.age_min || ($scope.form.age_min < $scope.age.min)) {
			$scope.errors.minAge = true;
		} else {
			delete $scope.errors.minAge;
		}

		if(!$scope.form.age_max || ($scope.form.age_max > $scope.age.max)) {
			$scope.errors.maxAge = true;
		} else {
			delete $scope.errors.maxAge;
		}
		return false;
	};

	$scope.search = function() {
		$scope.form.online_status = $scope.form.online_status ? true : null;
		appSettings.save($scope.form, 'searchForm');
		$rootScope.go('search/results');
	};

}).controller('SearchResultsCtrl', function ($q, $rootScope, $scope, Api, appSettings, appHistory, Layout){

	$rootScope.actions = {
		text: $rootScope.l('search_results')
	};
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			$rootScope.actions.text = $rootScope.l('search');
			appHistory.goBack('search/form');
		}
	};

	var searchForm = appSettings.get('searchForm');
	$scope.users = [];
	$scope.mediaCount = {};
	var getUsers = function(data) {
		$scope.isLoading = true;
		var dfd = $q.defer();
		Api.query({module: 'users', method: 'search'}, data).then(function(respUsers){
			$scope.resultCount = respUsers.data.page_data.row_count;
			$scope.pageCount = respUsers.data.page_data.total_pages;
			getUsersMedia(respUsers.data.users).then(function(data) {
				$scope.isLoading = false;
				angular.extend($scope.mediaCount, data);
				dfd.resolve(respUsers.data);
			});
		});
		return dfd.promise;
	};

	var getUsersMedia = function(users) {
		var dfd = $q.defer();
		if(undefined === users) {
			dfd.resolve({});
		} else {
			Api.query({module: 'media', method: 'get_media_count'}, {user_ids: users.arrayOf('id')}).then(function(respMedia){
				dfd.resolve(respMedia.data);
			});
		}
		return dfd.promise;
	};

	getUsers(searchForm).then(function(data) {
		if(!data.users) {
			Layout.addAlert('info', $rootScope.l('users_no_results'), true);
			$rootScope.go('search', false);
			return false;
		};
		$scope.users = data.users;
	});

	var loading = false;
	$scope.loadPage = function() {
		if(loading) {
			return false;
		}
		loading = true;
		if(searchForm.page < $scope.pageCount) {
			searchForm.page = ++searchForm.page;
			Api.indicator.setSmall();
			getUsers(searchForm).then(function(data) {
				data.users.forEach(function(user) {
					$scope.users.push(user);
				});
				Api.indicator.setNormal();
			});
		}
		loading = false;
	};

});