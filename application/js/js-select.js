function jsSelect(load_options){
	var randNumber = Math.round(Math.random(1000)*1000);
	this.properties = {
		selectBlockID: 'js_select'+randNumber,
		selectBlockBgID: 'js_select_bg'+randNumber,
		selectBlockBgClass: 'load_content_bg',
		elementStyleInit: 'element-on',
		elementStyleActive: 'element-down',
		selectStyle: 'select-style',
		openSelectAction: 'click', /// mouseenter, click , dbclick
		element: false,
		selectData:{},
		onchangeFunc: function(id, value, element){},
		flagActivated: false
	}

	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.create_select_block();
	}

	this.create_select_block = function(){
		if(!$("#"+_self.properties.selectBlockID).attr("id")){
			$("body").append('<div id="'+_self.properties.selectBlockID+'"></div>');
			$("#"+_self.properties.selectBlockID).addClass(_self.properties.selectStyle);
			$("#"+_self.properties.selectBlockID).css('display', 'none');
			$("#"+_self.properties.selectBlockID).css('position', 'absolute');
			$("#"+_self.properties.selectBlockID).css('z-index', '1500');

			$("#"+_self.properties.selectBlockID).append('<ul></ul>');

			for (var opt_id in _self.properties.selectData) {
				$("#"+_self.properties.selectBlockID+">ul").append('<li id="'+opt_id+'">'+_self.properties.selectData[opt_id]+'</li>');
			}
		}

		$("body").append('<div id="'+_self.properties.selectBlockBgID+'"></div>');
		$("#"+_self.properties.selectBlockBgID).addClass(_self.properties.selectBlockBgClass);
		$("#"+_self.properties.selectBlockBgID).css('display', 'none');
		$("#"+_self.properties.selectBlockBgID).css('position', 'fixed');
		$("#"+_self.properties.selectBlockBgID).css('z-index', '500');
		$("#"+_self.properties.selectBlockBgID).css('width', '1px');
		$("#"+_self.properties.selectBlockBgID).css('height', '1px');
		$("#"+_self.properties.selectBlockBgID).css('left', '1px');
		$("#"+_self.properties.selectBlockBgID).css('top', '1px');
	}

	this.init_select = function(obj){
		if(_self.properties.flagActivated) return;
		_self.properties.element = obj;
		if(_self.properties.elementStyleInit){
			$(_self.properties.element).addClass(_self.properties.elementStyleInit);
		}
		$(_self.properties.element).bind(_self.properties.openSelectAction, function(){
			_self.activate_select();
		});
	}

	this.free_select = function(){
		if(_self.properties.flagActivated) return;
		$(_self.properties.element).unbind(_self.properties.openSelectAction);
		if(_self.properties.elementStyleInit){
			$(_self.properties.element).removeClass(_self.properties.elementStyleInit);
		}
		_self.properties.element = false;
	}

	this.activate_select = function(){
		_self.inactive_bg();
		_self.active_bg();

		if(_self.properties.elementStyleActive){
			$(_self.properties.element).addClass(_self.properties.elementStyleActive);
		}

		var select_left = $(_self.properties.element).offset().left;
		var select_top = $(_self.properties.element).offset().top + $(_self.properties.element).height() +5;
		var select_width = $(_self.properties.element).width() + 10;
		$("#"+_self.properties.selectBlockID).css('left', select_left+'px');
		$("#"+_self.properties.selectBlockID).css('top', select_top+'px');
		$("#"+_self.properties.selectBlockID).css('min-width', select_width+'px');

		$("#"+_self.properties.selectBlockID+" > ul > li").unbind().bind('click', function(){
				_self.properties.onchangeFunc($(this).attr('id'), $(this).html(), _self.properties.element);
				_self.deactivate_select();
		});

		$("#"+_self.properties.selectBlockID).slideDown();
		_self.properties.flagActivated = true;
	}

	this.deactivate_select = function(){
		$("#"+_self.properties.selectBlockID).slideUp();

		$("#"+_self.properties.selectBlockID+" > ul > li").unbind('click');

		if(_self.properties.elementStyleActive){
			$(_self.properties.element).removeClass(_self.properties.elementStyleActive);
		}

		_self.inactive_bg();
		_self.properties.flagActivated = false;
	}

	this.active_bg = function(){
		$("#"+_self.properties.selectBlockBgID).css('width', $(window).width()+'px');
		$("#"+_self.properties.selectBlockBgID).css('height', $(window).height()+'px');
		$("#"+_self.properties.selectBlockBgID).bind('click', function(){
			_self.deactivate_select();
		});
		$("#"+_self.properties.selectBlockBgID).show();
	}

	this.inactive_bg = function(){
		$("#"+_self.properties.selectBlockBgID).css('width', '1px');
		$("#"+_self.properties.selectBlockBgID).css('height', '1px');
		$("#"+_self.properties.selectBlockBgID).unbind('click');
		$("#"+_self.properties.selectBlockBgID).hide();
	}

	_self.Init(load_options);

}
