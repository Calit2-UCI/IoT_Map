function usersList(optionArr){
	this.properties = {
		siteUrl: '',
		viewUrl: 'users/index', 
		viewAjaxUrl: 'users/ajax_index',
		listBlockId: 'users_block',
		savedListView: false,
		saveSearchUrl: 'users/ajax_save_search',
		errorObj: new Errors(),
		tIds: []
	}

	var _self = this;


	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.init_links();
		_self.init_form();
	}
	
	this.uninit = function(){
		if(_self.properties.tIds.length){
			for(var index in _self.properties.tIds){
				var id = _self.properties.tIds[index];
				$('#'+id+' a').unbind('click');
			}
		}
		$('#save_search_link').off('click');
		$('#user_search').off('keyup');
		$('#user_reset').off('click');
	}

	this.init_links = function(){
		if(_self.properties.tIds.length){
			for(var index in _self.properties.tIds){
				var id = _self.properties.tIds[index];
				$('#'+id+' a').unbind('click').bind('click', function(e){
					var new_url = $(this).attr('href').replace(_self.properties.viewUrl, _self.properties.viewAjaxUrl);
					if(new_url != $(this).attr('href')){
						_self.loading_block(new_url);
						return false;
					}
				});
			}
		}
		
		$('#save_search_link').on('click', function(){
			_self.save_search();
			return false;
		});
	}


	this.init_form = function(){
		$('#user_search').on('keyup', function(){
			_self.search();
			return false;
		});
		$('#user_reset').on('click', function(){
			$('form#user_search_form')[0].reset();
			_self.search();
			return false;	
		});
	}

	this.search = function(){
		var send_data = $('#user_search_form').serialize();
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.viewAjaxUrl,
			type: 'POST',
			cache: false,
			data: send_data,
			success: function(data){
				$('#'+_self.properties.listBlockId).html(data);
				_self.init_links();
			}
		});
	}

	this.loading_block = function(url){
		$.ajax({
			url: url, 
			type: 'GET',
			cache: false,
			success: function(data){
				$('#'+_self.properties.listBlockId).html(data);
				_self.init_links();
			}
		});
	}
	
	this.loading_post_block = function(post_data, url){
		if(!url){
			url = _self.properties.siteUrl + _self.properties.viewAjaxUrl;
		}
		$.ajax({
			url: url, 
			type: 'POST',
			data: post_data,
			cache: false,
			success: function(data){
				$('#'+_self.properties.listBlockId).html(data);
				_self.init_links();
			}
		});
	}
	
	
	this.save_search = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.saveSearchUrl, 
			type: 'GET',
			cache: false,
			dataType: 'json',
			success: function(data){
				if(data.success){
					_self.properties.errorObj.show_error_block(data.success, 'success');
				}else{
					_self.properties.errorObj.show_error_block(data.error, 'error');
				}
				return false;
			}
		});
	}	
	_self.Init(optionArr);
}