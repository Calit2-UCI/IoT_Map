function selectBox(optionArr){
	this.properties = {
		labelClass: 'label',
		arrowClass: 'arrow',
		dropdownClass: 'dropdown',
		dropdownAutosize: false,
		dropdownRight: false,
		dataClass: 'data',
		elementsIDs: [],
		force: false
	};
	var _self = this;
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		if(_self.properties.force){
			_self.clear();
		}
		for(var m in _self.properties.elementsIDs){
			_self.initBox(_self.properties.elementsIDs[m]);
		}
		//_self.initBg();

		$(document).one('pjax:start', function(e){
			_self.uninit();
		});
	};
	
	this.uninit = function(){
		_self.clear();
		$('body').find('#select_box_bg').remove();
		$(window).off('resize.selectBox');
		$('body').unbind('click.selectBox');
	};
	
	this.initBg = function(){
		if($('body').find('#select_box_bg').size()){
			return;
		}
		$('body').append('<div id="select_box_bg"></div>');
		$('#select_box_bg').css({
			'display': 'none',
			'position': 'fixed',
			'z-index': '98999',
			'left': '0',
			'top': '0',
			'width': '100%',
			'height': '100%'
		});
	};
	
	this.clear = function(){
		for(var m in _self.properties.elementsIDs){
			_self.unsetBox(_self.properties.elementsIDs[m]);
		}
	};
	
	this.expandBg = function(box_id){
		$('#select_box_bg').show().unbind('click').bind('click', function(){
			_self.closeBox(box_id);
		});
	};
	
	this.collapseBg = function(){
		$('#select_box_bg').hide().unbind();
	};
	
	this.initBox = function(box_id){
		if($('#'+box_id+'_box').length<1 || $('#'+box_id+'_box[sb_init="1"]').size()) return;
		$('#'+box_id+'_box').attr('sb_init', '1');
		_self.createDropDown(box_id);
		$('#'+box_id+'_box, #'+box_id+'_dropdown').unbind('click').bind('click', function(e){
			if($('#'+box_id+'_dropdown').is(':visible')){
				_self.closeBox(box_id);
			}else{
				e.cancelBubble = true; if (e.stopPropagation) e.stopPropagation();
				_self.openBox(box_id);
			}
		});
		_self.setDefault(box_id);
		$('#'+box_id+'_dropdown').on('click', 'li', function(){
			_self.setActiveBox(box_id, $(this));
		});
		$(window).on('resize.selectBox', function(){
			if($('#'+box_id+'_dropdown').is(':visible')){
				_self.resetDropDown(box_id);
			}
		});
	};
	
	this.unsetBox = function(box_id){
		$('#'+box_id+'_box').removeAttr('sb_init').unbind('click').off('click');
		$('#'+box_id+'_dropdown').unbind('click').off('click').off('click', 'li').remove();
	};
	
	this.openBox = function(box_id){
		_self.expandBg(box_id);
		for(var m in _self.properties.elementsIDs){
			if($('#'+_self.properties.elementsIDs[m]+'_dropdown').is(':visible')){
				_self.closeBox(_self.properties.elementsIDs[m]);
			}
		}
		_self.resetDropDown(box_id);
		$('#'+box_id+'_dropdown').slideDown(100, function(){
			var active_top = $(this).find('li.active').position().top - $(this).find('ul').position().top - ($(this).height()/2 - $(this).find('li.active').outerHeight()/2);
			$(this).scrollTop(active_top);
		});
		$('body').unbind('click.selectBox').bind('click.selectBox', function(e){
			_self.closeBox(box_id);
		});
	};

	this.createDropDown = function(box_id){
		var data = $('#'+box_id+'_box .'+_self.properties.dataClass).html();
		$('body').append('<div class="'+_self.properties.dropdownClass+'" id="'+box_id+'_dropdown">'+data+'</div>');
		_self.resetDropDown(box_id);
	};

	this.resetDropDown = function(box_id){
		var top = $('#'+box_id+'_box').offset().top + $('#'+box_id+'_box .label').outerHeight();
		var width = $('#'+box_id+'_box').width();
		
		if(_self.properties.dropdownAutosize){
			$('#'+box_id+'_dropdown').css({'display': 'block', 'visibility': 'hidden'});
			var calc_width = 0;
			var first_li = $('#'+box_id+'_dropdown').find('li:first');
			var padding = first_li.outerWidth() - first_li.width();
			$('#'+box_id+'_dropdown').find('li span').each(function(){
				if($(this).width() + padding > calc_width){
					calc_width = $(this).width() + padding;
				}
			});
			if(calc_width > width){
				width = calc_width;
			}
		}
		
		var left = $('#'+box_id+'_box').offset().left+'px';
		if(_self.properties.dropdownRight){
			left = $('#'+box_id+'_box').offset().left + $('#'+box_id+'_box').width() - width + 'px';
		}

		$('#'+box_id+'_dropdown').css({
			width: width+'px',
			left: left,
			top: top +'px',
			display: 'none',
			visibility: 'visible'
		});
	};
	
	this.closeBox = function(box_id){
		$('body').unbind('click.selectBox');
		_self.collapseBg();
		$('#'+box_id+'_dropdown').stop(true).slideUp(100);
	};
	
	this.setActiveBox = function(box_id, item){
		$('#'+box_id+'_dropdown li').removeClass('active');
		item.addClass('active');
		$('#'+box_id+'_box .'+_self.properties.labelClass).html(item.text());
		$('#'+box_id).val(item.attr('gid')).change();
	};
	
	this.resetValues = function(box_id, data, selected){
		if(!selected) selected = $('#'+box_id).val();
		var selected_used = false;

		$('#'+box_id+'_dropdown > ul > li[gid!=""]').remove();

		if(data){
			for(var m in data){
				$('#'+box_id+'_dropdown ul').append('<li gid="'+m+'">'+data[m]+'</li>');
				if(data[m].id == selected){
					_self.setActiveBox(box_id, $('#'+box_id+'_dropdown li[gid="'+m+'"]'));
					selected_used = true;
				}
			}	
		}
		if(selected == 0 || !selected_used){
			_self.setDefault(box_id);
		}
	};
	
	this.setDefault = function(box_id){
		var selected = $('#'+box_id).val();
		if(!selected) selected = $('#'+box_id+'_dropdown > ul > li:first').attr('gid');
		_self.setActiveBox(box_id, $('#'+box_id+'_dropdown li[gid="'+selected+'"]'));
	};
	_self.Init(optionArr);		
}	