"use strict";
function adminNetwork(optionArr) {

	var _self = this;

	this.properties = {
		showLog: true,
		frmNetwork: '#network-form',
		inputDomain: '#network-domain',
		inputKey: '#network-key',
		interval: 1000,
		siteUrl: '',
		statusUrl: 'admin/network/ajax_get_status',
		parent: {},
		status: false,
		prevFast: '',
		prevSlow: ''
	};

	var getStatus = function() {
		return $.ajax({
			dataType: 'json',
			url: _self.properties.siteUrl + _self.properties.statusUrl,
			context: document.body
		});
	};

	var setObjects = function() {
		_self.properties.parent = $('.network-content');
		_self.properties.fast = $('#fast', _self.properties.parent);
		_self.properties.slow = $('#slow', _self.properties.parent);
	};

	var processStatus = function(status) {
		if(_self.properties.prevSlow !== status.slow.log) {
			_self.properties.prevSlow = status.slow.log;
			_self.properties.slow.html(status.slow.log);
		}
		if(_self.properties.prevFast !== status.fast.log) {
			_self.properties.prevFast = status.fast.log;
			_self.properties.fast.text(status.fast.log);
		}
	};

	var startBackend = function() {
		setInterval(function() {
			getStatus().done(function(result) {
				processStatus(result);
			});
		}, _self.properties.interval);
	};

	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);
		setObjects();
		_self.uninit();
		_self.bindEvents();
		if(_self.properties.showLog) {
			startBackend();
		}
	};

	this.uninit = function() {
		$(_self.properties.frmNetwork).off('submit');
	};

	this.formIsValid = function() {
		return true;
	};

	this.bindEvents = function() {
		_self.uninit();
		$(_self.properties.frmNetwork).on('submit', function() {
			return _self.formIsValid();
		});
	};

	_self.Init(optionArr);
}