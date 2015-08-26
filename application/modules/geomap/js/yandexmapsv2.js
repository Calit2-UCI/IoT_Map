function YandexMapsv2(defOptions){
	this.prop = {
		site_url: '',
		save_location_url: '/admin/admin_settings.php',
		map_container: 'map_container',
		routes_container: '',
		amenities: [],
		icon: null,
		use_smart_zoom: false,
		use_searchbox: false,
		use_tools: false,
		use_router: false,
		use_type_selector: false,
		use_clusterer: false,
		use_click_zoom: false,
		default_zoom: 10,
		default_map_type: 2,
		lat: 56.614607,
		lon: 47.863757,		
		width: 0,
		height: 0,
		geocode_listener: false,
		zoom_listener: false,
		type_listener: false,
	}
	
	this.MAP_TYPES = [
		'yandex#map',
		'yandex#satellite',
		'yandex#hybrid',
		'yandex#publicMap',
		'yandex#publicMapHybrid',
	];
	
	this.is_loaded = false;
	
	this.min_zoom = 1;
	this.max_zoom = 19;
	
	this.map = null;
	this.map_container = null;	
	
	this.markers = [];
	this.clusterer = null;
	this.route = null;
	this.zoom = null;
	this.bounds = null;
	this.wait_requests = 0;
	
	this.amenities = {};
	
	var _self = this;
	
	this.init = function(options){
		_self.prop = $.extend(_self.prop, options);
				
		_self.prop.lat = parseFloat(_self.prop.lat);
		_self.prop.lon = parseFloat(_self.prop.lon);
		
		_self.map_container = $('#'+_self.prop.map_container);
		
		if(!_self.map_container.length){
			alert('map container not exists!');
			return false;
		}
		
		_self.map_container.html('');
		
		_self.map_container.css({'width': _self.prop.width, 'height': _self.prop.height});
				
		try{
			_self.map = new ymaps.Map(_self.map_container.attr('id'), {
							behaviors: ['default', 'scrollZoom'],
							center: [_self.prop.lat, _self.prop.lon],
							type: _self.MAP_TYPES[_self.prop.default_map_type-1], 
							zoom: _self.prop.default_zoom,	
						});
		} catch(e) {
			alert('map initialize is failed!');
			return false;
		}
		
		_self.map.controls.add('smallZoomControl');
		
		if(_self.prop.use_type_selector){
			_self.map.controls.add('typeSelector');
		}
		
		if(_self.prop.use_tools){		
			_self.map.controls.add(new ymaps.control.MapTools());
		}
		
		if(_self.prop.use_searchbox){		
			_self.map.controls.add(new ymaps.control.SearchControl({noPopup: true, position:{left:5, bottom:25}, width:220}));
		}
		
		if(_self.prop.use_clusterer){
			_self.clusterer = new ymaps.Clusterer();
			if(!_self.prop.use_click_zoom){
				_self.clusterer.options.set('clusterDisableClickZoom', true);
			}
			_self.map.geoObjects.add(_self.clusterer);
		}
		
		_self.min_zoom = Math.max(_self.min_zoom, _self.map.zoomRange.getCurrent()[0]);
		_self.max_zoom = Math.min(_self.max_zoom, _self.map.zoomRange.getCurrent()[1]);
		
		if(_self.prop.default_zoom < _self.min_zoom){
			_self.zoom = _self.min_zoom;
			_self.setZoom(_self.zoom);
		}else if(_self.prop.default_zoom >= _self.max_zoom){
			_self.zoom = _self.max_zoom;
			_self.setZoom(_self.zoom);
		}else{
			_self.zoom = _self.prop.default_zoom;
		}
		
		if(_self.prop.use_router){
			var routes_container = $('#'+_self.prop.routes_container);
			if(!routes_container.length) 
				_self.prop.use_router = false;
		}
		
		_self.map.events.add('boundschange', function(event){
			if (event.get('newZoom') != event.get('oldZoom')){
				if(_self.prop.zoom_listener != false){
					_self.prop.zoom_listener(event.get('newZoom'));
				}
				_self.zoom = event.get('newZoom');
			}
		});
		
		if(_self.prop.type_listener){
			_self.map.events.add("typechange", function(event){
				if(_self.is_loaded){
					var map_type_int = _self.MAP_TYPES.indexOf(event.get('newType'));					
					_self.prop.type_listener(map_type_int+1);
				}
			});
		}
		
		//_self.renderControl();
		
		_self.is_loaded = true;
	}
	
	this.renderControl = function(){
		var controls = 
			'<div style="position: absolute; right:5px; top:5px; background:#fff; opacity:0.5; z-index:1000;">'+	
			'<select id="map_type" style="margin:5px;">';
		
		var default_type = _self.map.getType();
		var a_types = [];
	
		for(var i in _self.MAP_TYPES){		
			if(!_self.MAP_TYPES[i]) continue;
			_self.map.setType(_self.MAP_TYPES[i]);		
			
			var name = _self.MAP_TYPES[i];
			//if(!$.inArray(name, a_types)) continue;
			a_types.push(name);
			controls += '<option value="'+i+'"'+(default_type == _self.MAP_TYPES[i] ? 'selected="selected"' : '')+'>'+name+'</option>';			
		}
	
		if(!$.inArray(name, a_types))  default_type = _self.MAP_TYPES[1];
		_self.map.setType(default_type);
			
		controls += 
			'</select>'+
			'<div id="zoom_out" style="float:right; width:30px; height:30px;text-align:center;vertical-align:middle;margin:5px;border:1px solid #ccc; cursor:pointer;">-</div>'+
			'<div style="float:right; width:30px; height:30px; text-align:center;vertical-align:middle;margin:5px;border:1px solid #ccc;cursor:pointer;" id="zoom_in">+</div>'+
			'</div>';
		$('#'+_self.prop.map_container).css('position', 'relative').append(controls);
		$('#zoom_in').bind('click', function(){
			if(_self.zoom < _self.max_zoom)	_self.setZoom(_self.zoom+1);
		});
		$('#zoom_out').bind('click', function(){
			if(_self.zoom > _self.min_zoom) _self.setZoom(_self.zoom-1);
		});
		$('#map_type').bind('change', function(){
			_self.map.setMapType(_self.MAP_TYPES[$(this).val()]);
		});
	}
	
	this.addMarker = function(latitude, longitude, options){
		var marker = new Marker(_self, latitude, longitude, options);
		_self.markers.push(marker);
		
		if(!_self.wait_requests){
			_self.setSmartCenter();
			//_self.addAmenities();	
		}
	}

	this.setType = function(index){
		if(!_self.MAP_TYPES[index]) return;
		_self.map.setType(_self.MAP_TYPES[index]);	
	}

	this.getZoom = function(){
		return _self.map.getZoom();
	}
	
	this.setZoom = function(zoomLevel){
		_self.map.setZoom(zoomLevel)
	}
	
	this.changeZoom = function(step){
		_self.zoom = _self.zoom + step;
		_self.setZoom(_self.zoom);
		return _self.zoom;
	}

	this.getCenter = function(){		
		var location = _self.map.getCenter();
		return {latitude: location[0], longitude: location[1]};
	}
	
	this.setCenter = function(latitude, longitude){
		_self.map.setCenter([latitude, longitude]);
	}
	
	this.setCenterFromLocation = function(location){
		this.geocodeLocation(location, {}, _self.setCenter);
	}
	
	this.getMarkersBounds = function(){
		if(!_self.markers.length)
			return [[_self.prop.lat, _self.prop.lon]];
	
		var point1 = _self.markers[0].getLocation();	
		if(_self.markers.length == 1)
			return [[point1.latitude, point1.longitude]];
			
		var point2 = _self.markers[1].getLocation();
		var bounds = [[Math.min(point1.latitude, point2.latitude), Math.min(point1.longitude, point2.longitude)], 
					  [Math.max(point1.latitude, point2.latitude), Math.max(point1.longitude, point2.longitude)]];
		for(var i=2; i<_self.markers.length; i++){
			var location = _self.markers[i].getLocation();
			bounds[0][0] = Math.min(location.latitude, bounds[0][0]);
			bounds[0][1] = Math.min(location.longitude, bounds[0][1]);
			bounds[1][0] = Math.max(location.latitude, bounds[1][0]);
			bounds[1][1] = Math.max(location.longitude, bounds[1][1]);
		}		
		return bounds;
	}
	
	this.setSmartCenter = function(){
		var bounds = _self.getMarkersBounds();		
		if(bounds.length == 2){
			if(_self.prop.use_smart_zoom){	
				_self.map.setBounds(bounds);
			}else{
				var point1 = bounds[1];
				var point2 = bounds[0];
				_self.setCenter(
					(point1[0] != point2[0] ? point2[0] + (point1[0]-point2[0])/2 : point1[0]), 
					(point1[1] != point2[1] ? point2[1] + (point1[1]-point2[1])/2 : point1[1])
				);
			}
		}else{
			_self.setCenter(bounds[0][0], bounds[0][1]);
		}
	}
	
	this.getInfoboxLocation = function(location){
		var point = _self.map.tryLocationToPixel(location);
		if (!point) return null;
		point.x += 5;
		point.y -= 25;		
		return _self.map.tryPixelToLocation(point);
	}

	this.clear = function(){
		if(_self.prop.use_clusterer){
			_self.clusterer.removeAll();
		}
		for(i in _self.markers){
			if(!_self.prop.use_clusterer) _self.removeEntry(_self.markers[i]);
			_self.markers[i] = null;
		}	
		_self.markers = [];
	}
	
	this.remove = function(){
		_self.clear();
		if(_self.prop.use_clusterer) _self.removeEntry(_self.clusterer);
		_self.clusterer = null;
		_self.router_editor = null;
		_self.map = null;
	}
	
	this.getLocationFromAddress = function(country, region, city, address, zip){
		var location = [];
		//if(zip) location.push(zip);		
		if(country) location.push(country);
		if(region) location.push(region);
		if(city) location.push(city);
		if(address) location.push(address);
		return location.join(', ');
	}
	
	this.getControl = function(){
		return _self.map;
	}
	
	this.addEntry = function(obj, no_clusterer){
		no_clusterer = no_clusterer || false;
		if(_self.prop.use_clusterer && !no_clusterer){
			_self.clusterer.add(obj);
		}else{
			_self.map.geoObjects.add(obj);
		}
	}
	
	this.removeEntry = function(obj, no_clusterer){
		no_clusterer = no_clusterer || false;
		if(_self.prop.use_clusterer && !no_clusterer){
			_self.clusterer.remove(obj);
		}else{
			_self.map.geoObjects.remove(obj);
		}
	}
	
	this.createRoute = function(){
		if(!_self.markers.length) return;
		var loc = _self.markers[0].getLocation();
		var bounds = _self.map.getBounds();
		_self.calcRoute([
			[loc.latitude, loc.longitude], 
			[loc.latitude+(bounds[1][0]-bounds[0][0])/10, loc.longitude+(bounds[1][1]-bounds[0][1])/10]
		]);
	}
	
	this.calcRoute = function(routes){
		if(!_self.prop.use_router) return false;
		
		if(!routes.length) return false;
		
		var route = ymaps.route(routes, {mapStateAutoApply: true});
		new ymaps.control.RouteEditor(route, {addWayPoints: true, removeWayPoints: true});
		route.then(function(result){
			result.getPaths().options.set({
				balloonContenBodyLayout: ymaps.templateLayoutFactory.createClass('$[properties.humanJamsTime]'),
				strokeColor: '0000ffff',
				opacity: 0.9,
			});
			result.getWayPoints().options.set({draggable: false});  
			
			_self.route = result;
			
			_self.route.editor.events.add('routeupdate', function(e){ 
				var way = _self.route.getPaths().get(0);
                segments = way.getSegments();
                var content = '';
				for (var i = 0; i < segments.length; i++){
					var street = segments[i].getStreet();
					content += ('' + segments[i].getHumanAction() + (street ? ' ' + street : '') + ', ' + segments[i].getLength() + ' м.,');
					content += '<br>'
				}
				$('#'+_self.prop.routes_conteiner).html(content);
			});			
			
			_self.addEntry(_self.route, true);
			_self.route.editor.start();
			
		}, function(error){
			alert(error.message);
		});
	}
	
	this.deleteRoute = function(){
		_self.route.editor.stop();
		_self.route.editor = null;
		_self.removeEntry(_self.route, true);
		_self.route = null;
	}
	
	this.addAmenities = function(){
		if(!_self.prop.amenities.length) return false;
		
		var point = _self.getCenter();
		ymaps.getZoomRange(_self.map.getType(), [point.latitude, point.longitude]).then(function(zoomRange){
			_self.setZoom(zoomRange[1]);
			_self.wait_requests++;
			_self.geocodeLocation("кафе", {}, _self.addAmenity);
		});			
	}
	
	this.addAmenity = function(latitude, longitude, options){
		var marker = new Marker(_self, latitude, longitude, options);
		_self.markers.push(marker);		
		if(!_self.wait_requests){
			_self.setSmartCenter();
		}
	}
	
	this.moveMarker = function(gid, latitude, longitude){
		for(var i in _self.markers){
			if(_self.markers[i].getGUID() != gid) continue;
			_self.markers[i].move(latitude, longitude);
			break;
		}
		_self.setCenter(latitude, longitude);
	}
	
	this.moveMarkers = function(latitude, longitude){
		for(var i in _self.markers){
			_self.markers[i].move(latitude, longitude);
		}
		_self.setCenter(latitude, longitude);
	}
	
	function Marker(map, latitude, longitude, defOptions){
		this.prop = {
			draggable: false,
			icon: null,
			highlightIcon: null,		
			info: null,
			showInfo: true,
			htmlContent: null,
			state: 'none',
			gid: 'default',
			drag: null,
			dragend: null,
			drag_listener: false,
		};

		this.map = map;
		this.marker = null;
		this.infobox = null;
	
		this.prop.icon = map.prop.icon;
	
		var _self = this;
	
		this.init = function(latitude, longitude, options){
			_self.prop = $.extend(_self.prop, options);
			
			var marker_options = {
				clusterCaption: _self.prop.info,
				hintContent: _self.prop.info,
				balloonContent: _self.prop.info,
			}
			
			_self.marker = new ymaps.Placemark([latitude, longitude], marker_options, 
				{
					balloonOffset: [5, -25],
					draggable: _self.prop.draggable,
					hasBallon: _self.prop.showInfo,
					hideIconOnBalloonOpen: false,
					zIndex: 1,
					zIndexActive: 1,
				}); 				
			
			if(options.icon){
				_self.marker.options.set({
					iconImageHref: options.icon,
				});
			}
			
			_self.marker.events.add('drag', function(event){
				if(_self.prop.drag) _self.prop.drag();
			});
		
			if (_self.prop.draggable){
				_self.marker.events.add('dragend', function(event){
					if(_self.prop.drag_listener){
						var loc = _self.getLocation();	
						_self.prop.drag_listener(_self.prop.gid, loc.latitude, loc.longitude);
					}
					
					if(_self.prop.dragend) _self.prop.dragend();
				});
			}
			_self.map.addEntry(_self.marker);
		}

		this.displayInfobox = function(){
			
		}
		
		this.getGUID = function(){
			return _self.prop.gid;
		}
	
		this.getLocation = function(){
			var loc = _self.marker.geometry.getCoordinates();
			return {latitude: loc[0], longitude: loc[1]};
		}
		
		this.clear = function(){
			if(_self.infobox)
				_self.infobox.close();
			_self.map = null;				
		}
		
		this.move = function(latitude, longitude){
			_self.marker.geometry.setCoordinates([latitude, longitude]);
		}
	
		_self.init(latitude, longitude, defOptions);
	}
	
	_self.init(defOptions);
	
	return _self;
}

function YandexMapsv2_Geocoder(defOptions){
	this.properties = {
		site_url: '',
	}
	
	var _self = this;
	
	this.init = function(options){
		_self.properties = $.extend(_self.properties, options);
	}
	
	this.geocodeLocation = function(location, callback){
		var geocoder = ymaps.geocode(location, {results:1});
		geocoder.then(function(result){
			_self.wait_requests--;
			if(result.geoObjects.getLength()){
				var location = result.geoObjects.get(0).geometry.getCoordinates();
				if(callback) callback(location[0], location[1]);
			}
		}, function(e){
			alert('error');
			_self.wait_requests--;
		});
	}
	
	this.geocodeCoordinates = function(latitude, longitude, callback){
		var geocoder = ymaps.geocode([latitude, longitude], {results: 1});
		geocoder.then(function(result){
			_self.wait_requests--;
			result.geoObjects.get(0).properties.get('name');
			if(callback) callback(name);
		}, function(e){_self.wait_requests--;});
	}
	
	this.getLocationFromAddress = function(country, region, city, address, zip){
		var location = [];
		//if(zip) location.push(zip);	
		if(country) location.push(country);
		if(region) location.push(region);
		if(city) location.push(city);
		if(address) location.push(address);
		return location.join(', ');
	}
	
	_self.init(defOptions);

	return _self;
}
