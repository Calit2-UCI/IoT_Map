function langEditor(load_options){
	this.properties = {
		siteUrl: '',
		BlockID: 'lang_editor_js_block',
		HandleID: 'lang_handle',
		ContentID: 'lang_editor_content',
		hightlightTextClass: 'lang-mark',
		hightlightButtonClass: 'lang-button-mark',
		data: {}
	}

	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.create_editor();

	}
	
	this.create_editor = function(){
		$('#'+_self.properties.HandleID).bind('click', function(){
			_self.toggle_block();
		});
		for (var opt_id in _self.properties.data) {

			$("#"+_self.properties.ContentID+"> div[langid="+opt_id+"]").bind('mouseenter', function(){
				var opt_id = $(this).attr('langid');
				_self.hightlight_text(opt_id, _self.properties.data[opt_id].edit_type);
			}).bind('mouseleave', function(){
				var opt_id = $(this).attr('langid');
				_self.unhightlight_text(opt_id, _self.properties.data[opt_id].edit_type);
			});

			var edit_url = _self.properties.siteUrl +'languages/ajax_pages_save/'+_self.properties.data[opt_id].module_gid+'/'+_self.properties.data[opt_id].gid+'/'+_self.properties.data[opt_id].lang_id;
			$('#'+_self.properties.ContentID+' > div[langid='+opt_id+'] > div.value').editable(edit_url, {
				type: 'textarea',
				tooltip: 'Edit...',
				placeholder: '<font class="hide_text">Edit...</font>',
				name : 'text',
				submit : 'Save',
				height: 'auto',
				width: 200,
				callback: function(value, settings){
					$(this).html(value);

					var langid = $(this).attr('langid');
					if(_self.properties.data[langid].edit_type == 'text'){
						$('span[langid='+langid+']').each(function(){
							$(this).html(value);
						});
					}else
						$('input[langid='+langid+']').each(function(){
							$(this).val(value);
						});
				},
			});
		}


	}
	this.toggle_block = function(){
		$('#'+_self.properties.ContentID).slideToggle();

	}

	this.hightlight_text = function(langid, type){
		if(type=='text'){
			$('span[langid='+langid+']').addClass(_self.properties.hightlightTextClass);
		}else{
			$('input[langid='+langid+']').addClass(_self.properties.hightlightButtonClass);
		}
	}

	this.unhightlight_text = function(langid, type){
		if(type=='text'){
			$('span[langid='+langid+']').removeClass(_self.properties.hightlightTextClass);
		}else{
			$('input[langid='+langid+']').removeClass(_self.properties.hightlightButtonClass);
		}
	}

	_self.Init(load_options);

}