'use strict';

angular.module('datingMobile')
	/**
	 * The ng-thumb directive
	 * @author: nerv
	 * @version: 0.1.2, 2014-01-09
	 * @param {type} $window
	 * @returns {_L13.Anonym$1}
	 */
	.directive('ngThumb', ['$window', function($window) {
			var helper = {
				support: !!($window.FileReader && $window.CanvasRenderingContext2D),
				isFile: function(item) {
					return angular.isObject(item) && item instanceof $window.File;
				},
				isImage: function(file) {
					var type = '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
					// TODO: подгружать форматы с сервера
					return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
				}
			};
			return {
				restrict: 'A',
				template: '<canvas/>',
				link: function(scope, element, attributes) {
					if (!helper.support) {
						return;
					}
					var params = scope.$eval(attributes.ngThumb);
					if (!helper.isFile(params.file)) {
						return;
					} else if (!helper.isImage(params.file)) {
						return;
					}

					var canvas = element.find('canvas');
					var reader = new FileReader();

					reader.onload = onLoadFile;
					reader.readAsDataURL(params.file);

					function onLoadFile(event) {
						var img = new Image();
						img.onload = onLoadImage;
						img.src = event.target.result;
						return img;
					}

					function onLoadImage() {
						var width = params.width || this.width / this.height * params.height;
						var height = params.height || this.height / this.width * params.width;
						canvas.attr({width: width, height: height});
						canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
					}
				}
			};
		}])
	.directive('pgLocation', function($rootScope, Api, $q, $timeout, Helpers) {
		return {
			link: function(scope/*, element, attrs*/) {
				scope.autocomplete = {
					region: '',
					city: ''
				};

				// Заполнение данными пользователя
				var listener = scope.$watch('pgLocation', function(newValue, oldValue) {
					if (Helpers.isObjEmpty(newValue) && newValue === oldValue) {
						return false;
					} else if (!Helpers.isObjEmpty(scope.pgLocation)) {
						if (scope.pgLocation.id_country) {
							requestLocation('regions', {name: 'country_code', id: scope.pgLocation.id_country}).then(function(regions) {
								for (var key in regions) {
									if (regions[key].id === scope.pgLocation.id_region) {
										scope.autocomplete.region = regions[key].name;
										break;
									}
								}
							});
						}
						if (scope.pgLocation.id_city && 0 !== parseInt(scope.pgLocation.id_city)) {
							var data = {
								type: 'city',
								var : scope.pgLocation.id_city
							};
							Api.query({module: 'countries', method: 'get_data'}, data).then(function(resp) {
								scope.autocomplete.city = resp.data.city.name;
							}, function(resp) {
								console.log(resp);
							});
						}
					}
					listener();
				});

				var timer;
				var acTimeout = 500;
				var requestLocation = function(locName, parent, data) {
					var deferred = $q.defer();
					data = data || {};
					if (parent) {
						data[parent.name] = parent.id;
					}
					// TODO: cache
					Api.indicator.setSmall();
					Api.query({module: 'countries', method: 'get_' + locName}, data).then(function(resp) {
						scope[locName] = resp.data[locName].items;
						deferred.resolve(resp.data[locName].items);
						Api.indicator.setNormal();
					}, function() {
						scope[locName] = {};
						Api.indicator.setNormal();
					});
					return deferred.promise;
				};
				
				/*scope.selectFirstRegion = function() {
					var topRegion = $filter('filter')(scope.regions, scope.autocomplete.region)[0];
					if(undefined === topRegion) {
						return false;
					}
					scope.pgLocation.id_region = topRegion.id;
				};
				scope.selectFirstCity = function() {
					var topCity = $filter('filter')(scope.cities, scope.autocomplete.city)[0];
					if(undefined === topCity) {
						return false;
					}
					scope.pgLocation.id_city = topCity.id;
				};*/

				// Города, в отличие от регионов, запрашиваются по мере ввода
				scope.searchCity = function() {
					$timeout.cancel(timer);
					timer = $timeout(function() {
						var parent = {name: 'region_id', id: scope.pgLocation.id_region};
						requestLocation('cities', parent, {search: scope.autocomplete.city}).then(function(cities) {
							//scope.pgLocation.id_city = cities[0].id;
						});
					}, acTimeout);
				};

				// Когда пользователь выбирает из списка
				scope.selectCountry = function() {
					// Сбрасываем регионы и города
					scope.regions = scope.cities = [];
					scope.autocomplete.region = scope.autocomplete.city = '';
					scope.pgLocation.id_region = scope.pgLocation.id_city = 0;

					scope.showRegions = true;
					if (scope.pgLocation.id_country) {
						requestLocation('regions', {name: 'country_code', id: scope.pgLocation.id_country}).then(function(regions) {
							//scope.pgLocation.id_region = regions[0].id;
						});
					} else {
						scope.pgLocation.id_country = 0;
					}
				};
				
				scope.selectRegion = function(clear) {
					var init = function() {
						scope.showCities = true;
						scope.cities = [];
						scope.autocomplete.city = '';
						scope.pgLocation.id_city = 0;
					};
					var region;
					if(true === clear) {
						init();
						scope.autocomplete.region = '';
						scope.pgLocation.id_region = 0;
						return;
					}
					if(undefined === scope.regions) {
						return false;
					}
					for(var key in scope.regions) {
						if(scope.pgLocation.id_region === scope.regions[key].id) {
							region = scope.regions[key];
							break;
						}
					};
					if(undefined === region) {
						return false;
					}
					init();
					scope.autocomplete.region = region.name;
					scope.pgLocation.id_region = region.id;
				};
				scope.selectCity = function(clear) {
					if(true === clear) {
						scope.autocomplete.city = '';
						scope.pgLocation.id_city = 0;
						return;
					};
					var city;
					if(undefined === scope.cities) {
						return false;
					}
					for(var key in scope.cities) {
						if(scope.pgLocation.id_city === scope.cities[key].id) {
							city = scope.cities[key];
							break;
						}
					};
					if(undefined === city) {
						return false;
					}
					scope.autocomplete.city = city.name;
					scope.pgLocation.id_city = city.id;
				};
				scope.l = $rootScope.l;
				requestLocation('countries');
			},
			templateUrl: 'views/directive/pgLocation.html',
			scope: {
				pgLocation: '='
			}
		};
	})
	.directive('pgBlink', function() {
		return {
			link: function(scope, element, attrs) {
				element.hide();
				var showDuration = parseInt(attrs.showDuration) || "fast";
				var hideDuration = parseInt(attrs.hideDuration) || "fast";
				scope.$watch('pgBlink', function(newValue, oldValue) {
					if(newValue !== true) {
						return false;
					}
					element.stop(true, true)
							.fadeIn(showDuration, function() {
								element.fadeOut(hideDuration);
							});
					scope.pgBlink = false;
				});
			},
			scope: {
				pgBlink: '='
			}
		};
	})
	.directive("modalShow", function($parse) {
		return {
			restrict: "A",
			link: function(scope, element, attrs) {
				//Hide or show the modal
				scope.showModal = function(visible, elem) {
					if (!elem) {
						elem = element;
					}
					if (visible) {
						elem.modal("show");
					} else {
						elem.modal("hide");
					}
				};
				//Watch for changes to the modal-visible attribute
				scope.$watch(attrs.modalShow, function(newValue, oldValue) {
					scope.showModal(newValue, attrs.$$element);
				});
				//Update the visible value when the dialog is closed through UI actions (Ok, cancel, etc.)
				element.bind("hide.bs.modal", function() {
					$parse(attrs.modalShow).assign(scope, false);
					if (!scope.$$phase && !scope.$root.$$phase) {
						scope.$apply();
					}
				});
			}
		};
	}).directive('contenteditable', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attrs, ctrl) {
			// view -> model
			element.bind('keyup', function() {
				scope.$apply(function() {
					ctrl.$setViewValue(element.html());
				});
			});

			// model -> view
			ctrl.$render = function() {
				element.html(ctrl.$viewValue);
			};
		}
	};
	}).directive('toggleSwitch', function() {
		// http://cgarvis.github.io/angular-toggle-switch/
		return {
			restrict: 'EA',
			replace: true,
			scope: {
				model: '=',
				disabled: '@',
				onLabel: '@',
				offLabel: '@',
				knobLabel: '@'
			},
			templateUrl: 'views/directive/toggleSwitch.html',
			controller: function($scope) {
				$scope.toggle = function() {
					if (!$scope.disabled) {
						$scope.model = !$scope.model;
					}
				};
			},
			compile: function(element, attrs) {
				if (!attrs.onLabel) {
					attrs.onLabel = '';
				}
				if (!attrs.offLabel) {
					attrs.offLabel = '';
				}
				if (!attrs.knobLabel) {
					attrs.knobLabel = '\u00a0';
				}
				if (!attrs.disabled) {
					attrs.disabled = false;
				}
			}
		};
	});