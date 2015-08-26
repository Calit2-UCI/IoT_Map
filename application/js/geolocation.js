function getUserLocation(optionArr){
	
	var _self = this;
	
	this.properties = {
		// Process settings
		gl_alert: 'Geolocation is not supported by this browser.',
		gl_alert_storage: 'Sorry! No Web Storage support..',
		gl_output: 'info_block',
		gl_test: false,
		errorObj: new Errors()
	};
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.get_Location();
	}
	
	this.get_Location = function (){
		if (navigator.geolocation){
			navigator.geolocation.getCurrentPosition(function(position) {
				_self.geo_success(position);
			});
		}else{ 
			_self.properties.errorObj.show_error_block(_self.properties.gl_alert, 'error');
		}
	}
	
	this.geo_success = function (position){
		
		var latitude  = position.coords.latitude;
		var longitude = position.coords.longitude;
		
		if(_self.properties.gl_test){
			var img = new Image();
			img.src = "http://maps.googleapis.com/maps/api/staticmap?center=" + latitude + "," + longitude + "&zoom=13&size=300x300&sensor=false";
			$('#'+_self.properties.gl_output).append(img);
			$('#'+_self.properties.gl_output).show();
		}
		
		if(typeof(Storage) !== "undefined") {
			// Code for localStorage/sessionStorage.
			var userLocation = latitude+";"+longitude;
			if(localStorage.getItem("userLocation") != userLocation){
				localStorage.setItem("userLocation", userLocation);
			}
		} else {
			_self.properties.errorObj.show_error_block(_self.properties.gl_alert_storage, 'error');
		}
	}
	
	_self.Init(optionArr);
}

geo = new getUserLocation();
