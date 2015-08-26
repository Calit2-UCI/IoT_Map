function checkBox(optionArr){
	this.properties = {
		labelClass: 'label',
		boxClass: 'box',
		checkedClass: 'checked',
		hoveredClass: 'hovered',
		elementsIDs: []	
	};
	var _self = this;
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		for(var m in _self.properties.elementsIDs){
			_self.initBox(_self.properties.elementsIDs[m]);
		}
	};
	
	this.initBox = function(box_id){
		if($('#'+box_id+'_cbox').length<1) return;

		$('#'+box_id+'_cbox .'+_self.properties.labelClass+', #'+box_id+'_cbox .'+_self.properties.boxClass)
			.unbind('mouseenter')
			.bind('mouseenter', function(){
				var gid = $(this).attr('gid');
				$('#'+box_id+'_cbox .'+_self.properties.boxClass+'[gid='+gid+']').addClass(_self.properties.hoveredClass);
			})
			.unbind('mouseleave')
			.bind('mouseleave', function(){
				var gid = $(this).attr('gid');
				$('#'+box_id+'_cbox .'+_self.properties.boxClass+'[gid='+gid+']').removeClass(_self.properties.hoveredClass);
			});
		
		$('#'+box_id+'_cbox .'+_self.properties.labelClass+', #'+box_id+'_cbox .'+_self.properties.boxClass)
			.unbind('click')
			.bind('click', function(){
				var gid = $(this).attr('gid');
				if($('#'+box_id+'_cbox .'+_self.properties.boxClass+'[gid='+gid+'].'+_self.properties.checkedClass).length > 0){
					_self.uncheckBox(box_id, gid);
				}else{
					_self.checkBox(box_id, gid);
				}
			});
		$('#'+box_id+'_cbox_check_all').unbind('click').bind('click', function(){
			_self.checkBoxAll(box_id);
		});
		$('#'+box_id+'_cbox_uncheck_all').unbind('click').bind('click', function(){
			_self.uncheckBoxAll(box_id);
		});
	};
	
	this.checkBox = function(box_id, gid){
		$('#'+box_id+'_cbox .'+_self.properties.boxClass+'[gid='+gid+']').addClass(_self.properties.checkedClass);
		$('#'+box_id+'_cbox input[value='+gid+']').remove();
		
		var input_name = $('#'+box_id+'_cbox').attr('iname');
		if(!input_name) input_name = box_id;
		
		var option_count = $('#'+box_id+'_cbox .'+_self.properties.boxClass).length;
		var multi_input = "";
		if(option_count > 1)	multi_input = "[]";
		 
		$('#'+box_id+'_cbox').append('<input type="hidden" name="'+input_name+multi_input+'" value="'+gid+'">');
		$('#'+box_id+'_cbox input[value='+gid+']:first').change();
	};
	
	this.uncheckBox = function(box_id, gid){
		$('#'+box_id+'_cbox .'+_self.properties.boxClass+'[gid='+gid+']').removeClass(_self.properties.checkedClass);
		$('#'+box_id+'_cbox input[value='+gid+']:first').remove();
	};
	
	this.checkBoxAll = function(box_id){
		$('#'+box_id+'_cbox .'+_self.properties.boxClass).each(function(){
			var gid = $(this).attr('gid');
			_self.checkBox(box_id, gid);
		});
	};
	
	this.uncheckBoxAll = function(box_id){
		$('#'+box_id+'_cbox .'+_self.properties.boxClass).each(function(){
			var gid = $(this).attr('gid');
			_self.uncheckBox(box_id, gid);
		});
	};
	
	_self.Init(optionArr);		
}	