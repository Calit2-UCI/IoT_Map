function hlBox(optionArr){
	this.properties = {
		dataClass: 'data',
		elementsIDs: [],
		force: false
	};
	var _self = this;
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);

		for(var m in _self.properties.elementsIDs){
			_self.initBox(_self.properties.elementsIDs[m]);
		}

		$(document).one('pjax:start', function(e){
			_self.uninit();
		});
		return this;
	};
	
	this.uninit = function(){
		_self.clear();
		$('body').unbind('click.hlBox');
		return this;
	};
	
	this.clear = function(){
		for(var m in _self.properties.elementsIDs){
			_self.unsetBox(_self.properties.elementsIDs[m]);
		}
		return this;
	};
	
	this.initBox = function(box_id){
		if(!$('#'+box_id+'_box').size() || $('#'+box_id+'_box').data('init')) return;
		$('#'+box_id+'_box').data('init', '1');
		_self.setDefault(box_id);
		
		$('#'+box_id+'_box').find('li').off('click').on('click', function(){
			_self.toggleBox(box_id, $(this));
		});
		return this;
	};
	
	this.unsetBox = function(box_id){
		$('#'+box_id+'_box').removeAttr('hlb_init').off().find('li').off();
		return this;
	};
	
	this.toggleBox = function(box_id, item){
		var multiselect = $('#'+box_id+'_box').data('multiselect');
		if(item.hasClass('active')){
			if(multiselect){
				_self.unsetActiveBox(box_id, item);
			}
		}else{
			_self.setActiveBox(box_id, item);
		}
		return this;
	};
	
	this.setActiveBox = function(box_id, item){
		var multiselect = $('#'+box_id+'_box').data('multiselect');
		var input_name = $('#'+box_id+'_box').data('input');
		if(multiselect){
			input_name += '[]';
		}else{
			$('#'+box_id+'_box').find('li.active').each(function(){
				_self.unsetActiveBox(box_id, $(this));
			});
		}
		var value = item.data('value');
		item.addClass('active');
		if(!$('#'+box_id+'_inputs').find('input[value="'+value+'"]').size()){
			$('#'+box_id+'_inputs').append('<input type="hidden" name="'+input_name+'" value="'+value+'" />').find('input').change();
		}
		return this;
	};
	
	this.unsetActiveBox = function(box_id, item){
		var input_name = $('#'+box_id+'_box').data('input');
		var value = item.data('value');
		item.removeClass('active');
		$('#'+box_id+'_inputs').find('input[value="'+value+'"]').remove();
		return this;
	};
	
	this.setDefault = function(box_id){
		var multiselect = $('#'+box_id+'_box').data('multiselect');
		var selected = $('#'+box_id+'_box').data('defaults');
		$('#'+box_id+'_inputs').find('input').remove();
		$('#'+box_id+'_box').find('li').removeClass('active');
		if(typeof selected === 'object'){
			for(var i in selected) if(selected.hasOwnProperty(i)){
				_self.setActiveBox(box_id, $('#'+box_id+'_box li[data-value="'+selected[i]+'"]'));
			}
		}else{
			_self.setActiveBox(box_id, $('#'+box_id+'_box li[data-value="'+selected+'"]'));
		}
		return this;
	};
	
	_self.Init(optionArr);		
}	