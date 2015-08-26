'use strict';

angular.module(
	'datingMobile',
	[
		'filters',
		'ngAnimate',
		'ngCookies',
		'ngResource',
		'ngRoute',
		'ngSanitize',
		'ngTouch',
		'view-segment',
		'route-segment',
		'ui.bootstrap',
		'jmdobry.angular-cache',
		'infinite-scroll',
		'bhResponsiveImages',
		'angularFileUpload',
		'monospaced.elastic',
		'angular-carousel',
		'com.2fdevs.videogular'
	]
	)
	.constant('apiHost', 'http://128.195.185.103/api/')
	.config(function($routeSegmentProvider, $routeProvider, $httpProvider, $locationProvider, $provide) {
		$routeSegmentProvider.options.autoLoadTemplates = true;
		$routeSegmentProvider
			.when('/friends', 'friends')

			.when('/gallery', 'gallery')
			.when('/gallery/edit', 'gallery_edit')
			.when('/gallery/edit/:itemId', 'gallery_edit')
			.when('/gallery/:userId', 'gallery')
			.when('/gallery/:userId/:itemId', 'gallery')

			.when('/im', 'im')
			.when('/im/:contactId', 'im_chat')

			.when('/login', 'login')
			.when('/confirm', 'confirm')

			.when('/main', 'main')

			.when('/profile', 'profile')
			.when('/profile/:userId', 'profile')
			.when('/profile/edit/:sectionId', 'profile_edit')

			.when('/register', 'register')
			.when('/register/step1', 'register.step1')
			.when('/register/step2', 'register.step2')
			.when('/register/step3', 'register.step3')

			.when('/search', 'search')
			.when('/search/results', 'search_results')
			.when('/search/:type', 'search')

			.when('/services', 'services.list')
			.when('/services/my', 'services.my')
			.when('/services/view_service/:serviceGid', 'services.view_service')
			.when('/services/view_package/:packageGid', 'services.view_package')

			.when('/settings', 'settings_list')
			.when('/settings/email', 'settings_email')
			.when('/settings/password', 'settings_password')
			.when('/settings/lang', 'settings_lang')

			.when('/start', 'start')
	
			.when('/redirect', 'redirect');
		$routeSegmentProvider
			.segment('friends', {
				controller: 'FriendsCtrl',
				templateUrl: 'views/friends/list.html'
			})
			.segment('im', {
				controller: 'ImCtrl',
				templateUrl: 'views/im/list.html'
			})
			.segment('im_chat', {
				controller: 'ImChatCtrl',
				templateUrl: 'views/im/chat.html',
				dependencies: ['contactId']
			})
			.segment('login', {
				controller: 'LoginCtrl',
				templateUrl: 'views/login/form.html'
			})			
			.segment('confirm', {
				controller: 'ConfirmCtrl',
				templateUrl: 'views/login/confirm.html'
			})
			.segment('main', {
				templateUrl: 'views/main.html',
				controller: 'MainCtrl'
			})
			.segment('profile', {
				controller: 'ProfileCtrl',
				templateUrl: 'views/profile/view.html',
				dependencies: ['userId']
			})
			.segment('profile_edit', {
				controller: 'ProfileEditCtrl',
				templateUrl: 'views/profile/edit.html',
				dependencies: ['sectionId']
			})
			.segment('gallery', {
				controller: 'GalleryCtrl',
				templateUrl: 'views/gallery/index.html',
				dependencies: ['userId']
			})
			.segment('gallery_edit', {
				controller: 'GalleryEditCtrl',
				templateUrl: 'views/gallery/edit.html',
				dependencies: ['itemId']
			})
			.segment('register', {
				controller: 'RegisterCtrl',
				templateUrl: 'views/register/index.html'
			})
			.within()
			.segment('step1', {
				controller: 'RegisterStep1Ctrl',
				templateUrl: 'views/register/step1.html'
			})
			.segment('step2', {
				controller: 'RegisterStep2Ctrl',
				templateUrl: 'views/register/step2.html'
			})
			.segment('step3', {
				controller: 'RegisterStep3Ctrl',
				templateUrl: 'views/register/step3.html'
			})
			.up()
			.segment('services', {
				controller: 'ServicesCtrl',
				templateUrl: 'views/services/index.html'
			})
			.within()
			.segment('list', {
				controller: 'ServicesListCtrl',
				templateUrl: 'views/services/list.html'
			})
			.segment('my', {
				controller: 'ServicesMyCtrl',
				templateUrl: 'views/services/my.html'
			})
			.segment('view_service', {
				controller: 'ServicesViewServiceCtrl',
				templateUrl: 'views/services/view-service.html'
			})
			.segment('view_package', {
				controller: 'ServicesViewPackageCtrl',
				templateUrl: 'views/services/view-package.html'
			})
			.up()
			.segment('settings_list', {
				controller: 'SettingsListCtrl',
				templateUrl: 'views/settings/list.html'
			})
			.segment('settings_email', {
				controller: 'SettingsEmailCtrl',
				templateUrl: 'views/settings/email.html'
			})
			.segment('settings_password', {
				controller: 'SettingsPasswordCtrl',
				templateUrl: 'views/settings/password.html'
			})
			.segment('settings_lang', {
				controller: 'SettingsLangCtrl',
				templateUrl: 'views/settings/lang.html'
			})
			.segment('search', {
				controller: 'SearchCtrl',
				templateUrl: 'views/search/form.html'
			})
			.segment('search_results', {
				controller: 'SearchResultsCtrl',
				templateUrl: 'views/search/list.html'
			})
			.segment('start', {
				controller: 'StartCtrl',
				templateUrl: 'views/start.html'
			})
			.segment('redirect', {
				controller: 'RedirectCtrl',
			});

		// Default route
		$routeProvider.otherwise({redirectTo: '/main'});

		$httpProvider.responseInterceptors.push(['$location', '$rootScope', '$q',
			function($location, $rootScope, $q) {
				function success(response) {
					if (response.debug) {
						console.log(response.debug);
					}
					return response;
				}
				function error(response) {
					if (response.debug) {
						console.log(response.debug);
					}
					if (404 === response.status && '/main' !== $location.path()) {
						$location.path('/main');
					}
					return $q.reject(response);
				}
				return function(promise) {
					return promise.then(success, error);
				};
			}]
			);

		/* настройки вида адреса - html5 + hash */
		$provide.decorator('$sniffer', function($delegate) {
			$delegate.history = false;
			return $delegate;
		});
		$locationProvider.html5Mode(true).hashPrefix('!');

		/* перехват событий загрузки — для индикатора загрузки */
		$httpProvider.interceptors.push('requestInterceptor');

		/* Преобразование запросов в формате json (стандартных для ангуляра) в формат form-urlencoded (понятного для PHP) */
		$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8';
		$httpProvider.defaults.transformRequest = [function(data) {
				var param = function(obj) {
					var query = '';
					var name, value, fullSubName, subName, subValue, innerObj, i;
					for (name in obj) {
						value = obj[name];
						if (value instanceof Array) {
							for (i = 0; i < value.length; ++i) {
								subValue = value[i];
								fullSubName = name + '[' + i + ']';
								innerObj = {};
								innerObj[fullSubName] = subValue;
								query += param(innerObj) + '&';
							}
						} else if (value instanceof Object) {
							for (subName in value) {
								subValue = value[subName];
								fullSubName = name + '[' + subName + ']';
								innerObj = {};
								innerObj[fullSubName] = subValue;
								query += param(innerObj) + '&';
							}
						} else if (value !== undefined && value !== null) {
							query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
						}
					}
					return query.length ? query.substr(0, query.length - 1) : query;
				};
				return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
			}];
	})
	.run(function(Init, $rootScope, Layout) {
		$rootScope.Layout = Layout;
		Init.setUp().then(function(init) {
			$rootScope.apd = init;
		});

	});