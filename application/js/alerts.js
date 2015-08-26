function Alerts(optionArr){

	var _self = this;
	if(arguments.callee._singletonInstance) {
		return arguments.callee._singletonInstance;
	}
	arguments.callee._singletonInstance = _self;

	var randNumber = Math.round(Math.random(1000) * 1000);

	this.properties = {
		alertBlockID: 'alert' + randNumber,
		alertBlockClass: 'alert',
		alertBlockBgID: 'alert_bg' + randNumber,
		alertBlockBgClass: 'alert_bg',
		alertTextID: 'alert_text' + randNumber,
		alertTextClass: 'alert_text',
		alertOkID: 'alert_ok' + randNumber,
		alertOkClass: 'alert_ok',
		alertCancelID: 'alert_cancel' + randNumber,
		alertCancelClass: 'alert_cancel',
		alertOkName: 'Ok',
		alertCancelName: 'Cancel'
	};

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.create_elements();
		return _self;
	};

	this.uninit = function(){
		$('#' + _self.properties.alertBlockBgID).remove();
		$('#' + _self.properties.alertBlockID).remove();
		return _self;
	};

	this.create_elements = function() {
		var bg = $('<div>').attr({
			id: _self.properties.alertBlockBgID,
			class:_self.properties.alertBlockBgClass
		});
		var window = $('<div>').attr({
			id: _self.properties.alertBlockID,
			class: _self.properties.alertBlockClass + ' p20 hide'
		});
		var text = $('<div>').attr({
			id: _self.properties.alertTextID,
			class: _self.properties.alertTextClass
		});
		var btnOk = $('<button>').attr({
			id: _self.properties.alertOkID,
			class: _self.properties.alertOkClass + ' mt20 mlr20'
		}).html(_self.properties.alertOkName);
		var btnCancel = $('<button>').attr({
			id: _self.properties.alertCancelID,
			class: _self.properties.alertCancelClass + ' mt20 mlr20'
		}).html(_self.properties.alertCancelName);
		
		window.append(text, btnOk, btnCancel);
		$('body').append(bg, window);
		return _self;
	};

	this.bind = function(callback, obj) {
		if('function' === typeof(callback)) {
			obj.off('click')
				.on('click', function(e){
					e.stopPropagation();
					callback();
					_self.hide();
				});
		} else {
			_self.bind(function(){_self.hide();}, obj);
		};
		return _self;
	};

	this.show = function(options) {
		if('undefined' === typeof(options)) {
			console.error('Empty alert options');
			return _self;
		} else if('string' === typeof(options)) {
			options = {
				type: 'alert',
				text: options,
				ok_button: _self.properties.alertOkName,
				cancel_button: _self.properties.alertCancelName
			};
		};
		if(options.ok_button) {
			$('#' + _self.properties.alertOkID).html(options.ok_button);
		} else {
			$('#' + _self.properties.alertOkID).html(_self.properties.alertOkName);
		}
		
		if(options.cancel_button) {
			$('#' + _self.properties.alertCancelID).html(options.cancel_button);
		} else {
			$('#' + _self.properties.alertCancelID).html(_self.properties.alertCancelName);
		}

		if('undefined' === typeof(options.text)) {
			console.error('Empty alert text');
			return _self;
		} else if(-1 === $.inArray(options.type, ['alert', 'confirm'])) {
			options.type = 'alert';
		};
		$('#' + _self.properties.alertTextID).html(options.text);
		$('#' + _self.properties.alertBlockID).fadeIn();
		_self.active_bg()
			.reposition();
		if('alert' === options.type) {
			$('#' + _self.properties.alertCancelID).hide();
			_self.bind(_self.hide, $('#' + _self.properties.alertBlockBgID))
				.bind(options.ok_callback, $('#' + _self.properties.alertOkID));
		} else if('confirm' === options.type) {
			$('#' + _self.properties.alertCancelID).show();
			_self.bind(options.cancel_callback, $('#' + _self.properties.alertBlockBgID))
				.bind(options.ok_callback, $('#' + _self.properties.alertOkID))
				.bind(options.cancel_callback, $('#' + _self.properties.alertCancelID));
		};
		return _self;
	};

	this.hide = function(){
		$('body').css('overflow', 'auto')
			.css('margin-right', 'auto');
		$('#' + _self.properties.alertBlockBgID + ', #' + _self.properties.alertBlockID).fadeOut(300, function(){
			_self.inactive_bg();
		});
		return _self;
	};

	this.reposition = function() {
		$('#' + _self.properties.alertBlockID).removeClass('hide').position({
			my: 'center',
			at: 'center',
			of: '#' + _self.properties.alertBlockBgID
		});
		return _self;
	};

	this.active_bg = function(){
		$('#' + _self.properties.alertBlockBgID + ', #' + _self.properties.alertBlockID)
			.fadeIn(100)
			.unbind();
		return _self;
	};

	this.inactive_bg = function(){
		if($('#' + _self.properties.alertBlockBgID).css('display') !== 'none'){
			$('#' + _self.properties.alertBlockBgID + ', #' + _self.properties.alertBlockID)
				.fadeOut()
				.unbind();
		};
		return _self;
	};

	_self.Init(optionArr);

}
