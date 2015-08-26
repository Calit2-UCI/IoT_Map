function init_carousel_controls(optionArr){
	this.properties = {
		carousel: {},
		carousel_images_count: 5,
		carousel_total_images:10,
		btnNext: 'directionright',
		btnPrev: 'directionleft',
		rtl: false,
		offsetP : 0,
		offsetN: 0,
		scroll: 'auto',
		scroll_max: 4,
		activeClass: 'active',
		inactiveClass: 'bg-delimiter_color'
	};

	var _self = this;


	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);

		_self.properties.carousel.off('itemfullyvisiblein.jcarousel', 'li:first-child').on('itemfullyvisiblein.jcarousel', 'li:first-child', function(e, carousel) {
			$(_self.properties.btnPrev).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
		}).off('itemfullyvisibleout.jcarousel', 'li:first-child').on('itemfullyvisibleout.jcarousel', 'li:first-child', function(e, carousel) {
			$(_self.properties.btnPrev).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
		}).off('itemfullyvisiblein.jcarousel', 'li:last-child').on('itemfullyvisiblein.jcarousel', 'li:last-child', function(e, carousel) {
			$(_self.properties.btnNext).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
		}).off('itemfullyvisibleout.jcarousel', 'li:last-child').on('itemfullyvisibleout.jcarousel', 'li:last-child', function(e, carousel) {
			$(_self.properties.btnNext).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
		});
		
		var fullyvisible = _self.properties.carousel.jcarousel('fullyvisible');
		var items = _self.properties.carousel.jcarousel('items');
		var scroll = _self.properties.scroll;
		if(scroll == 'auto'){
			scroll = fullyvisible.size() - 1;
			if(scroll < 1){
				scroll = 1;
			}
			if(scroll > _self.properties.scroll_max){
				scroll = _self.properties.scroll_max;
			}
		}

		$(_self.properties.btnNext).off('click').on('click', function(){
			_self.properties.carousel.jcarousel('scroll', '+='+scroll);
			
			var fullyvisible2 = _self.properties.carousel.jcarousel('fullyvisible');
			if(fullyvisible2.filter(':first')[0] != items.filter(':first')[0]){
				$(_self.properties.btnPrev).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
			}else{
				$(_self.properties.btnPrev).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
			}
			if(fullyvisible2.filter(':last')[0] != items.filter(':last')[0]){
				$(_self.properties.btnNext).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
			}else{
				$(_self.properties.btnNext).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
			}
		});
		$(_self.properties.btnPrev).off('click').on('click', function(){
			_self.properties.carousel.jcarousel('scroll', '-='+scroll);
			
			var fullyvisible2 = _self.properties.carousel.jcarousel('fullyvisible');
			if(fullyvisible2.filter(':first')[0] != items.filter(':first')[0]){
				$(_self.properties.btnPrev).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
			}else{
				$(_self.properties.btnPrev).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
			}
			if(fullyvisible2.filter(':last')[0] != items.filter(':last')[0]){
				$(_self.properties.btnNext).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
			}else{
				$(_self.properties.btnNext).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
			}
		});
		
		if(fullyvisible.filter(':first')[0] != items.filter(':first')[0]){
			$(_self.properties.btnPrev).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
		}else{
			$(_self.properties.btnPrev).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
		}
		if(fullyvisible.filter(':last')[0] != items.filter(':last')[0]){
			$(_self.properties.btnNext).removeClass(_self.properties.inactiveClass).addClass(_self.properties.activeClass);
		}else{
			$(_self.properties.btnNext).removeClass(_self.properties.activeClass).addClass(_self.properties.inactiveClass);
		}
		
		return this;
	};
	
	this.uninit = function(){
		$(_self.properties.btnNext).off('click');
		$(_self.properties.btnPrev).off('click');
		_self.properties.carousel
			.off('itemfullyvisiblein.jcarousel', 'li:first-child')
			.off('itemvisibleout.jcarousel', 'li:first-child')
			.off('itemfullyvisiblein.jcarousel', 'li:last-child')
			.off('itemvisibleout.jcarousel', 'li:last-child');
		
		return this;
	};

	_self.Init(optionArr);
	
	return this;
}