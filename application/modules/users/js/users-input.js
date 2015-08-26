function usersInput(optionArr){
	this.properties = {
		siteUrl: '',
		rand: '',
		id_user: '',
		load_user_link: 'users/ajax_get_users_data/',
		load_form: 'users/ajax_get_users_form/',
		load_data: 'users/ajax_get_selected_users/',
		id_main: '',
		id_text: '',
		id_open: '',
		id_hidden_user: '',
		id_bg: '',
		id_select: '',
		id_items: 'user_select_items',
		id_clear: 'user_select_clear',
		id_close: 'user_select_close',
		id_search: 'user_search',
		id_page: 'user_page',
		timeout_obj: null,
		timeout: 500,
		dropdownClass: 'dropdown',
		contentObj: new loadingContent({
			loadBlockWidth: '680px', closeBtnPadding: 15
		})
	}
	var _self = this;
	
	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.properties.id_main = 'user_select_'+_self.properties.rand;
		_self.properties.id_text = 'user_text_'+_self.properties.rand;
		_self.properties.id_open = 'user_open_'+_self.properties.rand;
		_self.properties.id_hidden_user = 'user_hidden_'+_self.properties.rand;
		//_self.properties.id_bg = 'user_input_bg_'+_self.properties.rand;
		//_self.properties.id_select = 'user_select_'+_self.properties.rand;

		$('#'+_self.properties.id_open).bind('click', function(){
			_self.open_form();
			return false;
		});
		
		/*$('#'+_self.properties.id_text).bind('keyup', function(){
			if(_self.properties.timeout_obj){
				clearTimeout(_self.properties.timeout_obj);
			}
			_self.properties.timeout_obj = setTimeout(function(){
				var name = $('#'+_self.properties.id_text).val();
				_self.emptyValues();
				if(name){
					_self.load_users(name, 1, _self.display_input);
				}else{
					_self.closeBox();
				}
			}, _self.properties.timeout)
			return true;
		});		
		_self.initBg();
		_self.initBox();*/
	}

	this.open_form = function(){
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_form,
			cache: false,
			success: function(data){
				_self.properties.contentObj.show_load_block(data);
				$('#'+_self.properties.id_clear).unbind().bind('click', function(){
					_self.clearBox();
				});
				_self.load_users();
				$('#'+_self.properties.id_search).unbind().bind('keyup', function(){
					_self.load_users($(this).val(), 1);
				});
				$('#' + _self.properties.id_close).bind('click', function() {
					_self.properties.contentObj.hide_load_block();
				});
			}
		});
	}

	this.load_users = function(search, page, callback){
		search = search || '';
		page = page || 1;
		callback = callback || _self.display_select;
		
		var send_data = {page: page};
		if(search) send_data.search = search;
		
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_user_link,
			dataType: 'json',
			type: 'POST',
			data: send_data,
			cache: false,
			success: function(data){
				callback(data);
			}
		});
	}
	
	this.display_select = function(data){
		$('#'+_self.properties.id_items).unbind();
		$('#'+_self.properties.id_items).empty();
		for(var id in data.items){
			$('#'+_self.properties.id_items).append('<li index="'+data.items[id].id+'">'+data.items[id].nickname + ' (' + data.items[id].output_name+')</li>');
		}
		_self.generate_pages(data.pages, data.current_page, name);
		$('#'+_self.properties.id_items+' li').bind('click', function(){
			_self.set_values($(this).attr('index'), $(this).text(), data);
			_self.properties.contentObj.hide_load_block();
		});
	}
	
	this.display_input = function(data){
		if(data.all > 0){
			$('#'+_self.properties.id_select+' ul').empty();
			for(var id in data.items){
				$('#'+_self.properties.id_select+' ul').append('<li gid="rs_'+id+'" data-id="'+data.items[id].id+'">'+data.items[id].nickname + ' (' + data.items[id].output_name+')</li>');
			}
			_self.openBox();
		}else{
			_self.closeBox();
		}
	}

	this.set_values = function(variable, value, data){
		var string_value = "";
		
		$('#'+_self.properties.id_hidden_user).val(variable.toString()).change();
		_self.properties.id_user = variable.toString();

		string_value = value;

		if(string_value == '') string_value = '...';
		$('#'+_self.properties.id_text).val(string_value);
	}
	
	this.set_values_text = function(user, value){
		$('#'+_self.properties.id_text).val(value);
		_self.properties.id_user = user;
		$('#'+_self.properties.id_hidden_user).val(_self.properties.id_user).change();
		_self.closeBox();
	}

	this.emptyValues = function(){
		_self.properties.id_user = '';
		$('#'+_self.properties.id_hidden_user).val(_self.properties.id_user).change();
	}
	
	this.generate_pages = function(pages, current_page, name){
		$('#'+_self.properties.id_page+' a').unbind();
		$('#'+_self.properties.id_page).empty();
		if(pages > 1){
			var start = Math.max(current_page-2, 1);
			var count = Math.min(pages, 5)
			for(var i=start; i<=count; i++){
				if(i == current_page){
					$('#'+_self.properties.id_page).append('<ins class="current">'+i+'</ins>');
				}else{
					$('#'+_self.properties.id_page).append('<ins><a href="#">'+i+'</a></ins>');
				}
			}
			$('#'+_self.properties.id_page+' a').bind('click', function(){
				_self.load_users(name, $(this).text());
				return false;
			});
		}
	}

	this.initBg = function(){
		$('body').append('<div id="'+_self.properties.id_bg+'"></div>');
		$('#'+_self.properties.id_bg).css({
			'display': 'none',
			'position': 'fixed',
			'z-index': '98999',
			'width': '1px',
			'height': '1px',
			'left': '1px',
			'top': '1px'
		});
	}
	
	this.expandBg = function(){
		$('#'+_self.properties.id_bg).css({
			'width': $(window).width()+'px',
			'height': $(window).height()+'px',
			'display': 'block'
		}).bind('click', function(){
			_self.closeBox();
		});
		
	}
	
	this.collapseBg = function(){
		$('#'+_self.properties.id_bg).css({
			'width': '1px',
			'height': '1px',
			'display': 'none'
		}).unbind();
	}
	
	this.initBox = function(){
		_self.createDropDown();

		$('#'+_self.properties.id_select).on('click', 'li', function(){
			_self.set_values_text($(this).attr('data-id'), $(this).text());
		});
	}
	
	this.unsetBox = function(){
		$('#'+_self.properties.id_select).unbind().remove();
	}
	
	this.openBox = function(){
		_self.expandBg();
		_self.resetDropDown();
		$('#'+_self.properties.id_select).slideDown();
	}
	
	this.createDropDown = function(){
		$('body').append('<div class="'+_self.properties.dropdownClass+'" id="'+_self.properties.id_select+'"><ul></ul></div>');
		_self.resetDropDown();
	}
	
	this.resetDropDown = function(){
		var top = $('#'+_self.properties.id_text).offset().top + $('#'+_self.properties.id_text).outerHeight();

		$('#'+_self.properties.id_select).css({
			width: $('#'+_self.properties.id_text).width()+'px',
			left: $('#'+_self.properties.id_text).offset().left+'px',
			top: top +'px'
		});
	}
	
	this.closeBox = function(){
		_self.collapseBg();
		$('#'+_self.properties.id_select).slideUp();
	}
	
	this.clearBox = function(){
		_self.set_values_text('', '');
		//_self.closeBox();
		_self.properties.contentObj.hide_load_block();
	}

	_self.Init(optionArr);
}
