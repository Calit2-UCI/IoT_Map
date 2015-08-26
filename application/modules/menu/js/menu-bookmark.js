function menuBookmark(optionArr){

	this.properties = {
		bmID: 'bookmark',
		bmElement: 'li',
		bmActiveClass: 'active',
		bmExeptClass: 'no-tab',
		
		tCounter: 0,
		tWidth: 0,
		padding: 5,
		'float': 'right'
	}
	
	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		
		if(!_self.properties.tWidth){
			_self.properties.tWidth = parseInt($('#'+_self.properties.bmID).width());
		}
		
		var tempWidth=0; maxWidth = 0; temp = 0;
		$('#'+_self.properties.bmID+' '+_self.properties.bmElement).each(function(){
			var el = $(this);
			el.css({'min-width': 0});
			if(!el.hasClass(_self.properties.bmExeptClass)){
				_self.properties.tCounter++;
				temp = parseInt(el.outerWidth(true));
				if(maxWidth == 0 || maxWidth < temp) maxWidth = temp;
				tempWidth+=temp;
			}
		});
		
		iter = 0;
		while(tempWidth > _self.properties.tWidth && iter < 100){
		    tempWidth=maxWidth; var str = '';
		    $('#'+_self.properties.bmID+' '+_self.properties.bmElement).each(function(){
			if(!$(this).hasClass(_self.properties.bmExeptClass)){
			    if(!$(this).attr('initial')) $(this).attr('initial', $(this).text());
			    str = $(this).find('a').text();
			    if(str.length > 4){
				str = str.slice(0, -4)+"...";
			    }
			    $(this).attr('changed', str);
			    $(this).find('a').text(str);
			    if(!$(this).hasClass(_self.properties.bmActiveClass)){
				tempWidth+=parseInt($(this).outerWidth(true));
			    }
			}
		    });
		    iter++;
		}
		
		if(iter > 0){
			var active = $('#'+_self.properties.bmID+' '+_self.properties.bmElement+'.'+_self.properties.bmActiveClass);
			active.find('a').text(active.attr('initial'));
	
			$('#'+_self.properties.bmID+' '+_self.properties.bmElement).bind('click', function(){
				if(!$(this).hasClass(_self.properties.bmExeptClass)){
					_self.active_item($(this));
				}
			});
			$('#'+_self.properties.bmID+' '+_self.properties.bmElement).bind('mouseenter', function(){
				if(!$(this).hasClass(_self.properties.bmExeptClass)){
					_self.active_item($(this));
				}
			});
		}
	}	

	this.active_item = function(el){
		if(!el.hasClass(_self.properties.bmExeptClass)){
			$('#'+_self.properties.bmID+' '+_self.properties.bmElement).each(function(){
				if(!$(this).hasClass(_self.properties.bmExeptClass)){
					$(this).find('a').text($(this).attr('changed'));	
				}
			});
			el.find('a').text(el.attr('initial'));	
		}
	}
	
	_self.Init(optionArr);

};
