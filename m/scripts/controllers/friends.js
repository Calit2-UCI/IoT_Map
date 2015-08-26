'use strict';

angular.module('datingMobile').controller('FriendsCtrl', function ($rootScope, $scope, Api, Layout, Helpers) {
	$rootScope.dependOnLang(function() {
		$rootScope.actions = {
			text: $rootScope.l('page_friends')
		};
	});

	var btnEdit = function() {
		$rootScope.actions.text = $rootScope.l('page_friends');
		$rootScope.rightBtn = {
			class: 'fa fa-pencil',
			click: function() {
				$scope.editMode = true;
				btnSave();
			}
		};
	};
	var btnSave = function() {
		$rootScope.actions.text = $rootScope.l('page_friends_remove');
		$rootScope.rightBtn = {
			class: 'fa fa-check',
			click: function() {
				confirmRemoval();
				$scope.editMode = false;
				btnEdit();
			}
		};
	};

	btnEdit();

	$scope.removeIds = [];
	var remove = function() {
		Api.query({module: 'friendlist', method: 'remove'}, {id_dest_user: $scope.removeIds}).then(function(){
			$scope.friends = $scope.friends.filter(function(friend) {
				return $scope.removeIds.indexOf(friend.id_dest_user) < 0;
			});
			$scope.removeIds = [];
		});
	};

	var confirmRemoval = function() {
		if(!$scope.removeIds.length) {
			return false;
		}
		Layout.showModal({
			position: 'bottom',
			buttons: [
				{
					text: $rootScope.l('friends_btn_remove'),
					class: 'btn-primary',
					action: function() {
						remove();
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

	$scope.toRemove = function(id) {
		$scope.removeIds.push(id);
	};

	$scope.friends = [];
	$scope.requests = [];
	var checkLists = function() {
		if(Helpers.isObjEmpty($scope.requests) && Helpers.isObjEmpty($scope.friends)) {
			Layout.addAlert('warning', $rootScope.l('friends_empty_list'));
			$rootScope.rightBtn = {
				class: 'fa fa-search',
				href: 'search'
			};
			return false;
		}
		return true;
	};

	Api.query({module: 'friendlist', method: 'friends_requests'}, {formatted: true}).then(function(respReq){
		$scope.requests = respReq.data;
		Api.query({module: 'friendlist', method: 'index'}, {formatted: true}).then(function(respFriends){
			$scope.friends = respFriends.data;
			checkLists();
		});
	});

});