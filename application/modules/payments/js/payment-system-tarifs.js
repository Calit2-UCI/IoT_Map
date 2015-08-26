function PaymentSystemTarifs(optionArr){
	this.properties = {
		siteUrl: '',
		systemGid: '',
		useOperators: false,
		blockId: 'system_tarifs_block',
		formId: 'operator_edit_block',
		sortId: 'operators_sorting',
		sortUrl: 'admin/payments/ajax_save_system_operators_sorter/',
		deleteUrl: 'admin/payments/ajax_delete_system_operator/',
		formUrl: 'admin/payments/ajax_get_system_operator_form/',
		saveUrl: 'admin/payments/ajax_save_system_operator_data/',
		activateUrl: 'admin/payments/ajax_set_system_operator_status/',
		blockUrl: 'admin/payments/ajax_get_system_operators/',
		cancelBtnId: 'btn_cancel',
		saveBtnId: 'btn_save',
		addBtnId: 'add_operator_link',
		onActionUpdate: true,
		errorObj: new Errors(),
		contentObj: new loadingContent,
	}

	var _self = this;

	this.errors = {}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.set_sortable();
		$('#'+_self.properties.addBtnId).bind('click', function(){
			_self.get_operator_form();
			return false;
		});
	}

	this.set_sortable = function(){
		$("#"+_self.properties.sortId).sortable({
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

		$("#"+_self.properties.sortId+' > li').each(function(){
			var gid = $(this).attr('id');
			var operator_gid = gid.substring(9);
			
			$(this).find('.add_link').bind('click', function(){
				_self.add_operator_tarif(operator_gid);
				return false;
			});
			
			$(this).find('.remove_link').bind('click', function(){
				_self.delete_operator_tarif($(this));
				return false;
			});
			
			$(this).find('.edit_link').bind('click', function(){
				_self.get_operator_form(operator_gid);
				return false;
			});

			$(this).find('.delete_link').bind('click', function(){
				_self.delete_operator(operator_gid);
				return false;
			});

			$(this).find('.activate_link').bind('click', function(){
				_self.activate_operator(operator_gid);
				return false;
			});

			$(this).find('.deactivate_link').bind('click', function(){
				_self.deactivate_operator(operator_gid);
				return false;
			});
		});
	}
	
	this.reload_block = function(){
		var url = _self.properties.siteUrl + _self.properties.blockUrl;
		$.ajax({
			url: url + _self.properties.systemGid, 
			type: 'GET',
			cache: false,
			success: function(data){
				$("#"+_self.properties.blockId).html(data);
				_self.set_sortable();
			}
		});
	}

	this.update_sorting = function(){
		var url =_self.properties.siteUrl + _self.properties.sortUrl;
		var data = new Object;
		$("#"+_self.properties.sortId+' > li').each(function(i){
			data[$(this).attr('id')] = i+1;
		});
		
		$.ajax({
			url: url + _self.properties.systemGid, 
			type: 'POST',
			dataType: 'json',
			data: ({sorter: data}), 
			cache: false,
			success: function(data){
				_self.properties.errorObj.show_error_block(data.success, 'success');
			}
		});
	}

	this.get_operator_form = function(operator_gid){
		operator_gid = operator_gid || '';
		
		var url = _self.properties.siteUrl + _self.properties.formUrl;
		$.ajax({
			url: url + _self.properties.systemGid + '/' + operator_gid, 
			cache: false,
			success: function(data){
				_self.properties.contentObj.show_load_block(data);
				$('#'+_self.properties.cancelBtnId).bind('click', function(){
					_self.properties.contentObj.hide_load_block();
					return false;
				});
				$('#'+_self.properties.saveBtnId).bind('click', function(){
					_self.save_operator_form_data(operator_gid);
				});
			}
		});
	}

	this.save_operator_form_data = function(operator_gid){
		var data = new Object;
		$("#"+_self.properties.formId+' input').each(function(i){
			data[$(this).attr('name')] = $(this).val();
		});
		
		var url = _self.properties.siteUrl + _self.properties.saveUrl;
		
		$.ajax({
			url: url +_self.properties.systemGid + '/' + operator_gid, 
			type: 'POST',
			data: ({data: data}), 
			dataType: 'json',
			cache: false,
			success: function(data){
				if(typeof(data.error) !== 'undefined'){
					_self.properties.errorObj.show_error_block(data.errors, 'error');
				}else{
					_self.properties.contentObj.hide_load_block();
					_self.reload_block();
				}
				
			}
		});
	}
	
	this.add_operator_tarif = function(operator_gid){
		var tarif_block = $('#'+operator_gid+'_tarif_block>div').clone(true);
		$('#'+operator_gid+'_tarifs_block').append(tarif_block);
	}
	
	this.delete_operator_tarif = function(item){
		delete item.parent().remove();
	}

	this.delete_operator = function(operator_gid){
		var url = _self.properties.siteUrl + _self.properties.deleteUrl;
		$.ajax({
			url:  url + _self.properties.systemGid + '/' + operator_gid, 
			cache: false,
			success: function(data){
				_self.reload_block();
			}
		});
	}

	this.activate_operator = function(operator_gid){
		var url = _self.properties.siteUrl + _self.properties.activateUrl;
		$.ajax({
			url:  url + _self.properties.systemGid + '/' + operator_gid + '/1', 
			cache: false,
			success: function(data){
				_self.reload_block();
			}
		});
	}
	
	this.deactivate_operator = function(operator_gid){
		var url = _self.properties.siteUrl + _self.properties.activateUrl;
		$.ajax({
			url:  url + _self.properties.systemGid + '/' + operator_gid + '/0', 
			cache: false,
			success: function(data){
				_self.reload_block();
			}
		});
	}

	_self.Init(optionArr);
}
