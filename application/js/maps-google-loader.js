function GoogleMapLoader(optionArr){
	
	this.properties = {
		map: '',
		rand: '',
		map_container: '',
		lang: '',
		try_count: 0,

		MAPS_TYPES: [],
		MAP_WIDTH: 600,
		MAP_HEIGHT: 400,

		default_map_type: '',
		default_view_type: '1',
		default_zoom: 10,

		lat: '',
		lon: '',
		drag_listener: false,
		zoom_listener: false,
		type_listener: false,
		markers_data: {},

		default_marker_data: {},
		default_marker: [],
		markers: {},
		temp_point: ''
	}

	var _self = this;

	this.Init = function(options){
		console.log('init1');
		_self.properties = $.extend(_self.properties, options);
		console.log('init2');
		_self.init_params();
		console.log('init3');
		_self.map_loader();
		console.log('init4');
	}
	
	this.init_params = function(){
		_self.properties.map_container = document.getElementById("map_container_" + _self.properties.rand);
	}
	
	this.get_map = function(){
		_self.properties.MAPS_TYPES[1] = G_NORMAL_MAP;
		_self.properties.MAPS_TYPES[2] = G_SATELLITE_MAP;
		_self.properties.MAPS_TYPES[3] = G_HYBRID_MAP;
		_self.properties.MAPS_TYPES[4] = G_PHYSICAL_MAP;
		
		_self.properties.default_map_type = _self.properties.MAPS_TYPES[_self.properties.default_view_type];

		if (GBrowserIsCompatible()){
			_self.properties.map = new google.maps.Map2(_self.properties.map_container, { size: new GSize(_self.properties.MAP_WIDTH, _self.properties.MAP_HEIGHT) });

			var customUI = _self.properties.map.getDefaultUI();
			customUI.controls.menumaptypecontrol = true;
			_self.properties.map.setUI(customUI);

			_self.properties.map.setMapType(_self.properties.default_map_type);
			_self.properties.map.setCenter(new GLatLng(_self.properties.lat, _self.properties.lon), _self.properties.default_zoom);
			_self.properties.map.enableInfoWindow();


			GEvent.addListener(_self.properties.map, "zoomend", function(oldZ, newZ){
					if(_self.properties.zoom_listener != false){
						_self.properties.zoom_listener(newZ);
					}
			});

			GEvent.addListener(_self.properties.map, "maptypechanged", function(){
				var curr_map_type = _self.properties.map.getCurrentMapType();
				var map_name = curr_map_type.getName();
				var map_type_int = 1;
				if(map_name == G_NORMAL_MAP.getName()){
					map_type_int = 1;
				}
				if(map_name == G_SATELLITE_MAP.getName()){
					map_type_int = 2;
				}
				if(map_name == G_HYBRID_MAP.getName()){
					map_type_int = 3;
				}
				if(map_name == G_PHYSICAL_MAP.getName()){
					map_type_int = 4;
				}
				if(_self.properties.type_listener != false){
					_self.properties.type_listener(map_type_int);
				}
			});
		
			var markers_count = _self.countProperties(_self.properties.markers_data);
			if(markers_count > 0){
				var first = 0;
				for(var marker in _self.properties.markers_data){
					if(first == 0){
						default_marker_data = {lat: _self.properties.markers_data[marker].lat, lon: _self.properties.markers_data[marker].lon, gid: _self.properties.markers_data[marker].gid, html: _self.properties.markers_data[marker].html, dragging: _self.properties.markers_data[marker].dragging};
					}
					first++;

					//// add marker to the map
					_self.properties.temp_point = new GLatLng(_self.properties.markers_data[marker].lat, _self.properties.markers_data[marker].lon);
					_self.properties.markers[marker] = new GMarker(_self.properties.temp_point, {draggable: _self.properties.markers_data[marker].dragging});
					_self.properties.markers[marker].gid = _self.properties.markers_data[marker].gid;
					if(_self.properties.markers_data[marker].html != ''){
						_self.properties.markers[marker].bindInfoWindowHtml(_self.properties.markers_data[marker].html);
						_self.properties.markers[marker].openInfoWindowHtml(_self.properties.markers_data[marker].html);
						GEvent.addListener(_self.properties.markers[marker], "dragstart", function() {
							_self.properties.markers[marker].closeInfoWindow();
						});
						GEvent.addListener(_self.properties.markers[marker], "dragend", function(point) {
							_self.properties.markers[marker].openInfoWindowHtml(_self.properties.markers_data[marker].html);
						});
					}

					if(_self.properties.markers_data[marker].dragging && _self.properties.drag_listener){
						GEvent.addListener(_self.properties.markers[marker], "dragend", function(point) {
							_self.properties.drag_listener(this.gid, point.lat(), point.lng());
						});
					}
					_self.properties.map.addOverlay(_self.properties.markers[marker]);
				}
			}

			_self.properties.temp_point = new GLatLng(default_marker_data.lat, default_marker_data.lon);
			_self.properties.map.setCenter(_self.properties.temp_point, _self.properties.default_zoom);
			
		}
		
	}

	this.set_zoom = function(zoom){
		zoom = parseInt(zoom);
		if(zoom){
			_self.properties.map.setZoom(zoom);
		}
	}

	this.set_view_type = function(type_num){
		if(_self.properties.MAPS_TYPES[type_num]){
			_self.properties.map.setMapType(_self.properties.MAPS_TYPES[type_num]);
		}
	}

	this.set_marker_position = function(gid, lat, lon){
		for(var m in _self.properties.markers){
			if(_self.properties.markers[m].gid == gid){
				var point = new GLatLng(lat, lon);
				_self.properties.markers[m].setLatLng(point);
				_self.properties.markers[m].closeInfoWindow();
				_self.properties.markers[m].openInfoWindowHtml(_self.properties.markers[m].html);
			}
		}
	}

	this.countProperties = function(obj) {
		var count = 0;
		for(var prop in obj) {
			if(obj.hasOwnProperty(prop))  ++count;
		}
		return count;
	}
	
	this.map_loader = function(){
//		alert('new');
		if(typeof window.google == 'object'){
			console.log(window.google);
			if(_self.properties.lang != ''){
				google.load("maps", "2.x", {"language" : _self.properties.lang, "callback": _self.get_map});
			}else{
				google.load("maps", "2.x", {"callback": _self.get_map});
			}
		}else{
			if(_self.properties.try_count < 100){
				_self.properties.try_count++;
				setTimeout(_self.map_loader, 500);
			}
		}
	}
	
	_self.Init(optionArr);	
}
