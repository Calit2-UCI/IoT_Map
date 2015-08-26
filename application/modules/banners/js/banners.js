function Banners(defOptions){
	this.properties = {
		siteUrl: '',
	}

	this.bannerArea = new Array;
	
	this.bannerData = new Array;

	this.timeOuts = new Array;

	var _self = this;

	this.init = function(options){
		_self.properties = $.extend(_self.properties, options);
	}

	this.create_banner_area = function(banner_area, banner_data){
		_self.bannerArea[banner_area.id] = banner_area;
		_self.bannerData[banner_area.id] = banner_data;
		$('#'+banner_area.div_id).width(banner_area.width+'px').height(banner_area.height+'px').html('<div style="width: '+banner_area.width+'px; height: '+banner_area.height+'px;">&nbsp;</div>');
		if(banner_data.length > 1){
			_self.bannerArea[banner_area.id].rotate = true;
		}else{
			_self.bannerArea[banner_area.id].rotate = false;
		}
		_self.bannerArea[banner_area.id].current_banner = -1;
		
		_self.preload_images(banner_area.id);
		_self.show_banner(banner_area.id);
	}
	
	this.preload_images = function(banner_area_id){
		var banners_count = _self.bannerData[banner_area_id].length;
		var banner_image = new Array();
		for(var i=0; i<banners_count; i++){
			if(_self.bannerData[banner_area_id][i].banner_type == 'image'){
				banner_image[i] = new Image;
				banner_image[i].src = _self.bannerData[banner_area_id][i].banner_src;
			}
		}
	}

	this.show_banner = function(banner_area_id){
		var banners_count = _self.bannerData[banner_area_id].length;
		var current_banner = _self.bannerArea[banner_area_id].current_banner;
		var next_banner = (current_banner + 1)%banners_count;
		var action = false;
		if(current_banner == -1 || _self.bannerData[banner_area_id][current_banner].id != _self.bannerData[banner_area_id][next_banner].id){
			action = true;
		}
		if(action){
			$('#'+_self.bannerArea[banner_area_id].div_id+' div').hide()
			.html(_self.bannerData[banner_area_id][next_banner].html)
			.show();
		}
		_self.bannerArea[banner_area_id].current_banner = next_banner;

		if(_self.bannerArea[banner_area_id].rotate){
			_self.timeOuts[banner_area_id] = setTimeout(function(){
				_self.show_banner(banner_area_id);
			}, _self.bannerArea[banner_area_id].rotate_time*1000);
		}
	}
	
	this.uninit = function(){
		for(var i in _self.timeOuts){
			if(_self.timeOuts.hasOwnProperty(i)){
				clearTimeout(_self.timeOuts[i]);
			}
		}
	}

	_self.init(defOptions);
}
