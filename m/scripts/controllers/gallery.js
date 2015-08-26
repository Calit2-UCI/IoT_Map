'use strict';

angular.module('datingMobile').controller('GalleryCtrl', function($rootScope, $routeSegment, $scope, $window, $timeout, $sce, Api, Data, appHistory, appSettings, Layout) {
	$rootScope.actions = {};

	// Если это собственная галерея, данные пользователя берутся из appSettings
	if (parseInt($routeSegment.$routeParams.userId)) {
		$scope.userId = $routeSegment.$routeParams.userId;
		Api.query({module: 'users', method: 'get'}, {id: $scope.userId}).then(function(resp) {
			$scope.user = resp.data;
		});
		$scope.canEdit = false;
	} else {
		$scope.user = appSettings.get('userData');
		$scope.userId = $scope.user.id;
		$scope.canEdit = true;
	}

	var updateHeader = function(pos) {
		$rootScope.actions.text = $rootScope.l('header_file') + ' '
				+ $rootScope.l('n_of_m').replace('[n]', pos).replace('[m]', $scope.media.length);
	};

	if('undefined' !== typeof $routeSegment.$routeParams.itemId) {
		$scope.itemNum = parseInt($routeSegment.$routeParams.itemId);
	} else {
		var cookieSlide = appSettings.get('gallerySlide');
		if(cookieSlide){
			$scope.itemNum = cookieSlide;
		} else {
			$scope.itemNum = 1;
		}
	}
	$scope.setList = function() {
		$rootScope.actions = {
			items: $scope.canEdit ? [{
					text: $rootScope.l('gallery_btn_add'),
					click: function() {
						var frmImg = $window.document.getElementById('frm-img');
						$timeout(function() {
							frmImg.click();
						});
					}
				}] : null,
			text: $rootScope.l('page_gallery')
		};
		Layout.enableSideMenu();
		$scope.carousel = false;
		$rootScope.leftBtn = {
			class: 'fa fa-arrow-left',
			click: function() {
				appHistory.goBack('profile/' + ($scope.userId || ''));
			}
		};
		$rootScope.rightBtn = {
			class: 'fa fa-play',
			click: function() {
				$scope.setSlider();
			}
		};
	};

	// <Report>
	var reportWindow = function() {
		if(!$rootScope.apd.isLogged) {
			$rootScope.go('login');
			return false;
		}
		Layout.showModal({
			scope: $scope,
			position: 'center',
			include: 'views/gallery/report.html',
			buttons: [{
				text: $rootScope.l('btn_send'),
				class: 'btn-primary',
				action: function() {
					$scope.report(
							$scope.media[$scope.slideIndex].id,
							$scope.reportReason,
							$scope.reportComment
					);
					$scope.reportReason = Object.keys($scope.reportReasons.option)[0];
					$scope.reportComment = '';
				}
			},
			{
				text: $rootScope.l('btn_close'),
				class: 'btn-primary'
			}]
		});
	};
	var getReportReasons = function() {
		if($rootScope.apd.isLogged) {
			Api.indicator.setSmall();
			Api.query({module: 'spam', method: 'get_reasons'}).then(function(resp) {
				Api.indicator.setNormal();
				$scope.reportReasons = resp.reasons;
				$scope.reportReason = Object.keys($scope.reportReasons.option)[0];
			});
		}
		return [];
	};
	$scope.reportReasons = getReportReasons();
	$scope.reportReason = null;
	$scope.reportComment = '';
	$scope.report = function(objectId, reasonId, comment) {
		var post = {
			'type_gid': 'media_object',
			'object_id': objectId,
			'data[id_reason]': reasonId,
			'data[message]': comment
		};
		Api.query({module: 'spam', method: 'mark_as_spam'}, post).then(function(resp) {
			Layout.addAlert('info', resp.messages);
		}, function(resp) {
			Layout.addAlert('warning', resp.errors);
		});
	};
	// </Report>

	$scope.fileSelect = function(file) {
		Data.gallery = {
			multiUpload: file[0]
		};
		$rootScope.go('gallery/edit/');
	};
		
	$scope.setSlider = function(index) {
		Layout.disableSideMenu();
		index = index || 0;
		if ($scope.media && index < $scope.media.length) {
			$scope.slideIndex = index;
			updateHeader(index + 1);
		}

		$scope.carousel = true;
		$rootScope.rightBtn = {
			class: 'fa fa-flag-o',
			click: reportWindow
		};
		$rootScope.leftBtn = {
			text: $rootScope.l('btn_close'),
			click: function() {
				$scope.setList();
			}
		};
		

		if ($scope.canEdit) {
			var setActions = function() {
				var deleteMedia = function() {
					var mediaId = $scope.media[$scope.slideIndex].id;
					Api.query({module: 'media', method: 'delete_media'}, {id: mediaId}).then(function(resp) {
						Layout.addAlert('info', resp.messages);
						$scope.media.splice($scope.slideIndex, 1);
						if (0 === $scope.media.length) {
							$rootScope.go('gallery/edit', false);
							return;
						} else {
							updateHeader($scope.slideIndex + 1);
						}
						// Переключаемся на предыдущую фотографию
						if ($scope.slideIndex > 0) {
							$scope.slideIndex--;
						}
					}, function(resp) {
						Layout.addAlert('danger', resp.errors);
					});
				};
				$rootScope.actions.items = [{
					text: $rootScope.l('gallery_btn_add'),
					click: function() {
						var frmImg = $window.document.getElementById('frm-img');
						$timeout(function() {
							frmImg.click();
						});
					}
				}];

				if ($scope.media.length > 0) {
					$rootScope.actions.items = $rootScope.actions.items.concat([{
							text: $rootScope.l('gallery_btn_edit'),
							click: function() {
								$rootScope.go('gallery/edit/' + $scope.media[$scope.slideIndex].id);
							}
						}, {
							text: $rootScope.l('gallery_btn_delete'),
							click: function() {
								Layout.confimDelete(deleteMedia);
							}
						}]);
				}
			};
			setActions();
		}
	};

	var updateLikeOnPage = function(likeId, action, newCount) {
		for (var i in $scope.media) {
			if($scope.media[i].likes && $scope.media[i].likes.id === likeId) {
				$scope.media[i].likes.has_mine = action;
				$scope.media[i].likes.count = newCount;
				break;
			}
		}
	};

	$scope.canLike = function() {
		if(!$rootScope.apd.isLogged) {
			$rootScope.go('login');
			return false;
		} else if(!$rootScope.apd.modules.likes) {
			return false;
		} else if($scope.canEdit) {
			return false;
		}
		return true;
	};

	$scope.like = function(likeId, action) {
		if(!$scope.canLike()) {
			return false;
		}
		$scope.showHeart = true;
		var like = {
			'like_id': likeId,
			'action': action ? 'like' : 'unlike'
		};
		Api.indicator.setSmall();
		Api.query({module: 'likes', method: 'like'}, like).then(function(resp) {
			Api.indicator.setNormal();
			if(resp.data) {
				updateLikeOnPage(likeId, action, parseInt(resp.data.count));
			}
		});
	};
	$scope.comment = function(itemId) {
		
	};
	$scope.share = function(itemId) {
		
	};
	$scope.slideIndex = 0;
	$scope.$watch('slideIndex', function(newVal, oldVal) {
		if (newVal === oldVal) {
			return false;
		}
		// Pause all videos on scroll
		for(var i in $scope.videoPlayers) {
			if('function' === typeof $scope.videoPlayers[i].pause) {
				$scope.videoPlayers[i].pause();
			}
		}
		if ($scope.slideIndex > 0) {
			Layout.disableSideMenu();
		} else {
			Layout.enableSideMenu();
		}
		updateHeader($scope.slideIndex + 1);
		appSettings.save($scope.slideIndex + 1, 'gallerySlide');
	});

	$scope.carousel = true;
	$scope.media = [];
	$scope.photos = [];
	$scope.videos = [];
	$scope.videogularSources = {};

	var data = {
		user_id: $scope.userId,
		param: 'all',
		page: 1,
		album_id: 0,
		direction: 'desc',
		gallery_name: 'mediagallery',
		order: 'date_add',
		place: 'user_gallery',
		get_likes: true
	};

	$scope.videoPlayers = [];
	var allowedMimes = [
		'video/webm',
		'video/ogg',
		'video/mp4'
	];

	var onFullScreen = function(API) {
		var fullScreenClass = 'full-screen';
		if(API.isFullScreen) {
			API.mediaElement.addClass(fullScreenClass);
		} else {
			API.mediaElement.removeClass(fullScreenClass);
		}
	};

	$scope.onPlayerReady = function(API) {
		$scope.videoPlayers.push(API);
		var name = 'videoPlayers[' + ($scope.videoPlayers.length - 1) + '].isFullScreen';
		$scope.$watch(name, function(newVal, oldVal) {
			if (newVal === oldVal) {
				return false;
			}
			onFullScreen(API);
		});
	};
	$scope.onPlayerError = function() {};
	var videoIsValid = function(video) {
		return allowedMimes.has(video.mime);
	};
	Api.query({module: 'media', method: 'get_list'}, data).then(function(resp) {
		for(var i in resp.data.media) {
			if(resp.data.media[i].upload_gid === 'gallery_image') {
				$scope.photos.push(resp.data.media[i]);
			} else if(resp.data.media[i].upload_gid === 'gallery_video' && videoIsValid(resp.data.media[i])) {
				$scope.videos.push(resp.data.media[i]);
				$scope.videogularSources[resp.data.media[i].id] = [{
					src: $sce.trustAsResourceUrl(resp.data.media[i].video_content.file_url), 
					type: resp.data.media[i].mime
				}];
			} else {
				continue;
			}
			$scope.media.push(resp.data.media[i]);
		}
		if ($scope.media.length) {
			$timeout(function() {
				$scope.setList();
			});
		} else {
			if (!$scope.canEdit) {
				Layout.addAlert('info', $rootScope.l('gallery_no_photos'));
				appHistory.goBack('profile/' + ($scope.userId || ''));
			} else {
				Layout.addAlert('info', $rootScope.l('gallery_add_photo'), true);
				$rootScope.go('gallery/edit', false);
			}
		}
	});
}).controller('GalleryEditCtrl', function($rootScope, $scope, $routeSegment, $q, Api, Data, appHistory, Layout) {

	$scope.mediaId = parseInt($routeSegment.$routeParams.itemId);
	$scope.isSaveDisabled = false;
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function() {
			appHistory.goBack('profile');
		}
	};
	$scope.media_type = '';
	$scope.form = {
		description: '',
		permissions: {}
	};

	$scope.fileSelect = function(file) {
		$scope.form.multiUpload = file[0];
	};

	var getItem = function() {
		var dfd = $q.defer();
		var data = {
			media_id: $scope.mediaId,
			without_position: true
		};
		Api.query({module: 'media', method: 'get_media_content'}, data).then(function(resp) {
			$scope.form.permissions = resp.data.media.permissions;
			$scope.form.description = resp.data.media.description;
			$scope.media_type = resp.data.media_type;
			if ('gallery_image' === resp.data.media_type) {
				$scope.preview = resp.data.media.media.mediafile.thumbs.middle;
			} else if('gallery_video' === resp.data.media_type 
					&& 'undefined' !== typeof resp.data.media.video_content.thumbs) {
				$scope.preview = resp.data.media.video_content.thumbs.middle;
			}
			dfd.resolve();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			dfd.reject();
		});
		return dfd.promise;
	};

	if ($scope.mediaId) {
		// Передан айдишник — редактирование
		$rootScope.actions = {
			text: $rootScope.l('page_gallery_edit_gallery_image')
		};
		getItem($scope.mediaId).then(function() {
			$rootScope.actions.text = $rootScope.l('page_gallery_edit_' + $scope.media_type);
		});
	} else {
		$scope.media_type = 'gallery_image';
		// Не передан — добавление.
		$rootScope.actions = {
			text: $rootScope.l('page_gallery_add_gallery_image')
		};
		// Инпут с выбором картинки может быть на другой странице
		if (Data.gallery) {
			$scope.form.multiUpload = Data.gallery.multiUpload;
		}
	}

	var upload = function() {
		var dfd = $q.defer();
		var data = {
			multiUpload: $scope.form.multiUpload,
			id_album: '',
			permissions: $scope.form.permissions,
			description: $scope.form.description
		};
		Api.query({module: 'media', method: 'save_image'}, data, 'multiUpload', data.multiUpload).then(function(resp) {
			if(Data.gallery) {
				delete Data.gallery;
			}
			$rootScope.go('gallery', false);
			dfd.resolve();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			$scope.isSaveDisabled = false;
			dfd.reject();
		});
		return dfd.promise;
	};

	var saveDescription = function() {
		var dfd = $q.defer();
		var data = {
			id: $scope.mediaId,
			description: $scope.form.description
		};
		Api.query({module: 'media', method: 'save_description'}, data).then(function(resp) {
			dfd.resolve();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			$scope.isSaveDisabled = true;
			dfd.reject();
		});
		return dfd.promise;
	};

	var savePermissions = function() {
		var data = {
			photo_id: $scope.mediaId,
			permissions: $scope.form.permissions
		};
		Api.query({module: 'media', method: 'save_permissions'}, data).then(function() {
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			$scope.isSaveDisabled = true;
		});
	};

	var getPermissionsList = function() {
		Api.query({module: 'media', method: 'get_permissions_list'}).then(function(resp) {
			$scope.accesses = resp.data.option;
		});
	};
	getPermissionsList();

	$scope.submit = function() {
		$scope.isSaveDisabled = true;
		if ($scope.mediaId) {
			// Редактирование существующей
			// TODO:
			// 1) Проверять, менялось ли что-то на странице дабы не отправлять лишних запросов
			// 2) Сохранять одним запросом
			saveDescription();
			savePermissions();
			appHistory.goBack('gallery');
		} else {
			// Загрузка новой
			upload();
		}
	};

});