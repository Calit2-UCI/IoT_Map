function BingMapsv7(defOptions){
	this.prop = {
		site_url: '',
		save_location_url: '/admin/admin_settings.php',
		map_container: 'map_container',
		routes_container: '',
		use_smart_zoom: false,
		use_searchbox: false,
		use_router: false,
		use_type_selector: false,
		amenities: [],
		icon: null,
		default_zoom: 10,
		default_map_type: 2,
		map_key: '',		
		lat: 56.614607,
		lon: 47.863757,		
		width: 0,
		height: 0,
		geocode_listener: false,
		zoom_listener: false,
		type_listener: false,
		
		useModules: {
			'advanced_shapes': false, 
			'directions': false, 
			'overlays': false, 
			'search': false, 
			'themes': false, 
			'traffic': false,
			'venue_maps': false
		},
	};
	
	this.MAP_TYPES = [
		Microsoft.Maps.MapTypeId.aerial, 
		Microsoft.Maps.MapTypeId.auto, 
		Microsoft.Maps.MapTypeId.birdseye,
		Microsoft.Maps.MapTypeId.collinsBart, 
		Microsoft.Maps.MapTypeId.mercator, 
		Microsoft.Maps.MapTypeId.ordnanceSurvey,
		Microsoft.Maps.MapTypeId.road,
	];
	
	this.ADVANCED_MODULES = {
		'advanced_shapes': {namespace: 'Microsoft.Maps.AdvancedShapes', is_loaded: false},
		'directions': {namespace: 'Microsoft.Maps.Directions', is_loaded: false},
		'overlays': {namespace: 'Microsoft.Maps.Overlays.Style', is_loaded:false},
		'search': {namespace: 'Microsoft.Maps.Search', is_loaded: false},
		'themes': {namespace: 'Microsoft.Maps.Themes.BingTheme', is_loaded: false},
		'traffic': {namespace: 'Microsoft.Maps.Traffic', is_loaded: false},
		'venue_maps': {namespace: 'Microsoft.Maps.VenueMaps', is_loaded: false},
	};
	
	this.is_loaded = false;
	
	this.directions_module_is_loaded = false;
	
	this.min_zoom = 1;
	this.max_zoom = 19;
	
	this.map = null;
	this.map_container = null;	
	
	this.markers = [];
	this.routes = [];
	this.amenities = {};
	this.infobox_visible = [];
	this.zoom = null;
	this.wait_requests = 0;
	
	this.smart_zoom_request = false;
	this.smart_zoom_direction = false;
	this.bounds = null;
	
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
		
		try{
			_self.map = new Microsoft.Maps.Map(_self.map_container[0], {
						credentials:_self.prop.map_key, 
						mapTypeId: _self.MAP_TYPES[_self.prop.default_map_type-1], 
						width: _self.prop.width, 
						height: _self.prop.height,
						center: new Microsoft.Maps.Location(_self.prop.lat, _self.prop.lon),
						zoom: _self.prop.default_zoom,
						enableClickableLogo: false,
						enableSearchLogo: _self.prop.use_searchbox,
						showMapTypeSelector: _self.prop.use_type_selector
					});
		} catch(e) {
			alert('map initialize is failed!');
			return false;
		}
		
		var zoom_range = _self.map.getZoomRange();
		
		_self.min_zoom = Math.max(_self.min_zoom, zoom_range.min);
		_self.max_zoom = Math.min(_self.max_zoom, zoom_range.max);
		
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
		
		Microsoft.Maps.Events.addHandler(_self.map, "viewchangeend", function(e) {
			var czoom = _self.getZoom();
			if(czoom != _self.zoom){
				for(i in _self.markers){
					if (_self.markers[i].infobox){
						var location = _self.getInfoboxLocation(_self.markers[i].getLocation());
						_self.markers[i].infobox.setLocation(location);
					}
				}
					
				if(_self.prop.zoom_listener != false){
					_self.prop.zoom_listener(czoom);
				}
				
				_self.zoom = czoom;
			}
			
			for(i in _self.infobox_visible){
				var index = _self.infobox_visible[i];
				if(_self.markers[index].infobox){
					var options = _self.markers[index].infobox.getOptions();							
					options.visible = true;
					_self.markers[index].infobox.setOptions(options);
				}
			}
			if(_self.smart_zoom_direction){
				var zoom_direction = _self.smart_zoom_direction;
				var contains = _self.mapContains(_self.bounds, _self.smart_zoom_direction);					
				if(_self.smart_zoom_direction == 1 && !contains){
					_self.smart_zoom_direction = 0;
					_self.bounds = null;
					_self.changeZoom(-zoom_direction);
				}else if(_self.smart_zoom_direction == -1 && contains){						
					_self.smart_zoom_direction = 0;
					_self.bounds = null;
				}else{
					if(_self.zoom <= 1 || _self.zoom >= _self.max_zoom){
						_self.smart_zoom_direction = 0;
						_self.bounds = null;
					}else{
						_self.changeZoom(zoom_direction);
					}						
				}
			}			
		});
		
		Microsoft.Maps.Events.addHandler(_self.map, "viewchangestart", function(e){
			_self.infobox_visible = [];
			for(i in _self.markers){
				if (_self.markers[i].infobox){							
					var options = _self.markers[i].infobox.getOptions();							
					if(options.visible){
						options.visible = false;
						_self.markers[i].infobox.setOptions(options);
						_self.infobox_visible.push(i);
					}
				}
			}
		});
	
		if(_self.prop.type_listener){
			Microsoft.Maps.Events.addHandler(_self.map, "maptypechanged", function() {
				if(_self.is_loaded){
					var map_type_int = _self.MAP_TYPES.indexOf(_self.map.getMapTypeId());
					_self.prop.type_listener(map_type_int+1);
					
					var zoom_range = _self.map.getZoomRange();
		
					_self.min_zoom = Math.max(_self.min_zoom, zoom_range.min);
					_self.max_zoom = Math.min(_self.max_zoom, zoom_range.max);
					
					if(_self.zoom < _self.min_zoom){
						_self.zoom = _self.min_zoom;
						_self.setZoom(_self.zoom);
					}else if(_self.zoom >= _self.max_zoom){
						_self.zoom = _self.max_zoom;
						_self.setZoom(_self.zoom);
					}
				}
			});
		}
		
		//_self.renderControl();
		
		/*for(i in _self.modules){
			if(_self.prop.useModules[i]){
				Microsoft.Maps.loadModule(_self.modules[i].namespace, function(){_self.modules[i].is_loaded = true;});
			}
		}*/
				
		_self.is_loaded = true;
	}
	
	this.renderControl = function(){
		var controls = 
			'<div style="position: absolute; right:5px; top:5px; background:#fff; opacity:0.5; z-index:1000;">'+	
			'<select id="map_type" style="margin:5px;">';
		
		var default_type = _self.map.getMapTypeId();
		var a_types = [];
	
		for(var i in _self.MAP_TYPES){		
			if(!_self.MAP_TYPES[i]) continue;
			_self.map.setMapType(_self.MAP_TYPES[i]);		
			var name = (_self.MAP_TYPES[i] == 'auto' ? 'Auto' : _self.map.getImageryId());
			if(!$.inArray(name, a_types)) continue;
			a_types.push(name);
			controls += '<option value="'+i+'"'+(default_type == _self.MAP_TYPES[i] ? 'selected="selected"' : '')+'>'+name+'</option>';			
		}
	
		if(!$.inArray(name, a_types))  default_type = _self.MAP_TYPES[1];
		_self.map.setMapType(default_type);
			
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
			
			//if(_self.prop.use_amenities){
				_self.addAmenities();
			//}
		}
	}
	
	this.setType = function(index){
		if(!_self.MAP_TYPES[index]) return;
		_self.map.setMapType(_self.MAP_TYPES[index]);
	}
	
	this.getZoom = function(){
		return _self.map.getZoom();
	}
	
	this.setZoom = function(zoomLevel){
		var options = _self.map.getOptions();
		options.zoom = zoomLevel;
		_self.map.setView(options)
	}
	
	this.changeZoom = function(step){
		_self.zoom = _self.zoom + step;
		_self.setZoom(_self.zoom);
		return _self.zoom;
	}
	
	this.getCenter = function(){		
		var center = _self.map.getCenter();
		return {latitude: center.latitude, longitude: center.longitude};
	}
	
	this.setCenter = function(latitude, longitude){
		var options = _self.map.getOptions();
		options.center = new Microsoft.Maps.Location(latitude, longitude);
		_self.map.setView(options);
	}
	
	this.getMarkersBounds = function(){
		var locations = [];		
		if(_self.markers.length){
			for(i in _self.markers){
				locations.push(_self.markers[i].getLocation());
			}		
		}else{
			locations.push(new Microsoft.Maps.Location(_self.prop.lat, _self.prop.lon));
		}
		return new Microsoft.Maps.LocationRect.fromLocations(locations);
	}
	
	this.setCenterFromLocation = function(location){
		this.geocodeLocation(location, {}, _self.setCenter);
	}
	
	this.setSmartCenter = function(){
		_self.bounds = _self.getMarkersBounds();		
		_self.setCenter(_self.bounds.center.latitude, _self.bounds.center.longitude);
		
		if(!_self.prop.use_smart_zoom) return false;
			
		var zoomDirection;
		if(_self.mapContains(_self.bounds)){
			zoomDirection = 1;
		}else{
			zoomDirection = -1;
		}
		_self.smart_zoom_direction = zoomDirection;		
		_self.changeZoom(zoomDirection);
	}
	
	this.getInfoboxLocation = function(location){
		var point = _self.map.tryLocationToPixel(location);
		if (!point) return null;
		point.x += 5;
		point.y -= 25;		
		return _self.map.tryPixelToLocation(point);
	}
	
	this.mapContains = function(bounds){
		var mapBounds = _self.map.getBounds();
		var point = _self.map.tryLocationToPixel(mapBounds.getNorthwest());
		point.y += $('#'+_self.prop.map_container+' .OverlaysTL').height()+2;
		_self.map.tryPixelToLocation(point).latitude
		return mapBounds.getEast() >= bounds.getEast() &&
			   mapBounds.getNorth() >= bounds.getNorth() &&
			   mapBounds.getSouth() <= bounds.getSouth() &&
			   mapBounds.getWest() <= bounds.getWest();
	}

	this.clear = function(){
		for(i in _self.markers){
			_self.markers[i].clear();
			_self.markers[i] = null;
		}		
		_self.markers = [];
	}
	
	this.remove = function(){
		_self.clear();
		_self.map.dispose();
		_self.map = null;
	}
	
	this.getKey = function(){
		return _self.prop.map_key;
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
	
	this.addEntry = function(obj){
		_self.map.entities.push(obj);
	}
	
	this.removeEntry = function(obj){
		_self.map.entities.remove(obj);				
	}
	
	this.setView = function(options){
		_self.map.setView(options);
	}
	
	this.startRoute = function(){
		if(!_self.markers.length) return;
		var loc = _self.markers[0].getLocation();
		var bounds = _self.map.getBounds();
		_self.calcRoute([
			loc.latitude.toFixed(3) +','+loc.longitude.toFixed(3), 
			(loc.latitude+(bounds.getNorth()-bounds.getSouth())/10).toFixed(3) + ',' + (loc.longitude+(bounds.getEast()-bounds.getWest())/10).toFixed(3)
		]);
	}
	
	this.calcRoute = function(routes){
		if(!_self.prop.use_router) return false;
		
		if(!routes.length) return false;
				
		var routes_container = $('#'+_self.prop.routes_container);
		
		/*if (_self.directions_module_is_loaded){
			directionsManager.setRequestOptions({
				routeMode: Microsoft.Maps.Directions.RouteMode.driving,
				routeDraggable: false
			});	
			directionsManager.setRenderOptions({
				itineraryContainer: routes_container[0],
				waypointPushpinOptions:{visible:false},
				viapointPushpinOptions:{visible:false},
			});
			return;
		}else{*/
			var options = {};
			var route = new Route(_self, routes, options);
			_self.routes.push(route);
		/*}*/
	}
	
	this.renderRoute = function(resources){
		var content = '';
		var distance = 0;
		var duration = 0;
		for(var i in resources.routeLegs){
			for(var j in resources.routeLegs[i].itineraryItems){
				if(resources.routeLegs[i].itineraryItems[j].instruction.text) content += '<p>'+resources.routeLegs[i].itineraryItems[j].instruction.text+'</p>';
			}
			distance += (resources.routeLegs[i].travelDistance || 0);
			duration += (resources.routeLegs[i].travelDuration || 0);
		}
		if(distance){
			content += 'Total distance: ' + distance.toFixed(2) + ' ' + resources.distanceUnit + ' <br>';
		}
		if(duration){
			content += 'Total duration: ' + duration.toFixed(2) + ' ' + resources.durationUnit + '<br>';
		}
		$('#'+_self.prop.routes_container).html(content);
	}
	
	this.deleteRoute = function(){
		if (!_self.routes) return;
		for (i in _self.routes) {
			_self.routes[i].clear();
			_self.routes[i] = null;
		}
		_self.routes = [];
		$('#'+_self.prop.routes_container).html('');
	}
	
	this.addAmenities = function(){
		//if(!_self.prop.amenities.length) return false;

		/*var point = _self.getCenter();
		console.log(point);
		var request = "http://api.bing.net/json.aspx?"
            + "AppId=" + "wboYoU8B8oi5t3B7kxTmK6Sz95m9RzBcji1xp0kC5wc="
            + "&Query=Pubs"
            + "&Sources=Phonebook"
            + "&Version=2.0"
            + "&Market=en-us"
            + "&UILanguage=en"
            + "&Latitude=" + point.latitude
            + "&Longitude=" + point.longitude
            + "&Radius=100.0"
            + "&Options=EnableHighlighting"
            + "&Phonebook.Count=25"
            + "&Phonebook.Offset=0"
            + "&Phonebook.FileType=YP"
            + "&Phonebook.SortBy=Distance"
            + "&JsonType=callback"
            + "&JsonCallback=?";
	console.log(request);
		$.getJSON(request, function(result){
		
		});*/
		
		var bounds = _self.map.getBounds();
		
		var location = 'Pubs';
		var geocodeRequest = 'http://spatial.virtualearth.net/REST/v1/data/c2ae584bbccc4916a0acf75d1e6947b4/NavteqEU/NavteqPOIs?' +
							 'spatialFilter=nearby%2850.1120796203613,8.68340969085693,100%29' +
							 '&$select=EntityID,Latitude,Longitude,DisplayName,__Distance,LanguageCode' +
							 '&$top=3&$format=json&jsonp=?&key=' + _self.prop.map_key;							 
							 
		console.log(geocodeRequest);
		$.getJSON(geocodeRequest, function(result){
			_self.wait_requests--;
			if (result && result.resourceSets && result.resourceSets.length > 0 &&
				result.resourceSets[0].resources && result.resourceSets[0].resources.length > 0){
				console.log(result.resourceSets[0].resources[0]);
				var loc = result.resourceSets[0].resources[0].point.coordinates;
				//callback(loc[0], loc[1], options);
			}
		});
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
			width: 30,
			height: 39,
			icon: null,		
			info: null,
			showInfo: true,
			infoWidth: '430',
			infoHeight: '250',		
			info: null,
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
		
			if(!_self.prop.info) _self.prop.showInfo = false;
		
			_self.marker = new Microsoft.Maps.Pushpin(
				new Microsoft.Maps.Location(latitude, longitude), {
					draggable: _self.prop.draggable,
					icon: _self.prop.icon,
					width: _self.prop.width,
					height: _self.prop.height,
				}); 
		
			Microsoft.Maps.Events.addHandler(_self.marker, 'drag', function(e){
				if(_self.infobox){
					var location = _self.map.getInfoboxLocation(_self.getLocation());
					_self.infobox.setLocation(location);
				}
				
				if(_self.prop.drag) _self.prop.drag();
			});
		
			if(_self.prop.showInfo){
				Microsoft.Maps.Events.addHandler(_self.marker, 'click', function(e){
					_self.displayInfobox();
				});
			}
		
			if (_self.prop.draggable){
				Microsoft.Maps.Events.addHandler(_self.marker, 'dragend', function(e){
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
			if (!_self.infobox){
				var content = '<div class="info_box">';
				var position = _self.getLocation();
				for(var i in _self.map.markers){
					var pos = _self.map.markers[i].getLocation();
					if(pos.latitude == position.latitude && pos.longitude == position.longitude){
						content += '<div class="info_item">'+_self.map.markers[i].prop.info+'</div>';
					}
				}
				content += '</div>';
				
				_self.infobox = new Microsoft.Maps.Infobox(
					_self.map.getInfoboxLocation(_self.marker.getLocation()), {
						width: _self.prop.infoWidth, 
						height: _self.prop.infoHeight, 
						title: '<div style="width: '+_self.prop.infoWidth+'px; max-height: '+_self.prop.infoHeight+'px; overflow-x: hidden;">' + content + '</div>', 
						htmlContent: '<div style="width: '+_self.prop.infoWidth+'px; max-height: '+_self.prop.infoHeight+'px; overflow-x: hidden;">' + content + '</div>',
						showPointer: true, 
						showCloseButton: true, 
						zIndex: 0,
						visible: false
					});
				_self.map.addEntry(_self.infobox);
			}
			var options = _self.infobox.getOptions();
			options.visible = true;
			_self.infobox.setOptions(options);
		}
		
		this.getGUID = function(){
			return _self.prop.gid;
		}
	
		this.getLocation = function(){
			var loc = _self.marker.getLocation()
			return {latitude: loc.latitude, longitude: loc.longitude};
		}
		
		this.clear = function(){
			if(_self.infobox)
				_self.map.removeEntry(_self.infobox);
			_self.map.removeEntry(_self.marker);				
		}
		
		this.move = function(latitude, longitude){
			var location = new Microsoft.Maps.Location(latitude, longitude)
			_self.marker.setLocation(location);
		}
	
		_self.init(latitude, longitude, defOptions);
	}

	function Route(map, routes, defOptions){
		this.prop = {
		};

		this.ROUTE_TYPES = [
			'Driving',
			'Walking',
			'Transit',
		];
		
		this.map = map;
		this.start = null;
		this.end = null;
		this.routeshape = null;
	
		var _self = this;
	
		this.init = function(routes, options){
			_self.prop = $.extend(_self.prop, options);
	
			if (!routes.length) return;
	
			routes_str = '';
			for(i in routes){
				routes_str += 'waypoint.' + i + '=' + encodeURI(routes[i]) + '&'; 
			}
		
			var route_type = 'Driving';
		
			var routeRequest = "http://dev.virtualearth.net/REST/v1/Routes/" + route_type
				+ "?" + routes_str + "routePathOutput=Points&output=json&jsonp=?&key=" + _self.map.getKey();
			$.getJSON(routeRequest,  function(result){	
				if (result && result.resourceSets && result.resourceSets.length > 0 &&
					result.resourceSets[0].resources && result.resourceSets[0].resources.length > 0){
				
					var resources = result.resourceSets[0].resources[0];
				
					// Set the map view
					var bbox = resources.bbox;
					var viewBoundaries = Microsoft.Maps.LocationRect.fromLocations(
											new Microsoft.Maps.Location(bbox[0], bbox[1]), 
											new Microsoft.Maps.Location(bbox[2], bbox[3]));
					_self.map.setView({bounds: viewBoundaries});

					// Draw the route
					var routeline = resources.routePath.line;
					var routepoints = new Array();                     
					for (var i = 0; i < routeline.coordinates.length; i++){
						routepoints[i] = new Microsoft.Maps.Location(routeline.coordinates[i][0], routeline.coordinates[i][1]);
					}

					var startLocation = resources.routeLegs[0].actualStart;
					var endLocation = resources.routeLegs[0].actualEnd;
				
					// Draw the route on the map
					var coord = startLocation.coordinates.toString().split(',');
					_self.start = new Marker(_self.map, coord[0], coord[1], 
						{draggable:true, info: startLocation.name, infoWidth: 250, infoHeight: 60, 
							drag: function(){
								var locs = _self.routeshape.getLocations();
								locs[0] = _self.start.getLocation(); 
								_self.routeshape.setLocations(locs);
							},
							dragend: function(){
								_self.map.deleteRoute();
								var loc1 = _self.start.getLocation();
								var loc2 = _self.end.getLocation();
								_self.map.calcRoute([
									loc1.latitude.toFixed(3) +','+loc1.longitude.toFixed(3), 
									loc2.latitude.toFixed(3) +','+loc2.longitude.toFixed(3), 
								]);
							},
						});
				
					var coord = endLocation.coordinates.toString().split(',');
					_self.end = new Marker(_self.map, coord[0], coord[1], 
						{draggable:true, info: endLocation.name, infoWidth: 250, infoHeight: 60,
							drag: function(){
								var locs = _self.routeshape.getLocations();
								locs[locs.length-1] = _self.end.getLocation(); 
								_self.routeshape.setLocations(locs);
							},
							dragend: function(){
								_self.map.deleteRoute();
								var loc1 = _self.start.getLocation();
								var loc2 = _self.end.getLocation();
								_self.map.calcRoute([
									loc1.latitude.toFixed(3) +','+loc1.longitude.toFixed(3), 
									loc2.latitude.toFixed(3) +','+loc2.longitude.toFixed(3), 
								]);
							},
						});
								
					_self.routeshape = new Microsoft.Maps.Polyline(routepoints, {strokeColor:new Microsoft.Maps.Color(200,0,0,200)});
					_self.map.addEntry(_self.routeshape);
					
					_self.map.renderRoute(resources);
				}
			});
		}

		this.clear = function(){
			_self.start.clear();
			_self.end.clear();
			_self.map.removeEntry(_self.routeshape);
		}
	
		_self.init(routes, defOptions);
	}
		
	_self.init(defOptions);
	
	return _self;
}

function BingMapsv7_Geocoder(defOptions){
	this.properties = {
		site_url: '',
	}
	
	var _self = this;
	
	this.init = function(options){
		_self.properties = $.extend(_self.properties, options);
	}
	
	this.geocodeLocation = function(location, callback){
		var geocodeRequest = 'http://dev.virtualearth.net/REST/v1/Locations?query=' + location + '&maxResults=1&output=json&jsonp=?&key=' + _self.prop.map_key;
		$.getJSON(geocodeRequest,  function(result){
			_self.wait_requests--;
			if (result && result.resourceSets && result.resourceSets.length > 0 &&
				result.resourceSets[0].resources && result.resourceSets[0].resources.length > 0){
				var loc = result.resourceSets[0].resources[0].point.coordinates;
				if(callback) callback(loc[0], loc[1]);
			}
		});
	}
	
	this.geocodeCoordinates = function(latitude, longitude, callback){
		var geocodeRequest = 'http://dev.virtualearth.net/REST/v1/Locations/' + latitude + ',' + longitude + '?maxResults=1&output=json&jsonp=?&key=' + _self.prop.map_key;
		$.getJSON(geocodeRequest,  function(result){
			_self.wait_requests--;
			if (result && result.resourceSets && result.resourceSets.length > 0 &&
				result.resourceSets[0].resources && result.resourceSets[0].resources.length > 0){
				if(callback) callback(result.resourceSets[0].resources[0].name);
			}
		});
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
