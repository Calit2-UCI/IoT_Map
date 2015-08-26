function langInlineEditor(options){
	this.properties = {
		siteUrl: '',
		formUrl: 'start/lang_inline_editor/',
		contentObj: new loadingContent(),
		idCloseBtn: 'lie_close',
		idSaveBtn: 'lie_save',
		textarea: false
	};

	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.reload_buttons();
	};
	
	this.reload_buttons = function(){
		$('[lang-editor=button]').each(function(){
			$(this).unbind().bind('click', function(){
				_self.load_editor($(this).attr('lang-editor-type'));
				return false;
			});
		});	
	};
	
	this.load_editor = function(editor_type){
		var langs = {};
		$('input[lang-editor-type="'+editor_type+'"][lang-editor="value"], textarea[lang-editor-type="'+editor_type+'"][lang-editor="value"]').each(function(){
			langs[$(this).attr('lang-editor-lid')] = $(this).val();
		});
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.formUrl + (_self.properties.textarea ? 1 : 0),
			type: 'GET',
			success: function(data){
				var html = $('<div></div>').append(data);
				
				html.find('input[lang-editor="redactor"], textarea[lang-editor="redactor"]').each(function(){
					$(this).val(langs[$(this).attr('lang-id')]);
				});
				
				_self.properties.contentObj.show_load_block(html);
				$('#'+_self.properties.idCloseBtn).unbind().bind('click', function(){
					_self.properties.contentObj.hide_load_block();
					return false;
				});
				$('#'+_self.properties.idSaveBtn).unbind().bind('click', function(){
					_self.save_editor(editor_type);
					return false;
				});
			}
		});
	};
	
	this.save_editor = function(editor_type){
		var langs = {};
		$('input[lang-editor="redactor"], textarea[lang-editor="redactor"]').each(function(){
			langs[$(this).attr('lang-id')] = $(this).val();
		});
		$('input[lang-editor-type="'+editor_type+'"][lang-editor="value"], textarea[lang-editor-type="'+editor_type+'"][lang-editor="value"]').each(function(){
			$(this).val(langs[$(this).attr('lang-editor-lid')]);
		});
		_self.properties.contentObj.hide_load_block();
	};
	

	_self.Init(options);

}
