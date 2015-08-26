'use strict';

angular.module('datingMobile').factory('Layout', function($rootScope, $location, $timeout) {
	// TODO: Переделать в сервис
	var alerts = [];
	var alertsTimeout = [];
	var alertDefaultPeriod = 8000;
	return {
		modal: {},
		topMessage: false,
		sideMenuVisible: false,
		sideMenuDisabled: false,
		topBarVisible: true,
		modalVisible: false,
		// Боковое меню
		showSideMenu: function() {
			if (!this.sideMenuDisabled) {
				this.sideMenuVisible = true;
			}
		},
		hideSideMenu: function() {
			if(this.sideMenuVisible) {
				this.sideMenuVisible = false;
			}
		},
		toggleSideMenu: function() {
			this.sideMenuVisible = !this.sideMenuVisible;
		},
		disableSideMenu: function() {
			this.hideSideMenu();
			this.sideMenuDisabled = true;
		},
		enableSideMenu: function() {
			this.sideMenuDisabled = false;
		},
		isSideMenuVisible: function() {
			return this.sideMenuVisible;
		},
		// TODO: Вызывать тут же
		setActiveMenuItem: function() {
			var location = $location.path().substring(1);
			$.map($rootScope.mainMenu.items, function(item/*, index*/) {
				if(!item) {
					return false;
				}
				if (item.href && item.href.toLowerCase() === location) {
					item.isActive = true;
				} else {
					item.isActive = false;
				}
			});
		},
		// Верхняя панель
		showTopBar: function() {
			this.topBarVisible = true;
		},
		hideTopBar: function() {
			this.topBarVisible = false;
		},
		toggleTopBar: function() {
			this.topBarVisible = !this.topBarVisible;
		},
		isTopBarVisible: function() {
			return this.topBarVisible;
		},
		// Алерты
		addAlert: function(type, message, isPermanent, alertPeriod) {
			isPermanent = isPermanent || false;
			// Проверка уникальности (по тексту)
			for (var alert in alerts) {
				if (alerts[alert] && alerts[alert].msg === message) {
					return false;
				}
			}
			var availableTypes = ['success', 'info', 'warning', 'danger'];
			if (-1 === availableTypes.indexOf(type)) {
				console.error('Wrong alert type');
			} else {
				if (typeof message === 'string') {
					var index = alerts.length;
					alerts[index] = {
						type: type,
						msg: message,
						time: new Date().getTime(),
						isPermanent: isPermanent
					};
					// Таймаут, в течение которого сообщение висит
					alertsTimeout[index] = $timeout(function() {
						if(!alerts[index]) {
							return false;
						}
						alerts[index].expired = true;
						$timeout(function() {
							delete alerts[index];
						}, 3);
					}, alertPeriod || alertDefaultPeriod);
				} else {
					// TODO: Сделать проверку на итерабельность
					for (var i in message) {
						this.addAlert(type, message[i], isPermanent);
					}
				}
			}
		},
		getAlerts: function() {
			return alerts;
		},
		removeAlerts: function(includingPermanent) {
			includingPermanent = includingPermanent || false;
			if (includingPermanent) {
				alerts = [];
			} else {
				for (var key in alerts) {
					if (alerts[key] && !alerts[key].isPermanent) {
						delete alerts[key];
					}
				}
			}
		},
		// Модальное окно
		showModal: function(modalData) {
			if (!modalData.buttons || (0 === modalData.buttons.length)) {
				modalData.buttons.push({
					text: l('btn_close'),
					class: 'btn-primary'
				});
			}
			this.modal = modalData;
			this.modalVisible = true;
		},
		hideModal: function() {
			this.modalVisible = false;
			this.modal = {};
		},
		confimDelete: function(func, confirmText) {
			this.showModal({
				position: 'bottom',
				buttons: [
					{
						text: confirmText || $rootScope.l('btn_delete'),
						class: 'btn-primary',
						action: function() {
							func();
						}
					},
					{
						text: $rootScope.l('btn_cancel'),
						class: 'btn-default'
					}
				]
			});
		},
		suggestMobApp: function() {
			var appUrl = $rootScope.getAppUrl();
			var Layout = this;
			this.setTopMessage({
				close: function() {
					Layout.removeTopMessage();
				},
				class: 'suggest',
				buttons: [
					appUrl ? {
						text: $rootScope.l('btn_get_app'),
						class: 'btn-primary btn-lg col-xs-offset-1',
						colSize: 10,
						action: function() {
							$rootScope.go(appUrl);
						}
					} : {},
					{
						text: $rootScope.l('btn_back_to_main_site'),
						class: 'btn-default btn-sm col-xs-offset-1',
						colSize: 10,
						action: function () {
							$rootScope.goToMainSite();
						}
					}
				]
			});
		},
		// Верхнее сообщение
		setTopMessage: function(data) {
			this.topMessage = data;
			this.topMessage.btnColSize = Math.round(12 / data.buttons.length);
			if('function' !== typeof this.topMessage.close) {
				var Layout = this;
				this.topMessage.close = function() {
					Layout.removeTopMessage();
				};
			}
		},
		removeTopMessage: function() {
			this.topMessage = false;
		}
	};
});