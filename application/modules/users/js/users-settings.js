function usersSettings(optionArr) {
	this.properties = {
		guest_view_profile_allow_id: '#guest_view_profile_allow',
		guest_view_profile_allow: {},
		guest_view_profile_limit_id: '#guest_view_profile_limit',
		guest_view_profile_limit: {},
		guest_view_profile_num_id: '#guest_view_profile_num',
		guest_view_profile_num: {}
	};

	var _self = this;

	var getObjects = function () {
		_self.properties.guest_view_profile_allow = $(_self.properties.guest_view_profile_allow_id);
		_self.properties.guest_view_profile_limit = $(_self.properties.guest_view_profile_limit_id);
		_self.properties.guest_view_profile_num = $(_self.properties.guest_view_profile_num_id);
	};

	var guestOptionsState = function() {
		if(_self.properties.guest_view_profile_allow.is(':checked')) {
			_self.properties.guest_view_profile_limit
					.attr('disabled', null);
		} else {
			_self.properties.guest_view_profile_limit
					.attr('disabled', 'disabled')
					.attr('checked', null);
		}
		if(_self.properties.guest_view_profile_limit.is(':checked')) {
			_self.properties.guest_view_profile_num
					.attr('disabled', null);
		} else {
			_self.properties.guest_view_profile_num
					.attr('disabled', 'disabled')
					.val(0);
		}
	};

	var bindEvents = function () {
		_self.properties.guest_view_profile_allow.on('change', function(){
			guestOptionsState();
		});
		_self.properties.guest_view_profile_limit.on('change', function(){
			guestOptionsState();
		});
	};

	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
		getObjects();
		bindEvents();
		guestOptionsState();
	};

	_self.Init(optionArr);

}