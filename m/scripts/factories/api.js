'use strict';

angular.module('datingMobile').factory('ApiRequest', ['$resource', 'apiHost', function($resource, apiHost) {
		return $resource(apiHost + ':module/:method/:uri', {}, {
			query: {method: 'POST'}
		});
	}])
		.factory('ApiUpload', ['$upload', 'apiHost', function($upload, apiHost) {
				return function(routeParams, postData, fileName, fileData) {
					return $upload.upload({
						url: apiHost + ':module/:method/:uri'
								.replace(':module', routeParams.module || '')
								.replace(':method', routeParams.method || '')
								.replace(':uri', routeParams.uri || ''),
						data: postData,
						file: fileData,
						fileFormDataName: fileName
					});
				};
			}])
		.factory('Api', ['$rootScope', 'ApiRequest', 'ApiUpload', '$q', 'appSettings', 'Layout', function($rootScope, ApiRequest, ApiUpload, $q, appSettings, Layout) {
				var _self = this,
					_tokenProccess = false,
					tokenDfd = $q.defer(),
					_token = appSettings.get('token');

				_self.setToken = function(newToken) {
					_token = newToken;
					appSettings.save(newToken, 'token');
					return newToken;
				};

				/**
				 * Request new token from the host
				 *
				 * @param {string} email
				 * @param {string} password
				 * @returns {$q@call;defer.promise}
				 */
				_self.updateToken = function(email, password) {
					var dfd = $q.defer();
					ApiRequest.query({module: 'get_token'}, {email: email, password: password}).$promise.then(function(resp) {
						if (resp.errors) {
							// Ошибка на уровне контроллера
							dfd.reject(resp.errors);
						} else if (resp.data.token) {
							// OK
							dfd.resolve(_self.setToken(resp.data.token));
						} else {
							// Ошибок нет. Токена тоже.
							dfd.reject('(api) requestToken() error (empty token)');
						}
					}, function(err) {
						// Ошибка на сервере
						dfd.reject('(api) requestToken() error (' + err + ')');
					});
					return dfd.promise;
				};

				/**
				 * Returns current token. Updates it when required.
				 *
				 * @param {bool} force
				 * @returns {$q@call;defer.promise}
				 */
				var getToken = function(force) {
					force = force || false;
					if (_tokenProccess) {
						return tokenDfd.promise;
					} else {
						tokenDfd = $q.defer();
					}
					if (_token && !force) {
						_tokenProccess = false;
						tokenDfd.resolve(_token);
					} else {
						_tokenProccess = true;
						_self.updateToken().then(function(newToken) {
							if (newToken) {
								tokenDfd.resolve(newToken);
							} else {
								tokenDfd.reject('(api) getToken() error (empty token)');
							}
							_tokenProccess = false;
						},
						function(err) {
							tokenDfd.reject(err);
							_tokenProccess = false;
						});
					}
					return tokenDfd.promise;
				};

				var _request = function(routerParams, postData) {
					postData = postData || {};
					var dfd = $q.defer();
					var attempt = 0;
					var req = function(forceTokenReq) {
						attempt++;
						getToken(forceTokenReq).then(function(token) {
							angular.extend(postData, {token: token});
							ApiRequest.query(routerParams, postData).$promise.then(function(resp) {
								if ((resp.errors && resp.errors.length) || resp.error && resp.error.length) {
									dfd.reject(resp);
								} else {
									dfd.resolve(resp);
								}
							}, function(resp) {
								if ((401 === resp.status) && attempt === 1) {
									// Токен не найден на сервере — запрашиваем новый (гостевой).
									req(true);
									return;
								} else if (403 === resp.status) {
									// Не хватает прав
									$rootScope.go('login');
								}
								dfd.reject(resp);
							});
						}, function(err) {
							console.log(err);
						});
					};
					req();
					return dfd.promise;
				};

				var _upload = function(routerParams, postData, fileName, fileData) {
					routerParams = routerParams || {};
					postData = postData || {};
					var dfd = $q.defer();
					getToken().then(function(token) {
						angular.extend(postData, {token: token});
						ApiUpload(routerParams, postData, fileName, fileData).progress(function(evt) {
							//if(progress) progress(parseInt(100.0 * evt.loaded / evt.total));
						}).then(function(resp, status, headers, config) {
							if (resp.data.errors && resp.data.errors.length) {
								dfd.reject(resp.data);
							} else {
								dfd.resolve(resp.data);
							}
						}, function(resp, status) {
							dfd.reject(resp.data);
						});
					});
					return dfd.promise;
				};

				_self.query = function(routerParams, postData, fileName, fileData) {
					var _deferred = $q.defer();
					var func = function(isUpload) {
						if (isUpload) {
							return _upload(routerParams, postData, fileName, fileData);
						} else {
							return _request(routerParams, postData);
						}
					};
					func(fileName && fileData).then(function(resp) {
						if(resp.system_messages) {
							if(resp.system_messages.errors) {
								Layout.addAlert('danger', resp.system_messages.errors);
							}
						}
						_deferred.resolve(resp);
					}, function(resp) {
						_deferred.reject(resp);
					});
					return _deferred.promise;
				};

				$rootScope.api = {
					indicator: {
						type: '',
						hide: false
					}
				};
				_self.indicator = {
					setSmall: function() {
						$rootScope.api.indicator.type = 'small';
					},
					setNormal: function() {
						$rootScope.api.indicator.type = '';
					},
					hide: function() {
						$rootScope.api.indicator.hide = true;
					},
					show: function() {
						$rootScope.api.indicator.hide = false;
					},
					reset: function() {
						$rootScope.api.indicator.type = '';
						$rootScope.api.indicator.hide = false;
					}
				};

				return _self;
			}])

		.factory('requestInterceptor', function($q, $rootScope) {
			$rootScope.pendingRequests = 0;
			return {
				request: function(config) {
					if (!config.url.endsWith('backend')) {
						$rootScope.pendingRequests++;
					}
					return config || $q.when(config);
				},
				requestError: function(rejection) {
					if (!rejection.url.endsWith('backend')) {
						$rootScope.pendingRequests--;
					}
					return $q.reject(rejection);
				},
				response: function(response) {
					if (!response.config.url.endsWith('backend')) {
						$rootScope.pendingRequests--;
					}
					return response || $q.when(response);
				},
				responseError: function(rejection) {
					if (!rejection.config.url.endsWith('backend')) {
						$rootScope.pendingRequests--;
					}
					return $q.reject(rejection);
				}
			};
		});