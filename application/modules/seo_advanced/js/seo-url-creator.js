function seoUrlCreator(optionArr){
	this.properties = {
		siteUrl: '',
		urlID: 'url_block',
		data: {},
		options: {},
		temp_data: {},
		fixedClass: 'fixed',
		textClass: 'text',
		tplClass: 'tpl',
		optClass: 'opt',
		postfixClass: 'postfix',
		editBlockTextID: 'url_text_edit',
		editBlockTplID: 'url_tpl_edit',
		hiddenID: 'url_data',
		showForm: true
	}

	this.id = 0;
	
	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.create_url();
	}

	this.create_url = function(){
		$('#'+_self.properties.urlID).append('<li class="'+_self.properties.fixedClass+' url-block">'+_self.properties.siteUrl+'</li>');

		$('#'+_self.properties.urlID).sortable({
			items: 'li.sortable',
			forcePlaceholderSize: true,
			placeholder: 'limiter',
			revert: true,
			update: function(event, ui) { 
				_self.refresh_data();
			},
			start: function( event, ui ){
				_self.properties.showForm = false;
			},
			stop: function( event, ui ){
				_self.properties.showForm = true;
			}
		});
				
		var postfix = false;		
		for(var part in _self.properties.data){
			if(_self.properties.data[part].type == 'postfix') postfix = true;
			if(postfix && _self.properties.data[part].type == 'text') _self.properties.data[part].type = 'postfix_text';
			_self.save_li(0, _self.properties.data[part]);
		}
		
	//	$('#'+_self.properties.urlID + '> li.sortable').bind('click', function(){
	//		var id = $(this).attr('id');
	//		_self.show_form($(this));
	//	});

		_self.refresh_data();
	}
	
	this.save_li = function(id, data){
		var liClass = liVarname = liValue = liOutput = liVartype = '';
		var liType = data.type;
		var liNum = data.var_num;
		
		var block = '';

		switch(data.type){
			case 'text':
				liClass = _self.properties.textClass + ' url-block';
				liValue = liOutput = data.value;
			break;
			case 'postfix_text':
				liClass = _self.properties.textClass;
				liValue = liOutput = data.value;
			break;
			case 'postfix':
				liClass = _self.properties.postfixClass;
				liVarname = data.var_name;
				liVartype = data.var_type;
				liValue = data.var_default;
				liOutput = '['+liVarname+']';
			break;
			case 'tpl':
				liClass = _self.properties.tplClass + ' url-block';
				liVarname = data.var_name;
				liVartype = data.var_type;
				liValue = data.var_default;
				liOutput = '['+liVarname+'|'+liValue+']';
			break;
			default:
				liClass = _self.properties.optClass + ' url-block';
				liVarname = data.var_name;
				liVartype = data.var_type;
				liValue = data.var_default;
				liOutput = '['+liVarname+'|'+liValue+']';
			break;
		}
		
		if(!id){
			if(_self.id > 0 && data.type !='postfix' && data.type !='postfix_text') liClass += ' sortable';
			id = 'li'+ (++_self.id);
			block += '<li id="'+id+'" class="'+liClass+'" li-type="'+liType+'" li-num="'+liNum+'" li-varname="'+liVarname+'" li-value="'+liValue+'" li-vartype="'+liVartype+'">'+liOutput+'</li>';
			if(data.type =='postfix' || data.type =='postfix_text'){
				$('#'+_self.properties.urlID).append(block);
			}else{
				$('#'+_self.properties.urlID).find('.url-block').last().after(block);
			}
			$('#'+id).bind('click', function(){
				if($('#'+id).find('input,select').length > 0 || !_self.properties.showForm) return;
				_self.show_form($(this).attr('id'));
			});
			$('#'+_self.properties.urlID).sortable('refresh');
		}else{
			$('#'+id).attr({'li-value': liValue, 'li-varname': liVarname}).text(liOutput);
		}
	}
	
	this.remove_li = function(id){
		$('#'+id).remove();
		$('#'+_self.properties.urlID).sortable('refresh');
	}
	
	this.refresh_data = function(){

		_self.properties.data = [];
		$('#'+_self.properties.urlID + '> li').each(function(i, item){
			if(item.getAttribute('class') == _self.properties.fixedClass + ' url-block') return;
			
			switch(item.getAttribute('li-type')){
				case 'text':
				case 'postfix_text':
					_self.properties.data.push({
						type: item.getAttribute('li-type'),
						value: item.getAttribute('li-value'),
						var_num: '',
						var_name: '',
						var_type: '',
						var_default: '',
					});
				break;
				case 'postfix':
					_self.properties.data.push({
						type: 'postfix',
						value: '',
						var_num: item.getAttribute('li-num'),
						var_name: item.getAttribute('li-varname'),
						var_type: item.getAttribute('li-vartype'),
						var_default: '',
					});
				break;
				case 'tpl':
					_self.properties.data.push({
						type: 'tpl',
						value: '',
						var_num: item.getAttribute('li-num'),
						var_name: item.getAttribute('li-varname'),
						var_type: item.getAttribute('li-vartype'),
						var_default: item.getAttribute('li-value'),
					});
				break;
				case 'opt':
					_self.properties.data.push({
						type: 'opt',
						value: '',
						var_num: item.getAttribute('li-num'),
						var_name: item.getAttribute('li-varname'),
						var_type: item.getAttribute('li-vartype'),
						var_default: item.getAttribute('li-value')
					});
				break;
			}			
		});
		_self.serialize_data();
	}

	this.clear_url = function(){
		$('#'+_self.properties.urlID + '>li').each(function(){
			$(this).remove();
		});
	}
	
	this.show_form = function(id, data){
		var item = $('#'+id);
		var type = item.attr('li-type');
		var value = item.attr('li-value');
		if(value.length <= 0) value='empty';

		switch(type){
			case 'text':
			case 'postfix_text':
				var output = '<input type="text" name="value" value="'+value+'">';
				output += ' <a href="#" class="icn-confirm"></a>';
				if(type == 'text' && id != 'li1'){
					output += ' <a href="#" class="icn-delete"></a>';
				}else{
					output += ' <a href="#" class="icn-cancel"></a>';
				}
			break;
			case 'postfix':
				var options_str = '';
				var options_arr = _self.properties.options[item.attr('li-num')-1];
				var var_name = item.attr('li-varname');
				for(var i in options_arr) options_str += '<option '+(i == var_name ? 'selected' : '')+'>'+i+'</option>';
				var output = '[<select name="value">'+options_str+'</select>]';
				output += ' <a href="#" class="icn-confirm"></a>';
				output += ' <a href="#" class="icn-cancel"></a>';
			break;
			case 'opt':
				var output = '['+item.attr('li-varname')+'|<input type="text" name="value" value="'+value+'">]';
				output += ' <a href="#" class="icn-confirm"></a>';
				output += ' <a href="#" class="icn-delete"></a>';
			break;
			default:
				var options_str = '';
				var options_arr = _self.properties.options[item.attr('li-num')-1];
				var var_name = item.attr('li-varname');
				for(var i in options_arr) options_str += '<option '+(i == var_name ? 'selected' : '')+'>'+i+'</option>';
				var output = '[<select name="value">'+options_str+'</select>|<input type="text" name="value" value="'+value+'">]';
				output += ' <a href="#" class="icn-confirm"></a>';
				output += ' <a href="#" class="icn-cancel"></a>';
			break;
		}
		item.html(output);
		item.find('a.icn-confirm').bind('click', function(){
			_self.save_form(id);
			return false;	
		});
		item.find('a.icn-cancel').bind('click', function(){
			_self.cancel_form(id);
			return false;	
		});
		item.find('a.icn-delete').bind('click', function(){
			_self.delete_form(id);
			return false;	
		});
		$('#'+_self.properties.urlID).sortable('disable');
	}
	
	this.cancel_form = function(id){
		var item = $('#'+id);
		var type = item.attr('li-type');
		var value = item.attr('li-value');
		if(value.length <= 0) value='empty';

		switch(type){
			case 'text':
			case 'postfix_text':
				var output = value;
			break;
			case 'postfix':
				var output = '['+item.attr('li-varname')+']';
			break;
			default:
				var output = '['+item.attr('li-varname')+'|'+value+']';
			break;
		}		
		item.html(output);
		_self.refresh_data();
		$('#'+_self.properties.urlID).sortable('enable');
	}
	
	this.save_form = function(id){
		var item = $('#'+id);
		var type = item.attr('li-type');
		
		switch(type){
			case 'text':
			case 'postfix_text':
				var text = item.find('input').val();
				if(text.length <= 0){_self.cancel_form(id); return false;}
				var data = {
					type: 'text',
					value: text,
					var_num: '',
					var_name: '',
					var_type: '',
					var_default: ''
				}
			break;
			case 'postfix':
				var select = item.find('select').val();
				var data = {
					type: 'postfix',
					value: '',
					var_num: item.attr('li-num'),
					var_name: select,
					var_type: item.attr('li-vartype'),
					var_default: ''
				}
			break;
			case 'tpl':
				var text = item.find('input').val();
				if(text.length <= 0) text='empty';
				var select = item.find('select').val();
				var data = {
					type: 'tpl',
					value: '',
					var_num: item.attr('li-num'),
					var_name: select,
					var_type: item.attr('li-vartype'),
					var_default: text
				}
			break;
			case 'opt':
				var text = item.find('input').val();
				if(text.length <= 0) text='empty';
				var data = {
					type: 'opt',
					value: '',
					var_num: item.attr('li-num'),
					var_name: item.attr('li-varname'),
					var_type: item.attr('li-vartype'),
					var_default: text
				}
			break;
		}
		_self.save_li(id, data);
		_self.refresh_data();
		$('#'+_self.properties.urlID).sortable('enable');
	}
	
	this.save_block = function(id, type, text, var_count, var_type, var_name){
		switch(type){
			case 'text':
			case 'postfix_text':
				if(text.length <= 0) return false;
				var data = {
					type: 'text',
					value: text,
					var_num: '',
					var_name: '',
					var_type: '',
					var_default: ''
				}
			break;
			case 'postfix':
				var select = item.find('select').val();
				var data = {
					type: 'postfix',
					value: '',
					var_num: item.attr('li-num'),
					var_name: select,
					var_type: item.attr('li-vartype'),
					var_default: ''
				}
			break;
			case 'tpl':
			if(text.length <= 0) text='empty';
				var data = {
					type: 'tpl',
					value: '',
					var_num: var_count,
					var_name: var_name,
					var_type: var_type,
					var_default: text
				}
			break;
			case 'opt':
				if(text.length <= 0) text='empty';
				var data = {
					type: 'opt',
					value: '',
					var_num: var_count,
					var_name: var_name,
					var_type: var_type,
					var_default: text
				}
			break;
		}
		_self.save_li(id, data);
		_self.refresh_data();
		return true;
	}
	
	this.add_block = function(id, type, text, var_count, var_type, var_name){
		switch(type){
			case 'text':
			case 'postfix_text':
				if(text.length <= 0) return false;
				var data = {
					type: type,
					value: text,
					var_num: '',
					var_name: '',
					var_type: '',
					var_default: ''
				}
			break;
			case 'opt':
				if(text.length <= 0) text='empty';
				var data = {
					type: 'opt',
					value: '',
					var_num: var_count,
					var_name: var_name,
					var_type: var_type,
					var_default: text
				}
			break;
		}
		_self.save_li(id, data);
		_self.refresh_data();
		return true;
	}
	
	this.delete_form = function(id){
		var index = 0;
		_self.remove_li(id);
		_self.refresh_data();
		$('#'+_self.properties.urlID).sortable('enable');
	}

	this.serialize_data = function(){
		var ret = '{';
		var index = 0;
		for(var id in _self.properties.data ){
			if(_self.properties.data[id].type == 'postfix_text') _self.properties.data[id].type = 'text'
			ret += '"'+index+'":{';
			ret += '"type":"'+_self.properties.data[id].type+'",';
			ret += '"value":"'+_self.properties.data[id].value+'",';
			ret += '"var_num":"'+_self.properties.data[id].var_num+'",';
			ret += '"var_name":"'+_self.properties.data[id].var_name+'",';
			ret += '"var_type":"'+_self.properties.data[id].var_type+'",';
			ret += '"var_default":"'+_self.properties.data[id].var_default+'"';
			ret += '},';
			index++;
		}
		if(ret.length>1)
			ret = ret.substring(0, ret.length-1);
		ret += "}";
		$('#'+_self.properties.hiddenID).val(ret);
	}

	_self.Init(optionArr);
}
