function adminCountriesSelected(optionArr){
	this.properties = {
		siteUrl: '',
		installBlockID: 'countries_reload',
		overallBarID: 'overall_bar',
		country_code: '',
		urlInstallCountries: 'admin/countries/ajax_install_country/',
		reloadTimeout: 600,
		countries: [],
		regions: [],
		regions_list: '',
		current_country_index: 0,
		current_region_index: 0,
		progress_step: 0
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.properties.regions = jQuery.parseJSON(_self.properties.regions_list);
		_self.properties.countries = jQuery.parseJSON(_self.properties.countries);
		
	}

	this.start_country_install = function(){
		if(_self.properties.countries.length > 0){
			_self.properties.progress_step = Math.floor(100/_self.properties.countries.length);
			_self.country_install(0);
		}
	}

	this.country_install = function(index){
		var country_code = _self.properties.countries[index];
		var url = _self.properties.siteUrl+_self.properties.urlInstallCountries+country_code;
		index++;
		$.ajax({
			url: url, 
			cache: false,
			success: function(data){
				$("#country_"+index).addClass('installed');

				var current_progress = Math.round(index*100/_self.properties.countries.length);
				if(index <= _self.properties.countries.length){
					setTimeout( function(){
						var region_install;
						var subSiteUrl = _self.properties.siteUrl;
						var subCountryCode = country_code;
						var subRegions = _self.properties.regions[country_code];
						region_install = new adminRegionsSelected({
							siteUrl: subSiteUrl,
							regions: subRegions,
							country_code: subCountryCode,
						});
						region_install.start_city_install(function(){
							_self.update_overall_progress(current_progress);
							_self.country_install(index);
						});
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

function adminRegionsSelected(optionArr){
	this.properties = {
		siteUrl: '',
		installBlockID: 'region_reload',
		overallBarID: 'regions_bar',
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

	this.start_city_install = function(callback){
		if(_self.properties.regions.length > 0){
			_self.properties.progress_step = Math.floor(100/_self.properties.regions.length);
			_self.city_install(0,callback);
		}
	}

	this.city_install = function(index, callback){
		var region_code = _self.properties.regions[index];
		var url = _self.properties.siteUrl+_self.properties.urlInstallCities+_self.properties.country_code+'/'+region_code;
		index++;

		$.ajax({
			url: url, 
			cache: false,
			success: function(data){
				$("#region_"+_self.properties.country_code+"_"+index).addClass('installed');

				var current_progress = Math.round(index*100/_self.properties.regions.length);
				_self.update_overall_progress(current_progress);
				if(index < _self.properties.regions.length){
					setTimeout( function(){
						_self.city_install(index,callback);
					}, _self.properties.reloadTimeout);
				}else{
					_self.update_overall_progress(100);
					if(typeof callback == 'function'){
						callback();
					} else {
						$('#back_btn').show();
					}
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
