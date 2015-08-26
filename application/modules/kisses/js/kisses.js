function Kisses(optionArr){
	this.properties = {
		siteUrl: '',
		use_form: true,
		btnForm: 'btn-kisses',
		cFormId: 'kisses_form',
		urlGetForm: '',
		urlSendForm: '',
		id_close: 'btn_send_kisses',
		errorObj: new Errors,
		dataType: 'html',
		contentObj: new loadingContent({
			loadBlockWidth: '50%', 
			closeBtnClass: 'w', 
			scroll: true,
			closeBtnPadding: 5,
			blockBody: true,
		})
	};
	
	var _self = this;
	
	this.Init = function(options){
		
		_self.properties = $.extend(_self.properties, options);
		
		$('#'+_self.properties.btnForm).bind('click', function(){
			if(_self.properties.use_form){
				_self.get_form();
			}
			return false;
		}).show();
	}
	
	this.get_form = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlGetForm,
			type: 'POST',
			cache: false,
			dataType: _self.properties.dataType,
			success: function(data){
				if (data.errors) {
					error_object.show_error_block(data.errors, 'error');
				} else {
				_self.properties.contentObj.hide_load_block();
					_self.properties.contentObj.show_load_block(data);
					$('#'+_self.properties.id_close).unbind().bind('click', function(){
						_self.clearBox();
						return false;
					});
				}
			}
		});
		
		return false;
	}
	
	this.send_form = function(data){
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
					_self.properties.errorObj.show_error_block(data.success, 'success');
					_self.properties.contentObj.hide_load_block();
				}
			}
		});
		
		return false;
	}
	
	this.clearBox = function(){
		var data = $('#'+_self.properties.cFormId).serialize();
		
				console.log(data);
		_self.send_form(data);
	}

	_self.Init(optionArr);
}
