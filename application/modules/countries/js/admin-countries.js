function adminCountries(optionArr){
	this.properties = {
		siteUrl: '',
		installBlockID: 'region_reload',
		overallBarID: 'overall_bar',
		country_code: '',
		urlInstallCities: 'admin/countries/ajax_install_cities/',
		reloadTimeout: 600,
		regions: [],
		current_region_index: 0,
		progress_step: 0
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
	}

	this.start_city_install = function(){
		if(_self.properties.regions.length > 0){
			_self.properties.progress_step = Math.floor(100/_self.properties.regions.length);
			_self.city_install(0);
		}
	}

	this.city_install = function(index){
		var region_code = _self.properties.regions[index];
		var url = _self.properties.siteUrl+_self.properties.urlInstallCities+_self.properties.country_code+'/'+region_code;

		$.ajax({
			url: url, 
			cache: false,
			success: function(data){
				$("#region_"+_self.properties.current_region_index).addClass('installed');
				_self.properties.current_region_index++;

				var current_progress = Math.round(_self.properties.current_region_index*100/_self.properties.regions.length);
				_self.update_overall_progress(current_progress);
				if(_self.properties.current_region_index < _self.properties.regions.length){
					setTimeout( function(){
						_self.city_install(_self.properties.current_region_index);
					}, _self.properties.reloadTimeout);
				}else{
					_self.update_overall_progress(100);
					$('#back_btn').show();
				}
			}
		});
	}

	this.update_overall_progress = function(progress){
		if(progress > 100) progress = 100;
		$("#"+_self.properties.overallBarID+' div.bar').html(progress + '%');
		$("#"+_self.properties.overallBarID+' div.bar').css('width', progress + '%');
	}

	_self.Init(optionArr);
}
