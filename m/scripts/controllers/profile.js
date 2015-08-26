'use strict';

angular.module('datingMobile').controller('ProfileCtrl', function($rootScope, $routeSegment, $scope, $q, Api, appHistory, appSettings, Layout) {
	var userId = parseInt($routeSegment.$routeParams.userId) || parseInt(appSettings.get('userData').id) || false;
	if (!userId) {
		appHistory.goBack('search');
		return;
	}
	var ownProfile = userId === parseInt(appSettings.get('userData').id);
	$rootScope.actions = {};
	$rootScope.rightBtn = {
		icon: 'fa fa-search',
		href: 'search'
	};
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function() {
			appHistory.goBack('search');
		}
	};

	// Для обновления аватара
	$scope.fileSelect = function(file) {
		Api.query({module: 'users', method: 'save'}, {user_icon: file[0]}, 'user_icon', file[0]).then(function() {
			Api.query({module: 'users', method: 'get'}, {formatted: true}).then(function(userResp) {
				$scope.user = userResp.data;
			});
		});
	};

	var setLayout = function() {
		// Есть ли у пользователя картинки
		var checkUserMedia = function(userId) {
			var dfd = $q.defer();
			Api.query({module: 'media', method: 'get_media_count'}, {user_ids: [userId]}).then(function(resp) {
				if (resp.data[$scope.user.id]) {
					dfd.resolve(true);
				} else {
					dfd.resolve(false);
				}
			});
			return dfd.promise;
		};
		if (ownProfile) {
			checkUserMedia(userId).then(function(hasMedia) {
				var galleryAction;
				if (hasMedia) {
					galleryAction = function() {
						$rootScope.go('gallery');
					};
				} else {
					galleryAction = function() {
						Layout.addAlert('info', $rootScope.l('gallery_add_photo'), true);
						$rootScope.go('gallery/edit');
					};
				}
				$rootScope.actions.items = [{
						click: galleryAction,
						text: $rootScope.l('profile_my_photos')
					}];
			});
			$scope.bigBtns = [
				{
					icon: 'fa-comments',
					text: $rootScope.l('messages'),
					ngHide: 'imDisabled',
					click: function() {
						$rootScope.go('im', true);
					}
				},
				{
					icon: 'fa-users ',
					text: $rootScope.l('friends'),
					click: function() {
						$rootScope.go('friends');
					}
				}
			];
			$scope.canEdit = true;
		} else {
			$scope.canEdit = false;
			var checkFriendRequest = function() {
				// Проверяем запрос на добавление в друзья
				Api.query({module: 'friendlist', method: 'get_statuses'}, {id_dest_user: userId}).then(function(resp) {
					if (resp.data.allowed_btns.accept.allow) {
						// Ответ на запрос
						var friendResponse = function(action) {
							if (action) {
								Api.query({module: 'friendlist', method: action}, {id_dest_user: userId}).then(function(resp) {
									if (resp.messages) {
										Layout.addAlert('info', resp.messages);
									}
								}, function(resp) {
									Layout.addAlert('danger', resp.errors);
								});
							}
						};
						Layout.setTopMessage({
							close: function() {
								Layout.removeTopMessage();
							},						
							text: $scope.user.fname + ' ' + $rootScope.l('friends_request_sent'),
							buttons: [
								{
									text: $rootScope.l('friends_btn_accept'),
									class: 'btn-primary',
									action: function() {
										friendResponse('accept');
									}
								},
								{
									text: $rootScope.l('friends_btn_decline'),
									class: 'btn-default',
									action: function() {
										friendResponse('decline');
									}
								}
							]
						});
					}
				});
			};
			$scope.bigBtns = [{
					icon: 'fa-camera',
					text: $rootScope.l('photos'),
					click: function() {
						checkUserMedia(userId).then(function(hasMedia) {
							if (hasMedia) {
								$rootScope.go('gallery/' + userId);
							} else {
								Layout.addAlert('info', $rootScope.l('gallery_no_photos'));
							}
						});
					}
				}];
			if ('true' === appSettings.get('isLogged')) {
				checkFriendRequest();
				$scope.bigBtns.unshift({
					icon: 'fa-comments',
					text: $rootScope.l('messages'),
					ngHide: 'imDisabled',
					click: function() {
						$rootScope.go('im/' + userId, true);
					}
				});
			}
		}
	};

	Api.query({module: 'users', method: 'view'}, {id: userId, lang_id: appSettings.get('lang').id}).then(function(resp) {
		$scope.user = resp.data.user;
		$scope.sections = resp.data.sections;
		$rootScope.actions.text = resp.data.user.fname || resp.data.user.nickname;
		setLayout();
	});

}).controller('ProfileEditCtrl', function($rootScope, $scope, $routeSegment, $timeout, Api, appSettings, appHistory, Init, Helpers, Layout) {
	$rootScope.actions = {
		text: appSettings.get('userData').fname || appSettings.get('userData').nickname
	};
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function() {
			appHistory.goBack('profile');
		}
	};

	var userId = appSettings.get('userData').id;
	var fillFormCustomData = function(fieldsData) {
		for (var key in fieldsData) {
			$scope.formData[fieldsData[key].field_name] = fieldsData[key].value;
		}
		$scope.fieldsData = fieldsData;
	};

	var fillFormPersonalData = function(fieldsData) {
		var personalFields = [
			'age_max',
			'age_min',
			'birth_date',
			'fname',
			'id_city',
			'id_country',
			'id_region',
			'looking_user_type',
			'nickname',
			'sname',
			'user_type',
			'age_min',
			'age_max',
			'user_logo'
		];
		for (var i = 0; i < personalFields.length; i++) {
			$scope.formData[personalFields[i]] = fieldsData[personalFields[i]];
		}
		$scope.formData.age_min = parseInt($scope.formData.age_min);
		$scope.formData.age_max = parseInt($scope.formData.age_max);
		if (fieldsData.user_logo) {
			$scope.userLogo = fieldsData.media.user_logo.thumbs.middle;
		}
		$scope.location = {
			id_country: $scope.formData.id_country,
			id_region: $scope.formData.id_region,
			id_city: $scope.formData.id_city
		};
		return $scope.formData;
	};

	$scope.age = appSettings.get('properties').age;
	$scope.userTypes = appSettings.get('properties').userTypes;

	$scope.section = $routeSegment.$routeParams.sectionId;
	$scope.formData = {section: $scope.section};
	$scope.notEditable = [];

	$scope.fileSelect = function(file) {
		$scope.formData.user_icon = false;
		$timeout(function() {
			$scope.formData.user_icon = file[0];
		}, 10);
	};

	$scope.save = function() {
		if ('personal' === $scope.section) {
			$scope.formData.section = $scope.section;
			$scope.formData.id_country = $scope.location.id_country;
			$scope.formData.id_region = $scope.location.id_region;
			$scope.formData.id_city = $scope.location.id_city;
		}
		Api.query({module: 'users', method: 'save'}, $scope.formData, 'user_icon', $scope.formData.user_icon).then(function(resp) {
			if (!Helpers.isObjEmpty(resp.validate_data.errors)) {
				Layout.addAlert('danger', resp.validate_data.errors, true);
			} else {
				Init.initSettings();
				appHistory.goBack('profile');
			}
		}, function() {
		});
	};

	var data = {
		id: userId,
		section: $scope.section,
		lang_id: appSettings.get('lang').id
	};

	Api.query({module: 'users', method: 'profile'}, data).then(function(resp) {
		if ('personal' === $scope.section) {
			fillFormPersonalData(resp.data);
		} else {
			fillFormCustomData(resp.data.fields_data);
		}
		$scope.notEditable = resp.data.not_editable_fields;
	});

});