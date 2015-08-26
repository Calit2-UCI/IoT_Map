function locationPopUp(optionArr){
	this.properties = {
		siteUrl: '',
		rand: '', 
		id_country: '',
		id_region: '',
		id_city: '',
		load_country_link: 'countries/ajax_get_countries',
		load_region_link: 'countries/ajax_get_regions/',
		load_city_link: 'countries/ajax_get_cities/',
		load_form: 'countries/ajax_get_form/',
		load_data: 'countries/ajax_get_data/',
		select_type: 'city',
		id_main: '',
		id_span: '',
		id_open: '',
		id_hidden_country: '',
		id_hidden_region: '',
		id_hidden_city: '',
		id_items: 'country_select_items',
		id_back: 'country_select_back',
		id_clear: 'country_select_clear',
		id_close: 'country_select_close',
		id_search: 'city_search',
		id_city_page: 'city_page',
		contentObj: new loadingContent({loadBlockWidth: '680px', closeBtnPadding: 15})
	}
	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.properties.id_main = 'country_select_'+_self.properties.rand;
		_self.properties.id_span = 'country_text_'+_self.properties.rand;
		_self.properties.id_open = 'country_open_'+_self.properties.rand;
		_self.properties.id_hidden_country = 'country_hidden_'+_self.properties.rand;
		_self.properties.id_hidden_region = 'region_hidden_'+_self.properties.rand;
		_self.properties.id_hidden_city = 'city_hidden_'+_self.properties.rand;

		$('#'+_self.properties.id_open).bind('click', function(){
			if(_self.properties.select_type == 'city'){
				if(_self.properties.id_region || _self.properties.id_city){
					_self.open_form('city', _self.properties.id_region);
				}else if(_self.properties.id_country){
					_self.open_form('region', _self.properties.id_country);
				}else{
					_self.open_form('country');
				}
			}else if(_self.properties.select_type == 'region'){
				if(_self.properties.id_country || _self.properties.id_region){
					_self.open_form('region', _self.properties.id_country);
				}else{
					_self.open_form('country');
				}
			}else if(_self.properties.select_type == 'country'){
				_self.open_form('country');
			}
			return false;
		});
	}

	this.open_form = function(type, variable){
		var url =  _self.properties.siteUrl+_self.properties.load_form+type;
		if(variable){
			url += '/'+variable;
		}
		$.ajax({
			url: url, 
			cache: false,
			success: function(data){
				_self.properties.contentObj.show_load_block(data);
				$('#'+_self.properties.id_clear).unbind().bind('click', function(){
					_self.clearBox();
				});
				if(type == 'country'){
					_self.load_countries();
				}else if(type == 'region'){
					_self.load_regions(variable);
					$('#'+_self.properties.id_back).unbind().bind('click', function(){
						_self.open_form('country', 0);
						return false;
					});
				}else if(type == 'city'){
					_self.load_cities(variable, '', 1);
					$('#'+_self.properties.id_back).unbind().bind('click', function(){
						_self.open_form('region', _self.properties.id_country);
						return false;
					});
					$('#'+_self.properties.id_search).unbind().bind('keyup', function(){
						_self.load_cities(variable, $(this).val(), 1);
					});
				}
				$('#' + _self.properties.id_close).bind('click', function() {
					_self.properties.contentObj.hide_load_block();
				});
			}
		});
	}

	this.load_countries = function(){
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_country_link,
			dataType: 'json',
			cache: false,
			success: function(data){
				$('#'+_self.properties.id_items).unbind();
				$('#'+_self.properties.id_items).empty();
				for(var id in data.items ){
					$('#'+_self.properties.id_items).append('<li index="'+data.items[id].code+'">'+data.items[id].name+'</li>');
				}
				$('#'+_self.properties.id_items+' li').bind('click', function(){
					_self.set_values('country', $(this).attr('index'), $(this).text(), data);
					if(_self.properties.select_type == 'country'){
						_self.properties.contentObj.hide_load_block();
					}else{
						_self.open_form('region', $(this).attr('index'));
					}
				});
			}
		});
	}

	this.load_regions = function(id_country){
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_region_link + id_country,
			dataType: 'json',
			cache: false,
			success: function(data){
				$('#'+_self.properties.id_items).unbind();
				$('#'+_self.properties.id_items).empty();
				for(var id in data.items ){
					$('#'+_self.properties.id_items).append('<li index="'+data.items[id].id+'">'+data.items[id].name+'</li>');
				}
				$('#'+_self.properties.id_items+' li').bind('click', function(){
					_self.set_values('region', $(this).attr('index'), $(this).text(), data);
					if(_self.properties.select_type == 'region'){
						_self.properties.contentObj.hide_load_block();
					}else{
						_self.open_form('city', $(this).attr('index'));
					}
				});
			}
		});
	}

	this.load_cities = function(id_region, search, page){
		if(search != ''){
			var ajax_type = 'POST';
			var send_data = {search: search};
		}else{
			var ajax_type = 'GET';
			var send_data = {};
		}

		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_city_link + id_region + '/' + page,
			dataType: 'json',
			type: ajax_type,
			data: send_data,
			cache: false,
			success: function(data){
				$('#'+_self.properties.id_items).unbind();
				$('#'+_self.properties.id_items).empty();
				for(var id in data.items ){
					$('#'+_self.properties.id_items).append('<li index="'+data.items[id].id+'">'+data.items[id].name+'</li>');
				}

				_self.generate_city_pages(data.pages, data.current_page, search);
				$('#'+_self.properties.id_items+' li').bind('click', function(){
					_self.set_values('city', $(this).attr('index'), $(this).text(), data);
					_self.properties.contentObj.hide_load_block();
				});
			}
		});
	}

	this.set_values = function(type, variable, value, data){
		var string_value = "";
		if(type == 'country'){
			$('#'+_self.properties.id_hidden_country).val(variable.toString()).change();
			_self.properties.id_country = variable.toString();

			$('#'+_self.properties.id_hidden_region).val(0).change();
			_self.properties.id_region = 0;

			$('#'+_self.properties.id_hidden_city).val(0).change();
			_self.properties.id_city = 0;

			string_value = value;

		}else if(type == 'region'){

			$('#'+_self.properties.id_hidden_region).val(variable).change();
			_self.properties.id_region = variable;

			$('#'+_self.properties.id_hidden_city).val(0).change();
			_self.properties.id_city = 0;

			string_value = data.country.name+', '+value;

		}else if(type == 'city'){

			$('#'+_self.properties.id_hidden_city).val(variable).change();
			_self.properties.id_city = variable;

			string_value = data.country.name+', '+data.region.name+', '+value;
		}

		if(string_value == '') string_value = '...';
		$('#'+_self.properties.id_span).text(string_value);
	}
	
	this.set_values_external = function(type, variable){
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_data + type + '/' + variable,
			dataType: 'json',
			cache: false,
			success: function(data){
				if(type == 'country'){
					_self.set_values(type, variable, data.country.name, data);
				}else if(type == 'region'){
					_self.set_values(type, variable, data.region.name, data);
				}else if(type == 'city'){
					_self.set_values(type, variable, data.city.name, data);
				}
			}
		});
		
	}

	this.generate_city_pages = function(pages, current_page, search){
		$('#'+_self.properties.id_city_page+' a').unbind();
		$('#'+_self.properties.id_city_page).empty();
		if(pages > 1){
			for(var i=1; i<=pages; i++){
				if(i == current_page){
					$('#'+_self.properties.id_city_page).append('<strong>'+i+'</strong>');
				}else{
					$('#'+_self.properties.id_city_page).append('<a href="#">'+i+'</a>');
				}
			}
			$('#'+_self.properties.id_city_page+' a').bind('click', function(){
				_self.load_cities(_self.properties.id_region, search, $(this).text());
				return false;
			});
		}
	}
	
	this.clearBox = function(){
		$('#'+_self.properties.id_span).text('...');
		$('#'+_self.properties.id_hidden_country).val('');
		_self.properties.id_country = '';
		$('#'+_self.properties.id_hidden_region).val('');
		_self.properties.id_region = '';
		$('#'+_self.properties.id_hidden_city).val('');
		_self.properties.id_city = '';
		_self.properties.contentObj.hide_load_block();
	}

	_self.Init(optionArr);
}
