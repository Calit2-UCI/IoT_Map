function Spam(optionArr){
	this.properties = {
		siteUrl: '',
		mark_as_spam_btn: 'mark_as_spam',		
		use_form: false,
		is_send: false,
		error_is_send: 'spam_form',
		cFormId: 'spam_form',
		urlGetForm: 'spam/ajax_get_form/',
		urlSendForm: 'spam/ajax_mark_as_spam/',
		id_close: 'close_btn',
		isOwner: false,
		contentObj: new loadingContent({loadBlockWidth: '544px', closeBtnClass: 'load_content_close w', closeBtnPadding: 5}),
		errorObj: new Errors		
	};

	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		$('#'+_self.properties.mark_as_spam_btn).bind('click', function(){
			var type_gid = $(this).attr('data-type');
			var object_id = $(this).attr('data-id');
			var data = {object_id: object_id, type_gid: type_gid, is_owner: _self.properties.isOwner ? 1 : 0};
			if(_self.properties.use_form){
				if(_self.properties.is_send){
					_self.properties.errorObj.show_error_block(_self.properties.error_is_send, 'error');
				} else {
					_self.get_form(data, type_gid, object_id);
				}
			}else{
				_self.send_form(data, type_gid, object_id);
			}
			return false;
		}).show();
	}
	
	this.get_form = function(data, type_gid, object_id){
		type_gid = type_gid || '';
		object_id = object_id || '';
		if(_self.properties.is_send){
			_self.properties.errorObj.show_error_block(_self.properties.error_is_send, 'error');
		} else {
			$.ajax({
				url: _self.properties.siteUrl + _self.properties.urlGetForm,
				type: 'POST',
				data: data,
				cache: false,
				success: function(data){
					if(data){
						if (data=='is_send') {
							_self.properties.errorObj.show_error_block(_self.properties.error_is_send, 'error');
						} else {
							_self.properties.contentObj.show_load_block(data);
							$('#'+_self.properties.id_close).unbind().bind('click', function(){
								_self.clearBox(type_gid, object_id);
							});
						}
					}else{
						_self.properties.use_form = false;
						_self.send_form(data, type_gid, object_id);
					}
				}
			});
		}
		
		return false;
	}
	
	this.send_form = function(data, type_gid, object_id){
		type_gid = type_gid || '';
		object_id = object_id || '';
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlSendForm,
			type: 'POST',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(data){
				if(typeof(data.error) != 'undefined' && data.error != ''){
					_self.properties.errorObj.show_error_block(data.error, 'error');
				}else{
					_self.properties.is_send = true;
					_self.properties.errorObj.show_error_block(data.success, 'success');
					_self.properties.contentObj.hide_load_block();
					var btn = $('#'+_self.properties.mark_as_spam_btn);
					//btn.attr('href', '').unbind('click');
					var ins = btn.find('ins');
					if(ins.length){
						ins.addClass('g');
						if (type_gid=='media_object') {
							var btn2 = $('#'+type_gid+'_'+object_id);
							btn2.addClass('g');
						}
					}else{
						btn.hide();
					}
				}
			}
		});
				
		return false;
	}
	
	this.clearBox = function(type_gid, object_id){
		var data = $('#'+_self.properties.cFormId).serialize();
		_self.send_form(data, type_gid, object_id);
	}

	_self.Init(optionArr);
}
