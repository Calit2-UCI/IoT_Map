function flashchatAdmin(optionArr) {

	this.properties = {
		adminFile: ''
	};
	this.chatSettings = {};

	this.errors = {};

	var _self = this;
	var _commonParent = '#flashchat';
	
	var _admin_panel;
	var _embed;

	var changeServerType = function(type) {
		if('by_flashchat_free' === type || undefined === _self.chatSettings.server_settings[type].client_location) {
			_admin_panel.hide();
		} else {
			_embed.attr('src', _self.chatSettings.server_settings[type].client_location + _self.properties.adminFile);
			_admin_panel.show();
		}
		$('.server_settings', _commonParent).slideUp();
		$('#settings_' + type, _commonParent).slideDown();
	};

	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);
		_embed = $('#embed', _commonParent);
		_admin_panel = $('#admin_panel', _commonParent);
		_self.bindEvents();
	};

	this.bindEvents = function() {
		$('.server_type', _commonParent).on('change', function() {
			changeServerType($(this).val());
		});
		
		$('[name="settings[server_settings][by_user][server_type]"]').change(function(){
			var type = $(this).val();
			var location_path = $('#client_location').val();
			if ('ppvsoftware' === type) {
				$('#init_port').val('51212');
				$('#init_port_h').val('31212');
				$('#client_location').val(location_path.replace("35555", "31212"));
				$('#client_type').hide();
			} else if ('flashchat' === type) {
				$('#init_port').val('51127');
				$('#init_port_h').val('35555');
				$('#client_location').val(location_path.replace("31212", "35555"));
				$('#client_type').show();
			}
		});
	};

	_self.Init(optionArr);
}