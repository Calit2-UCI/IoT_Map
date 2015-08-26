function fieldEditorSelect(optionArr){
	this.properties = {
		siteUrl: '',
		fieldID: '',
		itemsBlockID: 'select_options_block',
		optionFormID: 'change_option_block',
		itemsUlID: 'select_options',
		addLinkID: 'add_option_link',
		
		defaultHiddenID: 'hidden_block',
		defaultHiddenName: 'settings_data[default_value]',
		defaultMultiple: false,
		defaultValues:[],
		
		urlSaveSort: 'admin/field_editor/ajax_save_select_option_sorter/',
		urlDeleteItem: 'admin/field_editor/ajax_delete_select_option/',
		urlFormItem: 'admin/field_editor/ajax_get_select_option_form/',
		urlSaveItem: 'admin/field_editor/ajax_set_select_option/',
		urlGetBlock: 'admin/field_editor/ajax_get_field_select_options/',
		btnCancelID: 'btn_cancel',
		btnSaveID: 'btn_save',
		onActionUpdate: true,
		errorObj: new Errors(),
		contentObj: new loadingContent({closeBtnClass: 'w'})
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.set_sortable();
		$('#'+_self.properties.addLinkID).bind('click', function(){
			_self.get_option_form('');
			return false;
		});
		_self.update_default_block();
	}

	this.set_sortable = function(){
		$("#"+_self.properties.itemsUlID).sortable({
			items: 'li',
			scroll: true,
			forcePlaceholderSize: true,
			placeholder: 'limiter',
			revert: true,
			update: function(event, ui) { 
				if(_self.properties.onActionUpdate){
					_self.update_sorting();
				}
			}
		});

		$("#"+_self.properties.itemsUlID+' > li').each(function(){
			var gid = $(this).attr('id');
			var option_gid = gid.substring(7);
			$(this).find('.edit_link').bind('click', function(){
				_self.get_option_form(option_gid);
				return false;
			});

			$(this).find('.delete_link').bind('click', function(){
				_self.delete_option(option_gid);
				return false;
			});

			$(this).find('.active_link').bind('click', function(){
				_self.set_default_option(option_gid, 'activate');
				return false;
			});

			$(this).find('.deactive_link').bind('click', function(){
				_self.set_default_option(option_gid, 'deactivate');
				return false;
			});
		});
	}
	
	this.reload_block = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlGetBlock + _self.properties.fieldID, 
			type: 'GET',
			cache: false,
			success: function(data){
				$("#"+_self.properties.itemsBlockID).html(data);
				_self.set_sortable();
				_self.update_default_block();
			}
		});
	}

	this.update_sorting = function(){
		var data = new Object;
		$("#"+_self.properties.itemsUlID+' > li').each(function(i){
			data[$(this).attr('id')] = i+1;
		});
		
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlSaveSort + _self.properties.fieldID, 
			type: 'POST',
			data: ({sorter: data}), 
			cache: false,
			success: function(data){
//				show_error('{/literal}{l i="page_sorting_save_success"}{literal}');
			}
		});
	}

	this.get_option_form = function(option_gid){
		$.ajax({
			url:  _self.properties.siteUrl+_self.properties.urlFormItem+_self.properties.fieldID+'/'+option_gid, 
			cache: false,
			success: function(data){
				_self.properties.contentObj.show_load_block(data);
				$('#'+_self.properties.btnCancelID).bind('click', function(){
					_self.properties.contentObj.hide_load_block();
					return false;
				});
				$('#'+_self.properties.btnSaveID).bind('click', function(){
					_self.save_option_form(option_gid);
				});
			}
		});
	}

	this.save_option_form = function(option_gid){
		var data = new Object;
		$("#"+_self.properties.optionFormID+' input').each(function(i){
			data[$(this).attr('name')] = $(this).val();
		});
		
		$.ajax({
			url:  _self.properties.siteUrl+_self.properties.urlSaveItem+_self.properties.fieldID+'/'+option_gid, 
			type: 'POST',
			data: ({data: data}), 
			dataType: 'json',
			cache: false,
			success: function(ret){

				var isError = parseInt(ret.is_error);
				if(isError == 1){
					_self.properties.errorObj.show_error_block(ret.errors, 'error');
				}else{
					_self.properties.contentObj.hide_load_block();
					_self.reload_block();
				}
				
			}
		});
	}

	this.delete_option = function(option_gid){
		$.ajax({
			url:  _self.properties.siteUrl+_self.properties.urlDeleteItem+_self.properties.fieldID+'/'+option_gid, 
			cache: false,
			success: function(data){
				_self.reload_block();
			}
		});
	}

	this.set_default_option = function(option_gid, type){
		if(type == 'activate'){
			if(!_self.properties.defaultMultiple){
				_self.properties.defaultValues = [];
			}
			_self.properties.defaultValues.push(option_gid);
		}else if(type == 'deactivate'){
			if(!_self.properties.defaultMultiple){
				_self.properties.defaultValues = [];
			}else{
				for(var i in _self.properties.defaultValues){
					if(_self.properties.defaultValues[i] == option_gid){
						_self.properties.defaultValues.splice(i,1);
					}
				}
			}			
		}
		
		_self.update_default_block();
	}

	this.update_default_block = function(){
		$('#'+_self.properties.defaultHiddenID).empty();
		var name = _self.properties.defaultHiddenName;
		if(_self.properties.defaultMultiple) name = name + '[]';
		$("#"+_self.properties.itemsUlID+' > li .active_link').show();
		$("#"+_self.properties.itemsUlID+' > li .deactive_link').hide();
		
		for(var i in _self.properties.defaultValues){
			$('#'+_self.properties.defaultHiddenID).append('<input type="hidden" name="'+name+'" value="'+_self.properties.defaultValues[i]+'">');
			$('#option_'+_self.properties.defaultValues[i]+' .active_link').hide();
			$('#option_'+_self.properties.defaultValues[i]+' .deactive_link').show();
		}
	}
	_self.Init(optionArr);
}