'use strict';

function membershipsAdmin(optionArr) {
	this.properties = {
		siteUrl: '/',
		activateUrl: 'admin/memberships/ajax_activate/',
		deactivateUrl: 'admin/memberships/ajax_deactivate/',
		activateServiceUrl: 'admin/memberships/ajax_activate_service/',
		deactivateServiceUrl: 'admin/memberships/ajax_deactivate_service/',
		btnDeleteObj: {},
		btnActivityObj: {},
		btnServiceActivityObj: {},
		errorObj: new Errors(),
		membershipId: '',
		msgConfirmDeletion: 'Are you sure?',
		parent: '.memberships',
		parentObj: {}
	};

	var _self = this;

	var initObjects = function () {
		_self.properties.parentObj = $(_self.properties.parent);
		_self.properties.btnDeleteObj = $('.btn-delete', _self.properties.parentObj);
		_self.properties.btnActivityObj = $('.btn-activity', _self.properties.parentObj);
		_self.properties.btnServiceActivityObj = $('.btn-service-activity', _self.properties.parentObj);
	};

	var showResult = function (result) {
		if ('undefined' !== typeof result.success) {
			_self.properties.errorObj.show_error_block(result.success, 'success');
		}
		if ('undefined' !== typeof result.errors) {
			_self.properties.errorObj.show_error_block(result.errors, 'error');
		}
	};

	var query = function (url, data, success, error) {
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'json',
			cache: false,
			data: data
		}).done(function (result) {
			if ('function' === typeof (success) && 'undefined' === typeof result.errors || result.errors.length < 1) {
				success();
			}
			showResult(result);
		}).fail(function (result) {
			if ('function' === typeof (error)) {
				error();
			}
			showResult(result);
		});
		return false;
	};

	var toggleMembership = function (btn) {
		var id = parseInt(btn.data('id'));
		var currentActivity = btn.data('activity');
		var newActivity = currentActivity !== 'true' && currentActivity !== true;
		var url = _self.properties.siteUrl;
		if (true === newActivity) {
			url += _self.properties.activateUrl;
		} else if (false === newActivity) {
			url += _self.properties.deactivateUrl;
		}
		query(url, {id: id}, function () {
			btn.data('activity', newActivity.toString());
			if (newActivity) {
				btn.find('i').removeClass('inactive');
			} else {
				btn.find('i').addClass('inactive');
			}
		});
		return false;
	};

	var toggleService = function (btn) {
		var serviceId = parseInt(btn.data('id'));
		var currentActivity = btn.data('activity');
		var newActivity = currentActivity !== 'true' && currentActivity !== true;
		var url = _self.properties.siteUrl;
		if (true === newActivity) {
			url += _self.properties.activateServiceUrl;
		} else if (false === newActivity) {
			url += _self.properties.deactivateServiceUrl;
		}
		query(url, {service_id: serviceId, membership_id: _self.properties.membershipId}, function () {
			btn.data('activity', newActivity.toString());
			if (newActivity) {
				btn.find('i').removeClass('inactive');
			} else {
				btn.find('i').addClass('inactive');
			}
		});
		return false;
	};

	var bind = function () {
		_self.properties.btnDeleteObj.on('click', function () {
			return confirm(_self.properties.msgConfirmDeletion);
		});
		_self.properties.btnActivityObj.on('click', function () {
			return toggleMembership($(this));
		});
		_self.properties.btnServiceActivityObj.on('click', function () {
			return toggleService($(this));
		});
	};

	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
		$('.data tr:odd').addClass('zebra');
		initObjects();
		if (parseInt(_self.properties.membershipId)) {
			_self.properties.activateServiceUrl += _self.properties.membershipId;
			_self.properties.deactivateServiceUrl += _self.properties.membershipId;
		}
		bind();
	};

	_self.Init(optionArr);
}