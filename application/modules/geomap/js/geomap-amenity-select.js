function geomapAmenitySelect(optionArr){ 
	this.properties = {
		siteUrl: '',
		rand: '', 
		amenities: {},
		load_form: 'admin/geomap/ajax_get_amenity_form/',
		load_data: 'admin/geomap/ajax_get_amenity_data/',
		id_main: '',
		id_span: '',
		id_text: '',
		id_open: '',
		id_hidden_amenity: '',
		id_items: 'amenity_select_items',
		id_back: 'amenity_select_back',
		id_close: 'amenity_close_link',
		id_left: 'amenity_max_left_block',
		hidden_name: 'id_amenity',
		raw_data: {},				
		max: 5,
		gid: '',
		output: 'max',

		errors: {
			max_items_reached: 'Max items count is reached'
		},
		
		contentObj: new loadingContent({loadBlockWidth: '680px', closeBtnClass: 'load_content_controller_close', closeBtnPadding: 15})
	}
	
	this.errors = {}
	
	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.properties.id_main = 'amenity_select_'+_self.properties.rand;
		_self.properties.id_span = 'amenity_text_'+_self.properties.rand;
		_self.properties.id_text = 'amenity_list_'+_self.properties.rand;
		_self.properties.id_open = 'amenity_open_'+_self.properties.rand;
		_self.properties.id_hidden_amenity = 'amenity_hidden_'+_self.properties.rand;

		$('#'+_self.properties.id_open).unbind().bind('click', function(){
			_self.open_form();
			return false;
		});

		$(document).off('click', '#'+_self.properties.id_back).on('click', '#'+_self.properties.id_back, function(){
			_self.clear_selection();
			return false;
		});

		//// set action onclick on amenity
		$(document).off('click', '#'+_self.properties.id_items+' li').on('click', '#'+_self.properties.id_items+' li', function(){
			_self.set_value($(this).attr('index'), 'toggle');
			return false;
		});
			
		//_self.update_selected_count();		
	}

	this.open_form = function(){
		var url =  _self.properties.siteUrl+_self.properties.load_form + _self.properties.gid + '/' + _self.properties.max;

		$.ajax({
			url: url, 
			cache: false,
			success: function(data){
				_self.properties.contentObj.show_load_block(data);

				_self.load_amenities();

				$('#'+_self.properties.id_close).unbind().bind('click', function(){
					_self.properties.contentObj.hide_load_block();
					return false;
				});
				
				_self.update_selected_count();
			}
		});
	}
	
	this.load_amenities = function(){
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_data + _self.properties.gid,
			dataType: 'json',
			cache: false,
			success: function(data){				
				for(var id in data.amenities){
					//// save in raw
					_self.properties.raw_data[data.amenities[id].id] = data.amenities[id];
					$('#'+_self.properties.id_items).append('<li index="'+data.amenities[id].id+'">'+data.amenities[id].name+'</li>');
					if(_self.properties.amenities[data.amenities[id].id] == 1){
						_self.set_value(data.amenities[id].id, 'no-toggle');
					}
				}
			}
		});
	}

	this.set_value = function(value, set_type){
		/// если уже установлено - то unset_value и return
		if(set_type == 'toggle' && $('#sel_'+_self.properties.rand+'_'+value).length > 0){
			_self.unset_value(value);
			return;
		}

		/// если max = 1 то unset все в amenities
		if(_self.properties.max == 1){
			for(var id in _self.properties.amenities){
				_self.unset_value(id);
			}
		}
		
		/// проверяем на возможность выбора
		var sum = _self.get_all_sum();
		if(_self.properties.max - sum < 1 && _self.properties.amenities[value] != 1){
			return;
		}
		
		/// устанавливаем поле hidden
		if(_self.properties.max == 1)
			var hidden_name = _self.properties.hidden_name;
		else
			var hidden_name = _self.properties.hidden_name+'[]';

		
		if(!$('#sel_'+_self.properties.rand+'_'+value).length){
			$('#'+_self.properties.id_main).append('<input type="hidden" name="'+hidden_name+'" value="'+value+'" id="sel_'+_self.properties.rand+'_'+value+'" >');
			$('#sel_'+_self.properties.rand+'_'+value).change();
		}

		/// добавляем в категории
		_self.properties.amenities[value] = 1;
		
		/// помечаем как выделенный
		$('#'+_self.properties.id_items+' li[index='+value+']').addClass('selected');
		
		_self.update_selected_count();
		
		return false;
	}
	
	this.unset_value = function(value){
		
		/// снимаем поле hidden
		$('#sel_'+_self.properties.rand+'_'+value).remove();
		
		/// удаляем из категорий
		_self.properties.amenities[value] = 0;

		/// снимаем выделение
		$('#'+_self.properties.id_items+' li[index='+value+']').removeClass('selected');
		
		_self.update_selected_count();

		return false;
	}
	
	this.clear_selection = function(){
		for(i in _self.properties.amenities){
			if(_self.properties.amenities[i] == 1){
				_self.unset_value(i);
			}
		}
	}

	this.update_selected_count = function(){
		var sum = _self.get_all_sum();
		if(_self.properties.max == '1'){
			var hdr = $('#'+_self.properties.id_items+' li.selected:first').text();
			$('#'+_self.properties.id_span).html(hdr);
		}else{
			$('#'+_self.properties.id_span).html(sum);
			var text = _self.get_all_text();
			$('#'+_self.properties.id_text).html(text);
		}
		$('#'+_self.properties.id_left).html(_self.properties.max - sum);
	}	
	
	this.get_all_sum = function(){
		var sum = 0;
		for(id in _self.properties.amenities){ 
			if(_self.properties.amenities[id] != 1) continue;
			sum += 1;
		}
		return sum;
	}
	
	this.get_all_text = function(){
		var amenities = [];
		for(id in _self.properties.amenities){ 
			if(_self.properties.amenities[id] != 1) continue;
			amenities.push(_self.properties.raw_data[id].name);
		}
		return amenities.join(', ');
	}
		
	this.sum = function(arr){
		var sum=0;
		for(i in arr){ sum += parseInt(arr[i]);}
		return sum;
	}
	_self.Init(optionArr);
}


