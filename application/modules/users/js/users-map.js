function usersMap(optionArr) {
	this.properties = {
		siteUrl: '', 
		user_id: 0,
		view_ajax_url: 'users/ajax_view_map_user_location',
		link_view_map_id: 'view_map_link',
		error_object: new Errors(),
		window_object: new loadingContent({
							loadBlockWidth: '642px',
							loadBlockTopType: 'top',
							loadBlockTopPoint: 100,
							closeBtnClass: 'w',
						}),
	};

	var _self = this;

	this.Init = function (options) {
		_self.prop = $.extend(_self.properties, options);
		_self.bindEvents();
	};
	
	this.bindEvents = function () {
		var load_map_scripts = 1;
		
		$('#' + _self.prop.link_view_map_id).on('click', function(){
			$.ajax({
				url: _self.prop.siteUrl + _self.prop.view_ajax_url,
				type: 'POST',
				dataType : 'json',
				data: {id: _self.prop.user_id, load_map_scripts: load_map_scripts}, 
				cache: false,
				success: function(data){				
					if (data.errors != "") {
						_self.prop.error_object.show_error_block(data.errors, 'error');
					} else if (data.html != "") {	
						$.pjax.disable();
						load_map_scripts = 0;
						_self.prop.window_object.show_load_block(data.html);
					}
				}	
			});
		});
		
		$('input[name=id_country]').on('change', function(){
			_self.checkAddressUpdated();
		});
	};
	
	this.checkAddressUpdated = function(){
		var country = $('input[name=id_country]').val();
		var region = $('input[name=id_region]').val();
		var city = $('input[name=id_city]').val();
		
		if (country == '') {
			_self.setCoordinates(0, 0);
			return;
		}

		var country_name = '';
		var region_name = '';
		var city_name = '';
		var locations = $('input[name=region_name]').val().split(',');

		if (typeof(locations[0]) != 'undefined') {
			country_name = locations[0];
		}
		if (typeof(locations[1]) != 'undefined') {
			region_name = locations[1];
		}
		if (typeof(locations[2]) != 'undefined') {
			city_name = locations[2];
		}

		_self.updateCoordinates(country_name, region_name, city_name);
	}
		
	this.updateCoordinates = function(country, region, city){
		if (typeof(geocoder) != 'undefined') {
			var location = geocoder.getLocationFromAddress(country, region, city);
			geocoder.geocodeLocation(location, function(latitude, longitude){
				_self.setCoordinates(latitude, longitude);
			});	
		}
	}
	
	this.setCoordinates = function(latitude, longitude) {
		$('#lat').val(latitude);
		$('#lon').val(longitude);
	}

	_self.Init(optionArr);

}