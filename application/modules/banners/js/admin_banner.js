function AdminBanners(defOptions){
	this.properties = {
		siteUrl: '',
		loadFormUrl: 'admin/banners/ajax_show_form/',
		loadImageFormUrl: 'admin/banners/ajax_show_form/banner_image_form/',
		loadHtmlFormUrl: 'admin/banners/ajax_show_form/banner_html_form/',
		loadGroupsUrl: 'admin/banners/ajax_get_groups/',
		
		banner_id: '',
		init_banner_form: false
	}
	var default_config = {
		altFormat: 'yy-mm-dd',
		dateFormat: 'dd MM yy',
		firstDay: 1
	}

	var _self = this;

	this.init = function(options){
		_self.properties = $.extend(_self.properties, options);
		if(_self.properties.init_banner_form){
			_self.init_banner_form();
		}
	}

	this.load_form = function(val){
		if (val != ''){
			var url = _self.properties.siteUrl + _self.properties.loadFormUrl + val + '/' + _self.properties.banner_id; 
			$('#second_form').load(url);
		}else{
			$('#second_form').html('');
		}
	}

	this.load_groups = function(place_id){
		if(place_id){
			var url = _self.properties.siteUrl + _self.properties.loadGroupsUrl  + place_id; 
			if( _self.properties.banner_id != ''){
				url = url + '/' + _self.properties.banner_id;
			}
			$('#banner_groups').load(url);
		}
	}

	this.init_banner_form = function(){
	
//		_self.load_form($('#banner_type').val());
		
		$('#banner_type').bind('change', function(){
			_self.load_form($(this).val());
		});

//		_self.load_groups($('#banner_place').val());
		
		$('#banner_place').bind('change', function(){
			_self.load_groups($(this).val());
		});
	}

	this.initImageForm = function(){
		var date_config = default_config;
		$(".datepicker").each(function(){
			var tempId = $(this).attr('id');
			var date_val = $("#"+tempId).val();
			date_config['altField'] = '#'+tempId + '_hide';
			$("#"+tempId).datepicker(date_config);
		});
	}

	_self.init(defOptions);
}