function Errors(optionArr){
	this.properties = {
		errorBlockID: 'autogen_error_block',
		errorBlockWidth: '300px',
		errorAccess: 'ajax_login_link',
		showErrorTimeout: 7000,
		position: 'center', //// center, right
		dir: site_rtl_settings, /// rtl,
		showTO: null
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.create_error_block();
		return _self;
	}

	this.extend_errors = function(errors){
		_self.errors = $.extend(_self.errors, errors);
		return _self;
	}

	this.create_error_block = function(){
		if(!$("#"+_self.properties.errorBlockID).attr("id")){
			$("body").append('<div id="'+_self.properties.errorBlockID+'"></div>');
			$("#"+_self.properties.errorBlockID).css('display', 'none');
			$("#"+_self.properties.errorBlockID).css('position', 'fixed');
			$("#"+_self.properties.errorBlockID).css('z-index', '1001');
			$("#"+_self.properties.errorBlockID).css('width', _self.properties.errorBlockWidth);
			$("#"+_self.properties.errorBlockID).attr('title', _self.errors.dblclick);
			$("#"+_self.properties.errorBlockID).bind('click', function(event){
//			$("#"+_self.properties.errorBlockID).bind('dblclick', function(event){
				_self.hide_error_block();
			});
		}
		return _self;
	}
	
	this.show_error_block = function(text, type){
		$("#"+_self.properties.errorBlockID).hide();

		if(typeof text === 'object'){
			if(text.length) {
				text = text.join('<br>');
			} else {
				var messages = text;
				text = [];
				for(var key in messages) {
					text.push(messages[key]);
				}
				text = text.join('<br>');
			}
		}

		if(text === _self.properties.errorAccess){
			_self.errors_access();
		}else{
			$("#"+_self.properties.errorBlockID).html('<div class="ajax_notice"><div class="'+type+'">'+text+'</div></div>');
		}

		if(_self.properties.dir == 'ltr'){
			var posPropertyLeft = "left";
			var posPropertyRight = "right";
		}else{
			var posPropertyLeft = "right";
			var posPropertyRight = "left";
		}

		if(_self.properties.position == 'left'){
			$("#"+_self.properties.errorBlockID).css('top', '10px');
			$("#"+_self.properties.errorBlockID).css(posPropertyLeft, '10px');			
		}else if(_self.properties.position == 'center'){
			$("#"+_self.properties.errorBlockID).css('top', '50px');
			var left = ($(window).width() - $("#"+_self.properties.errorBlockID).width())/2;
			$("#"+_self.properties.errorBlockID).css(posPropertyLeft, left+'px');			
		}else if(_self.properties.position == 'right'){
			$("#"+_self.properties.errorBlockID).css('top', '10px');
			$("#"+_self.properties.errorBlockID).css(posPropertyRight, '10px');			
		}
		
		if(text !== _self.properties.errorAccess)
			$("#"+_self.properties.errorBlockID).fadeIn('slow');

		if(_self.properties.showTO){
			clearTimeout(_self.properties.showTO);
		}
		_self.properties.showTO = setTimeout( function(){
			_self.hide_error_block();
		}, _self.properties.showErrorTimeout)
		
		return _self;
	}

	this.hide_error_block = function(){
		$("#"+_self.properties.errorBlockID).fadeOut('slow');
		return _self;
	}
	
	this.errors_access = function(){
		$('html, body').animate({scrollTop: $("#"+_self.properties.errorAccess).offset().top}, 2000);
		$("#"+_self.properties.errorAccess).click();
		return false;
	}

	_self.Init(optionArr);

};
