function GoogleMapsv3(defOptions){
	if(typeof(google) == 'undefined') return false;
	
	this.prop = {
		site_url: '',
		save_location_url: '/admin/admin_settings.php',
		map_container: 'map_container',
		panorama_container: 'pano_container',
		routes_container: 'routes',
		default_zoom: 10,
		default_map_type: 2,
		lat: 56.614607,
		lon: 47.863757,		
		width: 0,
		height: 0,
		icon: null,
		use_search_by_area: false,
		use_smart_zoom: false,
		use_searchbox: false,
		use_search_auto: false,
		use_search_radius: false,
		search_radius_default: 1,
		use_show_details: false,
		use_panorama: false,
		use_router: false,
		use_type_selector: false,
		use_amenities: false,
		amenities: [],
		amenities_names: {},
		rtl: false,
		geocode_listener: false,
		zoom_listener: false,
		type_listener: false,
		
		search_figure_stroke_color: '#FF0000',
		search_figure_stroke_opacity: 0.8,
		search_figure_stroke_weight: 2,
		search_figure_fill_color: '#FF0000',
		search_figure_fill_opacity: 0.35,
		search_figure_editable: false,
		
		default_circle_search_radius: 100,
		circle_search_radius: 0,
		circle_search_radius_unit: 'km',
		circle_center_lat: 0,
		circle_center_lon: 0,
		
		circle_slider_step: 10,
		circle_slider_min: 10,
		
		langs: [],
	};

	this.MAP_TYPES = [
		google.maps.MapTypeId.HYBRID,
		google.maps.MapTypeId.ROADMAP,
		google.maps.MapTypeId.SATELLITE,
		google.maps.MapTypeId.TERRAIN,
	];
	
	this.ANIMATION_TYPES = [
		google.maps.Animation.DROP,
	];
	
	this.is_loaded = false;
	
	this.min_zoom = 1;
	this.max_zoom = 19;
	
	this.map = null;
	this.searchCircle = null;
	this.panorama = null;
	this.markers = [];
	this.amenities = [];
	this.places = [];
	this.zoom = null;
	this.place_service = null;
	this.directions_service = null;
	this.directions_display = null;
	this.wait_requests = 0;
	this.amenities_loaded = false;
	
	var _self = this;
	
	this.init = function(options){
		_self.prop = $.extend(_self.prop, options);
				
		_self.prop.lat = parseFloat(_self.prop.lat);
		_self.prop.lon = parseFloat(_self.prop.lon);
		
		var map_container = $('#'+_self.prop.map_container);
		
		if(!map_container.length){
			alert('map container not exists!');
			return false;
		}
		
		map_container.html('');
		
		map_container.css({'width': _self.prop.width, 'height': _self.prop.height});
		
		try{
			_self.map = new google.maps.Map(map_container[0], {
						center: new google.maps.LatLng(_self.prop.lat, _self.prop.lon),
						mapTypeId: _self.MAP_TYPES[_self.prop.default_map_type-1],
						zoom: parseInt(_self.prop.default_zoom),
						mapTypeControl: _self.prop.use_type_selector,
						streetViewControl: _self.prop.use_panorama,
						scrollwheel: _self.prop.use_search_by_area
					});
		} catch(e) {
			alert('map initialize is failed!');
			return false;
		}
		
		_self.min_zoom = Math.max(_self.min_zoom, _self.MAP_TYPES[_self.prop.default_map_type-1].minZoom);
		_self.max_zoom = Math.min(_self.max_zoom, _self.MAP_TYPES[_self.prop.default_map_type-1].maxZoom);
		
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
			if(routes_container.length){ 			
				_self.directions_service = new google.maps.DirectionsService();
				_self.directions_display = new google.maps.DirectionsRenderer({draggable: true, preserveViewport: true})
				google.maps.event.addListener(_self.directions_display, "directions_changed", function(){
					_self.renderRoute();
				});
			}else{
				_self.prop.use_router = false;
			}
		}
		
		if(_self.prop.use_searchbox || _self.prop.use_amenities){
			_self.place_service = new google.maps.places.PlacesService(_self.map);
			if(_self.prop.use_searchbox) _self.addSearchbox();
		}
		
		google.maps.event.addListener(_self.map, "zoom_changed", function(){
			if(!_self.is_loaded) return false;
			var czoom = _self.getZoom();
			if(czoom != _self.zoom){
				if(_self.prop.zoom_listener != false){
					_self.prop.zoom_listener(czoom);
				}
				_self.zoom = czoom;
			}
		});

		if(_self.prop.type_listener){
			google.maps.event.addListener(_self.map, "maptypeid_changed", function(){
				if(_self.is_loaded){
					var map_type_int = _self.MAP_TYPES.indexOf(_self.map.getMapTypeId());
					_self.prop.type_listener(map_type_int+1);
				}
			});
		}
		//_self.renderControl();

		if(_self.prop.use_search_by_area) {
			_self.drawSearchCircle();
		}
		
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
			_self.map.setMapTypeId(_self.MAP_TYPES[i]);		
			
			var name = _self.MAP_TYPES[i];
			//if(!$.inArray(name, a_types)) continue;
			a_types.push(name);
			controls += '<option value="'+i+'"'+(default_type == _self.MAP_TYPES[i] ? 'selected="selected"' : '')+'>'+name+'</option>';			
		}
	
		if(!$.inArray(name, a_types))  default_type = _self.MAP_TYPES[1];
		_self.map.setMapTypeId(default_type);
			
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
	
	this.getControl = function(){
		return _self.map;
	}
	
	this.addMarker = function(latitude, longitude, options){
		var marker = new Marker(_self, latitude, longitude, options);
		_self.markers.push(marker);
		
		if(!_self.wait_requests){
			_self.setSmartCenter();
			
			if(_self.prop.use_panorama && !_self.panorama){
				var loc = marker.getLocation();				
				_self.addPanorama(loc.latitude, loc.longitude);
			}
		
			if(_self.prop.use_amenities && _self.prop.amenities.length && !_self.amenities.length){
				_self.addAmenities();
			}
		}
	}
	
	this.drawSearchCircle = function(radius, center_lat, center_lon){
		var distance = 0;
		var latitude = 0;
		var longitude = 0;
		var unit = 0.0;
		
		if(radius == 0 || radius == undefined) {
			distance = parseFloat(_self.prop.circle_search_radius);
		} else {
			distance = radius;
		}

		if(center_lat == 0 || center_lat == undefined) {
			latitude = _self.prop.circle_center_lat;
		} else {
			latitude = center_lat;
		}
		if(center_lon == 0 || center_lon == undefined) {
			longitude = _self.prop.circle_center_lon;
		} else {
			longitude = center_lon;
		}
		
		var populationOptions = {
			strokeColor: _self.prop.search_figure_stroke_color,
			strokeOpacity: _self.prop.search_figure_stroke_opacity,
			strokeWeight: _self.prop.search_figure_stroke_weight,
			fillColor: _self.prop.search_figure_fill_color,
			fillOpacity: _self.prop.search_figure_fill_opacity,
			editable: _self.prop.search_figure_editable,
			map: _self.map,
			center: new google.maps.LatLng(latitude, longitude),
			radius: distance
		};

		_self.searchCircle = new google.maps.Circle(populationOptions);
		
		$('#circle_center_lat').val(latitude);
		$('#circle_center_lon').val(longitude);
		_self.setCenter(latitude, longitude);
		
		if(radius == 0 || radius == undefined) {
			_self.addCircleSlider(distance);
		}
		
		google.maps.event.addListener(_self.searchCircle, 'radius_changed', function(){
			_self.circleSliderChange();
		});
		
		google.maps.event.addListener(_self.searchCircle, 'center_changed', function(){
			_self.circlePositionChange();
		});
	}
	
	this.addCircleSlider = function(radius) {
		view_radius = _self.convertDistanceUnits(radius);
	
		var content = $(
			'<div class="nearest_users_input_block">' +	
			'	<input type="submit" value="' + _self.prop.langs.search + '" id="search_nearest_btn" name="search_nearest_btn">' +
			'	<div class="circle_slider_block" id="circle_radius_slider">' +
			'		<a href="#" class="ui-slider-handle ui-state-default ui-corner-all">' +
			'			<span id="circle_radius_span" style="position: inherit; margin: 25px -40px; width: 100px;">' + 
						_self.prop.langs.radius + ' ' + view_radius.toFixed() + ' ' + _self.prop.langs.unit + '</span>' +
			'		</a>' +
			'	</div>' +
			'</div>');
					
		content.find("#circle_radius_slider").slider({
			orientation: "horizontal",
			step: _self.prop.circle_slider_step,
			max: _self.prop.default_circle_search_radius,
			min: _self.prop.circle_slider_min,
			value: radius,
			range: false,
			animate: 'slow',
			change: function(event, ui) {
				_self.setCircleRadius(ui.value);
			}
		});		
			
		var position = _self.prop.rtl ? google.maps.ControlPosition.LEFT_BOTTOM : google.maps.ControlPosition.RIGHT_BOTTOM;
		_self.map.controls[position].push(content[0]);
		
		$('.map_container').on('click', function(){
			$("#circle_radius_slider a").removeClass('ui-state-focus');
			$("#circle_radius_slider a").removeClass('ui-state-hover');
		});
	}
	
	this.circleSliderChange = function() {
		var radius = _self.searchCircle.getRadius();
		var value = $( "#circle_radius_slider" ).slider("value");
		
		if(value != radius) {
			$("#circle_radius_slider").slider("value", radius);
		}
	}
	
	this.circlePositionChange = function() {
		var location = _self.searchCircle.getCenter();
		var radius = _self.searchCircle.getRadius()
		
		$('#circle_center_lat').val(location.lat());
		$('#circle_center_lon').val(location.lng());
		
		_self.setCenter(location.lat(), location.lng());
		_self.searchCircle.setMap(null);
		_self.searchCircle = null;			
		_self.drawSearchCircle(radius, location.lat(), location.lng());
	}
	
	this.setCircleRadius = function(radius){
		var location = _self.searchCircle.getCenter();
		
		_self.searchCircle.setMap(null);
		_self.searchCircle = null;
		_self.drawSearchCircle(radius, location.lat(), location.lng());
		_self.prop.circle_search_radius = radius;
		$('#circle_radius').val(radius);
		
		radius = _self.convertDistanceUnits(radius);
		
		$('#circle_radius_span').text(_self.prop.langs.radius + ' ' + radius.toFixed() + ' ' + _self.prop.langs.unit);
	}
	
	this.convertDistanceUnits = function(distance) {
		var convertDistance = 0;
		if(_self.prop.circle_search_radius_unit == 'km') {
			convertDistance = distance / 1000;
		} else {
			convertDistance = distance / 1609.344;
		}
		
		return convertDistance;
	}
	
	this.setType = function(index){
		if(!_self.MAP_TYPES[index]) return;
		_self.map.setMapTypeId(_self.MAP_TYPES[index]);
	}
	
	this.getZoom = function(){
		return _self.map.getZoom();
	}
	
	this.setZoom = function(zoomLevel){
		_self.map.setZoom(zoomLevel);
	}
	
	this.changeZoom = function(step){
		_self.zoom = _self.zoom + step;
		_self.setZoom(_self.zoom);
		return _self.zoom;
	}

	this.getCenter = function(){		
		var location = _self.map.getCenter();
		return {latitude: location.lat(), longitude: location.lng()};
	}
	
	this.setCenter = function(latitude, longitude){
		_self.map.setCenter(new google.maps.LatLng(latitude, longitude));
	}
	
	this.getMarkersBounds = function(){
		var markers = _self.markers;
		for(var i in _self.places){
			markers.push(_self.places[i]);
		}
		for(var i in _self.amenities){
			markers.push(_self.amenities[i].getMarker());
		}
		
		if(!markers.length)
			return new google.maps.LatLngBounds(
				new google.maps.LatLng(_self.prop.lat, _self.prop.lon),
				new google.maps.LatLng(_self.prop.lat, _self.prop.lon)
			);
	
		var point1 = markers[0].getLocation();	
		if(markers.length == 1)
			return new google.maps.LatLngBounds(
				new google.maps.LatLng(point1.latitude, point1.longitude),
				new google.maps.LatLng(point1.latitude, point1.longitude)
			);
			
		var point2 = markers[1].getLocation();
		var bounds = new google.maps.LatLngBounds(		
			new google.maps.LatLng(
				Math.min(point1.latitude, point2.latitude), 
				Math.min(point1.longitude, point2.longitude)
			),
			new google.maps.LatLng(
				Math.max(point1.latitude, point2.latitude), 
				Math.max(point1.longitude, point2.longitude)
			)
		);
		
		for(var i=2; i<markers.length; i++){
			var point = markers[i].getLocation();
			bounds.extend(new google.maps.LatLng(point.latitude, point.longitude));
		}		
		
		return bounds;
	}
	
	this.setCenterFromLocation = function(location){
		this.geocodeLocation(location, {}, _self.setCenter);
	}
	
	this.setSmartCenter = function(){
		var bounds = _self.getMarkersBounds();		
		if(_self.prop.use_smart_zoom){		
			_self.map.fitBounds(bounds);
		}else{
			var point = bounds.getCenter();
			_self.setCenter(point.lat(), point.lng());
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
		for(i in _self.markers){
			_self.markers[i].clear();
			_self.markers[i] = null;
		}
		_self.markers = [];
		
		for(i in _self.places){
			_self.places[i].clear();
			_self.places[i] = null;
		}
		_self.places = [];
		
		for(i in _self.amenities){
			_self.amenities[i].clear();			
		}
		_self.amenities = [];
	}
	
	this.remove = function(){
		_self.clear();
		_self.panorama = null;
		_self.map = null;
	}
	
	this.addPanorama = function(latitude, longitude){
		var panorama_container = $('#'+_self.prop.panorama_container);		
		if(!panorama_container.length) return; 

		panorama_container.css({'width': _self.prop.width, 'height': _self.prop.height, 'position': 'absolute', 'opacity': 0});
		
		var point = new google.maps.LatLng(latitude, longitude);
		var sv = new google.maps.StreetViewService();
		_self.addPanoramaFromLocation(sv, point, 50);
	}
	
	this.addPanoramaFromLocation = function(sv, point, radius){
		sv.getPanoramaByLocation(point, radius, function(data, status){
			if(status == google.maps.StreetViewStatus.OK){
				var panorama_container = $('#'+_self.prop.panorama_container);
				_self.panorama = new  google.maps.StreetViewPanorama(panorama_container[0], {
					position: data.location.latLng,
					pov: {
						heading: 0,
						pitch: 0,
						zoom: 1,
					},
				});
				_self.map.setStreetView(_self.panorama);				
				panorama_container.css({'position': 'relative', 'opacity': 1});
			}else{
				if(radius != 200){
					_self.addPanoramaFromLocation(sv, point, 200);
				}else{
					var panorama_container = $('#'+_self.prop.panorama_container);
					panorama_container.css({'position': 'relative', 'opacity': 1, 'display': 'none'});
				}
			}
		});		
	}
	
	this.createRoute = function(){
		if(!_self.markers.length) return;
		var loc = _self.markers[0].getLocation();
		var bounds = _self.map.getBounds();
		_self.directions_display.setMap(_self.map);
		_self.calcRoute([
			new google.maps.LatLng(loc.latitude, loc.longitude), 
			new google.maps.LatLng(
				loc.latitude+(bounds.getNorthEast().lat()-bounds.getSouthWest().lat())/10, 
				loc.longitude+(bounds.getSouthWest().lng()-bounds.getSouthWest().lng())/10
			)
		]);
	}
	
	this.calcRoute = function(routes){
		if(!_self.prop.use_router) return false;
		
		if(!routes.length) return false;
		
        _self.directions_service.route({
            origin:routes[0],
            destination:routes[routes.length-1],
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        }, function(response, status){
			if(status == google.maps.DirectionsStatus.OK){
				_self.directions_display.setDirections(response);
			}
        });
	}
	
	this.renderRoute = function(){
		var content = '';
		var distance = 0;
		var duration = 0;
		var route = _self.directions_display.getDirections().routes[0].legs[0];
		for(var i=0;i<route.steps.length; i++){
			if(route.steps[i].instructions) content += '<p>'+route.steps[i].instructions+'</p>';
			distance += (route.steps[i].distance.value || 0);
			duration += (route.steps[i].duration.value || 0);
		}
		if(distance){
			distance /= 1000;
			content += 'Total distance: ' + distance.toFixed(2) + ' km <br>';
		}
		if(duration){
			duration /= 60;
			var label = 'minutes';
			if(duration >= 60){
				duration /= 60;
				label = 'hours';
			}
			content += 'Total duration: ' + duration.toFixed(2) + ' ' + label + '<br>';
		}
		$('#'+_self.prop.routes_container).html(content);
	}
	
	this.deleteRoute = function(){
		_self.directions_display.setMap(null);
		$('#'+_self.prop.routes_container).html('');
	}	
	
	this.searchByKeyword = function(keyword, radius){
		if(!keyword || !radius) return false;
		
		if(_self.places.length){
			for(var i in _self.places)
				_self.places[i].clear();
			_self.places = [];
		}
		
		var point = _self.getCenter();
		var request = {
			location: new google.maps.LatLng(point.latitude, point.longitude),
			radius: radius*1000,
			keyword: keyword
		};
		_self.place_service.search(request, function(result, status){
			if(status == google.maps.places.PlacesServiceStatus.OK){
				for(var i in result){
					if(_self.prop.use_show_details){
						_self.getDetailedInfo(result[i].reference, _self.addPlace);
						_self.wait_requets++;
					}else{
						_self.addPlace(result[i]);						
					}
					_self.setSmartCenter();
				}
			}else if(status == google.maps.places.PlacesServiceStatus.ZERO_RESULTS){
				alert('no results');
			}else{
				alert('Error: '+status);
			}
		});
	}
	
	this.searchByTypes = function(types){
		var amenities = [];
		for(var i in _self.amenities){
			if(!_self.amenities[i].clear(types))
				amenities.push(_self.amenities[i]);
		}
		_self.amenities = amenities;

		var options = {types: types};
		options.location = _self.map.getCenter();
		options.radius = '500';
		_self.place_service.search(options, function(result, status){
			if(status != google.maps.places.PlacesServiceStatus.OK) return false;
				for(var i in result){
					if(_self.prop.use_show_details){
						_self.getDetailedInfo(result[i].reference, _self.addAmenity);
						_self.wait_requests++;
					}else{
						_self.addAmenity(result[i]);
					}
				}
				_self.setSmartCenter();
			}
		);
	}
		
	this.addPlace = function(place, wait){
		wait = wait || false;
		if(wait) _self.wait_requests--;
		var options = {
			info: _self.getPlaceInfo(place),
			showInfo: true,
			//animation: _self.prop.animation,
			icon: {
				url: place.icon ? place.icon : _self.prop.search_icon,
				scaledSize: new google.maps.Size(16, 16),
			},
			//shadow: _self.prop.search_shadow,
			reference: place.reference
		};
		var marker = new Marker(_self, place.geometry.location.lat(), place.geometry.location.lng(), options);
		_self.places.push(marker);
	}
	
	this.getPlaceInfo = function(place){
		var info = '';
		var addr = '';
		if(place.name){
			if(place.url){
				info += "<p><b><a href='"+place.url+"' target='_blank'>"+place.name+"</a></b>";
			}else{
				info += "<p><b>"+place.name+"</b>";
			}
			if(place.rating) info += "&nbsp;(Rating:&nbsp;"+place.rating+")";
			info += "</p>";
			addr = place.name + ' ';
		}
		if(place.vicinity){
			info += "<p>"+place.vicinity+"</p>";
			addr += place.vicinity;
		}
		if(place.international_phone_number) info += "<p>"+place.international_phone_number+"</p>";
		if(place.website){
			var patt = /(http[s]*:\/\/)+(.*?)[\/]+/i;
			if(!patt.exec(place.website)){
				place.website = 'http://'+place.website;
			}
			var site = (patt.exec(place.website)[2]) ? patt.exec(place.website)[2] : place.website;
			if(site){
				info += "<p><a href='"+place.website+"' target='_blank'>"+site+"</a></p>";
			}
		}
		if(!place.icon) place.icon = "http://maps.google.com/mapfiles/kml/pal3/icon43.png";

		return (
		'<div>' + 
		'	<!-- div class="marker_img"><img src="'+place.icon+'" width="30" height="30"></div -->' + 
		'	<div id="marker_info_td">'+info+'</div>' +
		'</div>');
	}

	this.getDetailedInfo = function(reference, callback){
		var request = {reference: reference};
		_self.place_service.getDetails(request, function(result, status){
			if(status == google.maps.places.PlacesServiceStatus.OK){
				callback(result, true);
			}
		});
	}
	
	this.addSearchbox = function(){
		var content = $(
			'<div class="map_search_box">' +
			'	<input type="text" class="map_search_keyword">' +
			'	<input type="button" value="Search">' +
			'</div>');
			if(_self.prop.use_search_radius)
				content.find('.map_search_keyword').after(
					'	Within:' +
					'	<select class="map_search_radius">' +
					'		<option value="0.5">0.5</option>' +
					'		<option value="1" selected>1</option>' +
					'		<option value="3">3</option>' + 
					'		<option value="5">5</option>' + 
					'		<option value="10">10</option>' + 
					'		<option value="20">20</option>' + 
					'		<option value="50">50</option>' +
					'	</select> km');
					
		content.find('.map_search_keyword').bind('keyup', function(e){
			e = e || window.event;
			if (e.keyCode == 13){
				_self.searchByKeyword($(this).val(), _self.prop.use_search_radius ? $('#'+_self.prop.map_container + ' .map_search_radius').val() : _self.prop.search_radius_default);
			}
			return false;
		});		
		content.find('input[type="button"]').bind('click', function(){
			_self.searchByKeyword($('#'+_self.prop.map_container + ' .map_search_keyword').val(), _self.prop.use_search_radius ? $('#'+_self.prop.map_container + ' .map_search_radius').val() : _self.prop.search_radius_default);
		});
		var position = _self.prop.rtl ? google.maps.ControlPosition.LEFT_BOTTOM : google.maps.ControlPosition.RIGHT_BOTTOM;
		_self.map.controls[position].push(content[0]);
		if(_self.prop.use_search_auto){
			G.autocomplete = new google.maps.places.Autocomplete($('#'+_self.prop.map_container + ' .map_search_keyword')[0], {bounds: G.map.getBounds(), types: ['establishment']});
		}
	}
	
	this.addAmenities = function(){
		if(_self.amenities_loaded || !_self.prop.amenities.length) return false;
		_self.amenities_loaded = true;
		
		_self.searchByTypes(_self.prop.amenities);
		
		var control = 
			'<div class="amenities_goolgemaps">' +
			'	<div class="amenities_header">' +
			'		<a class="amenities_switch"></a>Local amenity' +
			'	</div>' +
			'	<div class="amenities_body">';
				
		for(var i in _self.prop.amenities){
			control += 
			'	<div id="div_' + _self.prop.amenities[i] + '">' +
			'		<table cellpadding="0" cellspacing="0">' + 
			'			<tr>' +
			'				<td>' +
			'					<input type="checkbox" id="id_' + _self.prop.amenities[i] + '" value="' + _self.prop.amenities[i] + '" checked>' +
			'				</td>' +
			'				<!-- td width="16">' +
			'					<img src="/uploades/gmap/' + _self.prop.amenities[i] + ' style="padding: 0 2px;">' + 
			'				</td -->' +
			'				<td style="font-weight:bold; color:#000;">' + _self.prop.amenities_names[_self.prop.amenities[i]] + '</td>' +			
			'			</tr>' +
			'		</table>' +
			'	</div>'; 
		}
		
		control += '' +
			'	</div>' +
			'</div>'; 
		
		control = $(control);
		control.find('input[type="checkbox"]').bind('click', function(){
			if(this.checked){
				_self.searchByTypes([this.value]);
			}else{
				_self.clearAmenity(this.value); 
			}
		});
		control.find('#'+_self.prop.map_container+' .amenities_switch').bind('click', function(){
			$(this).toggleClass('expand');
			$('#'+_self.prop.map_container +' .amenities_goolgemaps .amenities_body').toggle();
		});
		
		var position = _self.prop.rtl ? google.maps.ControlPosition.LEFT_TOP : google.maps.ControlPosition.RIGHT_TOP;
		_self.map.controls[position].push(control[0]);
	}
	
	this.addAmenity = function(amenity, wait){
		wait = wait || false;
		if(wait) _self.wait_requests--;
		var options = {
			info: _self.getPlaceInfo(amenity),
			showInfo: true,
			//animation: _self.prop.animation,
			icon: {
				url: amenity.icon ? amenity.icon : _self.prop.search_icon,
				scaledSize: new google.maps.Size(16, 16)
			},
			//shadow: _self.prop.search_shadow,
			reference: amenity.reference
		};
		var marker = new Marker(_self, amenity.geometry.location.lat(), amenity.geometry.location.lng(), options);
		amenity = new Amenity(marker, $.grep(amenity.types, function(value){return $.inArray(value, _self.prop.amenities) != -1;}));
		_self.amenities.push(amenity);
				
	}
	
	this.clearAmenity = function(type){
		var amenities = [];
		for(var i in _self.amenities){
			if(!_self.amenities[i].clear([type]))
				amenities.push(_self.amenities[i]);
		}
		_self.amenities = amenities;
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
			info: null,
			teaserInfo: null,
			showInfo: true,
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
			_self.marker = new google.maps.Marker({
				draggable: _self.prop.draggable,
				icon: _self.prop.icon,
				map: _self.map.getControl(),
				position: new google.maps.LatLng(latitude, longitude),
				zIndex: 100,
			}); 
			
			if (_self.prop.draggable){
				google.maps.event.addListener(_self.marker, 'drag', function(e){
					if(_self.prop.infobox){
						var location = _self.map.getInfoboxLocation(_self.getLocation());
						_self.infobox.setLocation(location);
					}
				
					if(_self.prop.drag) _self.prop.drag();
				});
				
				google.maps.event.addListener(_self.marker, 'dragend', function(e){
					if(_self.prop.drag_listener){
						var loc = _self.getLocation();	
						window[_self.prop.drag_listener](_self.prop.gid, loc.latitude, loc.longitude);
					}
					
					if(_self.prop.dragend) _self.prop.dragend();
				});				
			}
		
			if (_self.prop.showInfo){
				google.maps.event.addListener(_self.marker, 'click', function(e){
					_self.displayInfobox();
				});
			}
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
				
				_self.infobox = new google.maps.InfoWindow({
					content: content,
				});
			}
			_self.infobox.open(_self.map.getControl(), _self.marker);
		}
		
		this.getGUID = function(){
			return _self.prop.gid;
		}
	
		this.getLocation = function(){
			var loc = _self.marker.getPosition();
			return {latitude: loc.lat(), longitude: loc.lng()};
		}
		
		this.clear = function(){
			if(_self.infobox)
				_self.infobox.close();
			_self.marker.setMap(null);	
			_self.map = null;
		}
		
		this.move = function(latitude, longitude){
			var location = new google.maps.LatLng(latitude, longitude);
			_self.marker.setPosition(location);
		}
	
		_self.init(latitude, longitude, defOptions);
		
		return _self;
	}
	
	function Amenity(marker, types){		
		this.marker = null,
		this.types = [];
		
		var _self = this;
		
		this.init = function(marker, types){
			_self.marker = marker;
			_self.types = types;
		}
		
		this.getMarker = function(){
			return _self.marker;
		}
		
		this.clear = function(types){
			types = types || [];
			if(types.length){
				_self.types = $.grep(_self.types, function(value){return $.inArray(value, types) == -1;});	
			}else{
				_self.types = [];
			}
			
			if(_self.types.length) return false;
			
			_self.marker.clear();
			
			_self.marker = null;
			
			return true;
		}
		
		_self.init(marker, types);
		
		return _self;
	}
	
	_self.init(defOptions);
	
	return _self;
}

function GoogleMapsv3_Geocoder(defOptions){
	this.properties = {
		site_url: '',
	}
	
	var _self = this;
	
	this.init = function(options){
		_self.properties = $.extend(_self.properties, options);
	}
	
	this.geocodeLocation = function(location, callback){
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({address: location}, function(result, status){
			_self.wait_requests--;
			if(status == google.maps.GeocoderStatus.OK){
				var lat = result[0].geometry.location.lat();
				var lon = result[0].geometry.location.lng();
				if(callback) callback(lat, lon);
			}
		});
	}
	
	this.geocodeCoordinates = function(latitude, longitude, callback){
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({location: new google.maps.LatLng(latitude, longitude)}, function(result, status){
			_self.wait_requests--;
			if(status == google.maps.GeocoderStatus.OK){
				if(callback) callback(result[0].address_components.long_name);
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
