"use strict";
function storeBanners(optionArr){

	this.properties = {
		banner_path: 'http://www.datingpro.com/banners_dating_free_package/',
        banner_block_class: 'homepage-banners',
        lang_code: 'en',
        langs: {},
        common_ancestor: 'body'
	};

	var _self = this;
	var _banners = ['custom', 'design', 'modules', 'packages'];
	var _return_url = {
            custom: {
                all: 'http://www.pilotgroup.net/'
            },
            design: {
                all: 'http://www.pilotgroup.net/', 
            },
            modules: {
                all: 'http://marketplace.datingpro.com/',
                ru: 'http://marketplace.datingsoftware.ru/'
            },
            packages: {
                all: 'http://www.datingpro.com/dating-software/pricing/'
            }
    };
	
	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
        _self.init_controls();
        _self.loadBanner();
	};
    
    this.uninit = function() {
		$(_self.properties.common_ancestor)
			.off('click', '.' + _self.properties.banner_block_class);
		return this;
	};
	
	this.init_controls = function() {		
		$(_self.properties.common_ancestor)
		.off('click', '.' + _self.properties.banner_block_class).on('click', '.' + _self.properties.banner_block_class, function(){
			_self.goToUrl($(this).data('url'));
		});
	};
    
    this.loadBanner = function () {
        var banner_key = Math.floor(Math.random() * _banners.length); 
        var banner_obj = _self.bannersObject(_banners[banner_key]);
        $('.homepage_block').siblings('h1').before(banner_obj);
    };
	
	this.bannersObject = function (banner) {
        var html_data = '';
        var banner_url = _self.bannerReturnUrl(banner);
        html_data += '<div class="'+ _self.properties.banner_block_class +'" data-url="'+ banner_url +'">';
        html_data +=    '<img src="'+ _self.properties.banner_path + banner +'.jpg" >';
        html_data +=    '<div class="text-block '+banner+'">';
        html_data +=        '<div class="header oh">'+ _self.properties.langs[banner].header +'</div>';
        html_data +=        '<div class="description">'+ _self.properties.langs[banner].description +'</div>';
        html_data +=        '<div class="button">'+ _self.properties.langs[banner].button +'</div>';
        html_data +=    '</div>';
        html_data += '</div>';
        return html_data;
	};
    
    this.bannerReturnUrl = function (banner) {
        var return_url = _return_url[banner].all;
        if (typeof(_return_url[banner][_self.properties.lang_code]) != 'undefined') {
            return_url = _return_url[banner][_self.properties.lang_code];
        }
        return return_url;
    };
    
    this.goToUrl = function (banner_url) {
        window.open(banner_url);
    };
	
	_self.Init(optionArr);
	
}