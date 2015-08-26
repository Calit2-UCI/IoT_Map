function available_view(optionArr){
	this.properties = {
		siteUrl: '/',
		checkAvailableAjaxUrl: 'users_services/ajax_available_contact/',
		buyAbilityAjaxUrl: 'users_services/ajax_activate_contact/',
		buyAbilityFormId: 'ability_form',
		buyAbilitySubmitId: 'ability_form_submit',
		formType: 'list',
		alert_ok_button: 'Ok',
		alert_cancel_button: 'Cancel',
		lang_delete_confirm: '',
		alert_type: '',

		success_request: function(message) {},
		fail_request: function(message) {},

		windowObj: new loadingContent({loadBlockWidth: '520px', closeBtnClass: 'w'})
	};

	var _p = {};
	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
	};

	this.check_users_services = function() {

	};

	this.check_available = function(id){
		id = id || '';
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.checkAvailableAjaxUrl + id,
			type: 'POST',
			dataType : "json",
			cache: false,
			success: function(data){
				if (data.display_login == 1){
					error_object.errors_access();
				}else if (_self.properties.alert_type){
					alerts.show({
						text: _self.properties.lang_delete_confirm,
						type: 'confirm',
						ok_button: _self.properties.alert_ok_button,
						cancel_button: _self.properties.alert_cancel_button,
						ok_callback: function() {
							if (data.available == 1){
								_self.properties.success_request('');
							} else {
								_self.properties.windowObj.show_load_block(data.content);
								_self.init_ability_form();
							}
						}
					});
				}else{
					if (data.available == 1){
						_self.properties.success_request('');
					} else {
						_self.properties.windowObj.show_load_block(data.content);
						_self.init_ability_form();
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			  }
		});
	};

	this.init_ability_form = function (){
		if(_self.properties.formType === 'select'){
			$('#' + _self.properties.buyAbilitySubmitId).unbind('click').bind('click', function(){
				var id_user_service = $('#' + _self.properties.buyAbilityFormId + ' select[name="id_user_service"]').val();
				if(id_user_service){
					_p.activate_request(id_user_service);
				}
			});
		}else if(_self.properties.formType === 'list'){
			$('#' + _self.properties.buyAbilityFormId).find('input[type="button"][data-value]').unbind('click').bind('click', function(){
				var id_user_service = parseInt($(this).data('value'));
				var alert = $(this).data('alert').replace(/<br>/g, '\n');
				if(!id_user_service) {
					return false;
				}
				if(alert){
					alerts.show({
						text: alert,
						type: 'confirm',
						ok_callback: function() {
							_p.activate_request(id_user_service);
						}
					});
				} else {
					_p.activate_request(id_user_service);
				}
			});
		}
	};

	_p.activate_request = function(id_user_service){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.buyAbilityAjaxUrl + id_user_service,
			type: 'GET',
			dataType : "json",
			cache: false,
			success: function(data){
				if (data.status == 1){
					_self.properties.success_request(data.message);
				} else {
					_self.properties.fail_request(data.message);
				}
				_self.properties.windowObj.hide_load_block();
			}
		});
	};

	this.set_properties = function(properties){
		_self.properties = $.extend(_self.properties, properties);
	};

	_self.Init(optionArr);
}