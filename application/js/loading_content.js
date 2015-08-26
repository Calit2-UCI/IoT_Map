function loadingContent(optionArr){
	var randNumber = Math.round(Math.random(1000)*1000);
	this.properties = {
		loadBlockID: 'user_load_content'+randNumber,
		loadBlockBgID: 'user_load_content_bg'+randNumber,
		loadBlockBgClass: 'load_content_bg',
		linkerObjID: '',
		loadBlockWidth: '50%',
		loadBlockLeftType: 'center',		/// left, right
		loadBlockLeftPoint: 0,
		loadBlockTopType: 'center',		/// top, bottom
		loadBlockTopPoint: 0,
		dir: site_rtl_settings, 			/// rtl
		posPropertyLeft: "left",
		posPropertyRight: "right",
		blockBody: false,
		
		closeBtnID: 'user_load_content_close'+randNumber,
		closeBtnClass: 'load_content_close',
		closeBtnUse: true,
		closeBtnPadding: 5,
		draggable: false,
		showAfterImagesLoad: true,
		contentImages: 0,
		contentLoadedImages: 0,
		
		///// left / right buttons 
		leftBtnID:  'user_load_content_left'+randNumber,
		leftBtnClass: 'load_content_left',
		leftBtnFunction: function(){},
		leftBtnCreated: false,
		
		rightBtnID:  'user_load_content_right'+randNumber,
		rightBtnClass: 'load_content_right',
		rightBtnFunction: function(){},
		rightBtnCreated: false,
		onClose: function(){},
		
		destroyOnReload: true
	}

	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);

		if(_self.properties.dir == 'rtl'){
			_self.properties.posPropertyLeft = "right";
			_self.properties.posPropertyRight = "left";
			
			if(_self.properties.loadBlockLeftType != 'center' && _self.properties.linkerObjID){
				if(_self.properties.loadBlockLeftType == 'left')
					_self.properties.loadBlockLeftType = 'right';
				else
					_self.properties.loadBlockLeftType = 'left';
			}
		}
		
		_self.create_load_block();
		
		$(window).off('resize.loadblock').on('resize.loadblock', function(){
			_self.reposition_load_block();
		});
		
		$(document).on('pjax:start', function(e){
			if(_self.properties.destroyOnReload){
				_self.destroy();
			}else{
				_self.hide_load_block();
			}
		});
	}

	this.extend_errors = function(errors){
		_self.errors = $.extend(_self.errors, errors);
	}
	
	this.destroy = function(){
		_self.hide_load_block();
		$("body")
			.find('#'+_self.properties.loadBlockID+', #'+_self.properties.loadBlockBgID+', #'+_self.properties.closeBtnID+', #'+_self.properties.leftBtnID+', #'+_self.properties.rightBtnID)
			.remove();
	}

	this.create_load_block = function(){
		if(_self.properties.linkerObjID || _self.properties.blockBody){
			var position = 'absolute';
		}else{
			var position = 'fixed';
		}
		if(_self.properties.linkerObjID){
			$("body").append('<div id="'+_self.properties.loadBlockBgID+'"></div><div id="'+_self.properties.loadBlockID+'"><div id="'+_self.properties.loadBlockID+'_content"></div></div>');			
		}else{
			$("body").append('<div id="'+_self.properties.loadBlockBgID+'"><div id="'+_self.properties.loadBlockID+'"><div id="'+_self.properties.loadBlockID+'_content"></div></div></div>');
		}
		$("#"+_self.properties.loadBlockID).css({
			'position': position,
			'display': 'none',
			'z-index': '1000',
			'padding-bottom': '10px'
		});
		if(_self.properties.loadBlockWidth != 'none'){
			$("#"+_self.properties.loadBlockID).css('width', _self.properties.loadBlockWidth);
		}
		
		if(_self.properties.draggable){
			$("#"+_self.properties.loadBlockID).draggable({ handle: "h1" });
		}

		$("#"+_self.properties.loadBlockBgID)
			.addClass(_self.properties.loadBlockBgClass)
			.css({
				'z-index': '1000',
				'width': '100%',
				'height': '100%',
				'position': 'fixed',
				'top': '0',
				'left': '0',
				'display': 'none'
			});
		
		if(_self.properties.closeBtnUse){
			_self.create_close();
		}
	}

	this.show_load_block = function(content, animate){
		animate = animate || false;
		_self.active_bg();
		var height_old = $("#"+_self.properties.loadBlockID+'_content').height();
		$("#"+_self.properties.loadBlockID+'_content').html(content);
		var height = $("#"+_self.properties.loadBlockID+'_content').height();
		if(animate && height_old && height){
			$("#"+_self.properties.loadBlockID+'_content').stop().css('height', height_old + 'px').animate({'height': height+'px'}, 100, function(){$(this).css('height', 'auto')});
		}
		_self.reposition_load_block();
		
		if(_self.properties.showAfterImagesLoad){
			_self.properties.contentImages = $("#"+_self.properties.loadBlockID + ' img').length;
			_self.properties.contentLoadedImages = 0;
			_self.show_after_load();
		}else{
			$("#"+_self.properties.loadBlockID).fadeIn();
			if(_self.properties.closeBtnUse){
				_self.active_close();	
			}
			if(_self.properties.leftBtnCreated){
				_self.active_left();	
			}
			if(_self.properties.rightBtnCreated){
				_self.active_right();	
			}
		}
		if(_self.properties.blockBody){
			var window_width = $(window).width();
			$('body').css('overflow', 'hidden');
			var margin = ($(window).width() - window_width);
			$('body').css('margin-'+_self.properties.posPropertyRight, margin + 'px');
			
			$('#'+_self.properties.loadBlockBgID).css({'overflow-x': 'auto', 'overflow-y': 'scroll', 'display': 'block'});
		}
	}
	
	this.show_after_load = function(){
		if(_self.properties.contentLoadedImages < _self.properties.contentImages){
			$("#"+_self.properties.loadBlockID + ' img').load(function(){
				_self.properties.contentLoadedImages++;
			});
			setTimeout( _self.show_after_load, 300);
		}else{
			_self.reposition_load_block();
			$("#"+_self.properties.loadBlockID).fadeIn();
			if(_self.properties.closeBtnUse){
				_self.active_close();	
			}
			if(_self.properties.leftBtnCreated){
				_self.active_left();	
			}
			if(_self.properties.rightBtnCreated){
				_self.active_right();	
			}
		}
	
	}

	this.reposition_load_block = function(){
		if(_self.properties.linkerObjID && !_self.properties.blockBody){
			_self.properties.loadBlockLeftPoint = $("#"+_self.properties.linkerObjID).offset().left;
			_self.properties.loadBlockTopPoint = $("#"+_self.properties.linkerObjID).offset().top;

			if(_self.properties.loadBlockLeftType == 'right'){
				_self.properties.loadBlockLeftPoint = $(window).width() - _self.properties.loadBlockLeftPoint - $("#"+_self.properties.linkerObjID).width();
			}else if(_self.properties.loadBlockLeftType == 'center'){
				_self.properties.loadBlockLeftPoint = _self.properties.loadBlockLeftPoint - ($("#"+_self.properties.loadBlockID).width() - $("#"+_self.properties.linkerObjID).width())/2;
			}
			if(_self.properties.loadBlockTopType == 'bottom'){
				_self.properties.loadBlockTopPoint = _self.properties.loadBlockTopPoint + $("#"+_self.properties.linkerObjID).height()+15;
			}else if(_self.properties.loadBlockTopType == 'center'){
				_self.properties.loadBlockTopPoint = _self.properties.loadBlockTopPoint - ($("#"+_self.properties.loadBlockID).height() - $("#"+_self.properties.linkerObjID).height())/2;
			}
		}else{
			if(_self.properties.loadBlockLeftType == 'center'){
				_self.properties.loadBlockLeftPoint = ($(window).width() - $("#"+_self.properties.loadBlockID).width())/2;
			}
			if(_self.properties.loadBlockTopType == 'center'){
				_self.properties.loadBlockTopPoint = ($(window).height() - $("#"+_self.properties.loadBlockID).height())/2;
			}
		}

		if(_self.properties.linkerObjID && _self.properties.loadBlockLeftType == 'right'){
			$("#"+_self.properties.loadBlockID).css('right', _self.properties.loadBlockLeftPoint+'px');		
		}else{
			$("#"+_self.properties.loadBlockID).css('left', _self.properties.loadBlockLeftPoint+'px');		
		}
		$("#"+_self.properties.loadBlockID).css('top', _self.properties.loadBlockTopPoint+'px');		
		if(_self.properties.closeBtnUse){
			_self.position_close();	
		}
		if(_self.properties.leftBtnCreated){
			_self.position_left();	
		}
		if(_self.properties.rightBtnCreated){
			_self.position_right();	
		}
	}

	this.hide_load_block = function(){
		if(window.wc_videoStreamUrl){
			window.wc_videoStreamUrl = null;
		}
		if(_self.properties.blockBody){
			$('body').css('overflow', 'auto').css('margin-'+_self.properties.posPropertyRight, 'auto');
		}
		$("#"+_self.properties.loadBlockBgID).hide();
		if($("#"+_self.properties.loadBlockID).css('display') == 'none'){
			return;
		}
		_self.clear_buttons();
		$("#"+_self.properties.loadBlockID).fadeOut(300, function(){
			$("#"+_self.properties.loadBlockID+'_content').html('');
			_self.inactive_bg();
		});
		_self.properties.onClose();
	}
	
	this.clear_buttons = function(){
		if(_self.properties.closeBtnUse){
			_self.inactive_close();		
		}
		if(_self.properties.leftBtnCreated){
			_self.inactive_left();	
		}
		if(_self.properties.rightBtnCreated){
			_self.inactive_right();	
		}	
	}

	this.active_bg = function(){
		$("#"+_self.properties.loadBlockBgID).fadeIn(100).unbind().bind('click', function(e){
			if($(e.target).attr('id') == _self.properties.loadBlockBgID){
				_self.hide_load_block();
			}
		});
	}

	this.inactive_bg = function(){
		if($("#"+_self.properties.loadBlockBgID).css('display') != 'none'){
			$("#"+_self.properties.loadBlockBgID).fadeOut();		
			$("#"+_self.properties.loadBlockBgID).unbind();
		}
	}
	
	this.active_close = function(){
		if($("#"+_self.properties.closeBtnID).css('display') == 'none'){
			_self.position_close();
			$("#"+_self.properties.closeBtnID).bind('click', function(){
				_self.hide_load_block();
			});
			$("#"+_self.properties.closeBtnID).fadeIn();
		}
	}
	
	this.inactive_close = function(){
		if($("#"+_self.properties.closeBtnID).css('display') != 'none'){
			$("#"+_self.properties.closeBtnID).unbind();
			$("#"+_self.properties.closeBtnID).fadeOut();
		}
	}
	
	this.create_close = function(){
		$("#"+_self.properties.loadBlockID).append('<div id="'+_self.properties.closeBtnID+'" class="load_content_close"><i class="fa-remove edge icon-big hover"></i></div>');
		$("#"+_self.properties.closeBtnID).addClass(_self.properties.closeBtnClass).find('i').addClass(_self.properties.closeBtnClass);
		$("#"+_self.properties.closeBtnID).css({
			'cursor': 'pointer',
			'position': 'absolute',
			'display': 'none',
			'z-index': '1002'
		});
		_self.position_close();
	}
	
	this.position_close = function(){
		var padding = _self.properties.closeBtnPadding;
		$("#"+_self.properties.closeBtnID).css({'top': padding + 'px'}).css(_self.properties.posPropertyRight, padding + 'px');
	}

	this.create_left = function(func){
		_self.inactive_left();
		$("#"+_self.properties.loadBlockID).append('<div id="'+_self.properties.leftBtnID+'"></div>');
		_self.properties.leftBtnFunction = func;
		_self.properties.leftBtnCreated = true;
		$("#"+_self.properties.leftBtnID)
			.addClass(_self.properties.leftBtnClass)
			.css({
				'cursor': 'pointer',
				'position': 'absolute',
				'display': 'none',
				'z-index': '1001'
			});
		_self.position_left();		
	}

	this.create_right = function(func){
		_self.inactive_right();
		$("#"+_self.properties.loadBlockID).append('<div id="'+_self.properties.rightBtnID+'"></div>');
		_self.properties.rightBtnFunction = func;
		_self.properties.rightBtnCreated = true;
		$("#"+_self.properties.rightBtnID)
			.addClass(_self.properties.rightBtnClass)
			.css({
				'cursor': 'pointer',
				'position': 'absolute',
				'display': 'none',
				'z-index': '1001'
			});
		_self.position_right();		
	}	
	
	this.active_left = function(){
		_self.position_left();
		$("#"+_self.properties.leftBtnID).unbind('click').bind('click', _self.properties.leftBtnFunction).fadeIn();
	}
	
	this.inactive_left = function(){
		$("#"+_self.properties.leftBtnID).remove();
		_self.properties.leftBtnCreated = false;
	}
	
	this.active_right = function(){
		_self.position_right();
		$("#"+_self.properties.rightBtnID).unbind('click').bind('click', _self.properties.rightBtnFunction).fadeIn();
	}
	
	this.inactive_right = function(){
		$("#"+_self.properties.rightBtnID).remove();
		_self.properties.rightBtnCreated = false;
	}

	this.position_left = function(){
		var padding = 8;
		var main_window_left = parseInt($("#"+_self.properties.loadBlockID).css(_self.properties.posPropertyLeft));
		var main_window_top = parseInt($("#"+_self.properties.loadBlockID).css('top'));
		var main_window_height = $("#"+_self.properties.loadBlockID).height();
		var x = main_window_left + padding;
		var y = main_window_top + (main_window_height - $("#"+_self.properties.leftBtnID).height())/2;
		$("#"+_self.properties.leftBtnID).css(_self.properties.posPropertyLeft, '0px').css('top', '0px');
	}
	this.position_right = function(){
		var padding = 8;
		var main_window_left = parseInt($("#"+_self.properties.loadBlockID).css(_self.properties.posPropertyLeft));
		var main_window_top = parseInt($("#"+_self.properties.loadBlockID).css('top'));
		var main_window_width = $("#"+_self.properties.loadBlockID).width();
		var main_window_height = $("#"+_self.properties.loadBlockID).height();
		var x = main_window_left + main_window_width - $("#"+_self.properties.rightBtnID).width()-padding;
		var y = main_window_top + (main_window_height - $("#"+_self.properties.rightBtnID).height())/2;
		$("#"+_self.properties.rightBtnID).css(_self.properties.posPropertyRight, '0px').css('top', '0px');
	}
	
	this.update_css_styles = function(cssArr, target){
		target = target || 'block';
		var obj = (target == 'bg') ? $("#"+_self.properties.loadBlockBgID) : $("#"+_self.properties.loadBlockID);
		for (var prop in cssArr) {
			obj.css(prop, cssArr[prop]);
		}
		return this;
	}
	
	_self.Init(optionArr);
	return _self;
};
