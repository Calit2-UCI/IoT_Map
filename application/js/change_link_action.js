function change_link_action(optionArr){
	this.properties = {
		siteUrl: '',
		viewUrl: 'resumes/ajax_section/',
		viewAjaxUrl: 'resumes/ajax_get_section/',
		listBlockParam: '.class_or_id',
		errorObj: new Errors(),
		successCallBack: function (){}
		
	}

	var _self = this;
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.init_links();
	}

	this.init_links = function(){
		$(_self.properties.listBlockParam).each(function(){
			var new_url = $(this).attr('href').replace(_self.properties.viewUrl, _self.properties.viewAjaxUrl);
			$(this).bind('click', function(){
				_self.send_request(new_url, $(this));
				return false;
			});
		});
	}
		
	this.send_request = function(url, obj){
		$.ajax({
			url: url, 
			type: 'POST',
			cache: false,
			dataType: 'json',
			error: function(jqXHR, textStatus, errorThrown){
				error_object.errors_access();
			},
			success: function(data){
				if(data){
					if(data.error){
						_self.properties.errorObj.show_error_block(data.message, 'error');
					}else if(data.success){
						_self.properties.errorObj.show_error_block(data.message, 'success');
						_self.properties.successCallBack(obj);
					}
				}
			}
		});
	}
	
	_self.Init(optionArr);
}