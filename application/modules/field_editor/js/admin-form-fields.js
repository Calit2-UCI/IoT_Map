function formFields(optionArr){
	this.properties = {
		siteUrl: '',
		fieldsID: 'menu_items',
		btnAddFieldID: 'add_field_form',
		btnAddSectionID: 'add_section_form',

		selectSectionsID: 'section_select',
		selectFieldsID: 'field_select',
		saveSectionsID: 'save_section_btn',
		cancelSectionsID: 'cancel_save_section',
		field_data: {},
		field_names: {},
		formId: 0,

		urlSaveSort: 'admin/field_editor/ajax_form_fields_sorter/',
		urlSave: 'admin/field_editor/ajax_save_form_fields/',
		urlDeleteSection: 'admin/field_editor/ajax_delete_form_section/',

		urlAddSectionForm: 'admin/field_editor/ajax_get_add_section_form/',
		urlAddFieldForm: 'admin/field_editor/ajax_get_add_field_form/',

		urlGetSectionData: 'admin/field_editor/ajax_get_section_data/',
		urlGetFieldData: 'admin/field_editor/ajax_get_field_data/',

		urlFieldSettingsForm: 'admin/field_editor/ajax_get_field_settings_form/',

		curFieldSectionGid: '',
		curFieldGid: '',

		onActionUpdate: true,
		contentObj: new loadingContent({closeBtnClass: 'w'}),
		errorObj: new Errors,
		empty_fields: 'Empty default langs'
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);

		_self.bild_data();
		_self.set_sortable();
		_self.activate_buttons();
	}

	this.bild_data = function(){
		var index = 0;
		var f_index = 0;
		var temp_item = {};
		var f_temp_item = {};
		for(index in _self.properties.field_data){
			temp_item = _self.properties.field_data[index];
			if(temp_item.type == 'field'){
				_self.build_field(temp_item.field.gid, temp_item.field.type, _self.properties.field_names['field_'+temp_item.field.gid], '');
			}else{
				_self.build_section(temp_item.section.gid, _self.properties.field_names['section_'+temp_item.section.gid], temp_item.section.fields);
				_self.set_sortable(temp_item.section.gid);
			}
		}
	}

	this.build_field = function(gid, type, name, section_gid){
		var parent_id = (section_gid != '')?('sfields_'+section_gid):'form_root';
		name = name !== '' ? name : '&nbsp;';
		$('#'+parent_id).append('<li id="field_'+gid+'"><div class="field_type">'+type+':</div> '+name+'</li>');
		$('#field_'+gid).addClass('type_'+type);

		$('#field_'+gid).append('<div class="delete-btn"></div>');
		$('#field_'+gid+' .delete-btn').bind('click', function(){
			_self.delete_field(gid, section_gid);
		});

		if(type == 'select' || type == 'text' || type == 'range'){
			$('#field_'+gid).append('<div class="edit-btn"></div>');
			$('#field_'+gid+' .edit-btn').bind('click', function(){
				_self.field_settings_form(gid);
			});
		}
	}

	this.unbuild_field = function(gid, section_gid){
		$('#field_'+gid).remove();
	}

	this.build_section = function(gid, name, fields){
		$('#form_root').append('<li id="section_'+gid+'"><div class="field_type">section:</div> <span class="section_name">'+name+'</span></li>');

		$('#section_'+gid).append('<div class="delete-btn"></div>');
		$('#section_'+gid+' .delete-btn').bind('click', function(){
			_self.delete_section(gid);
		});

		$('#section_'+gid).append('<div class="edit-btn"></div>');
		$('#section_'+gid+' .edit-btn').bind('click', function(){
			_self.add_section_form(gid);
		});

		$('#section_'+gid).append('<div class="add-btn"></div>');
		$('#section_'+gid+' .add-btn').bind('click', function(){
			_self.add_field_section_form(gid);
		});

		$('#section_'+gid).append('<ul id="sfields_'+gid+'" class="sortable"></ul>');
		$('#section_'+gid).addClass('type_section');

		var index = 0;
		var temp_item = {};
		for(index in fields){
			temp_item = fields[index];
			_self.build_field(temp_item.field.gid, temp_item.field.type, _self.properties.field_names['field_'+temp_item.field.gid], gid);
		}

	}

	this.unbuild_section = function(gid){
		$('#section_'+gid).remove();
	}

	this.set_sortable = function(section_gid){
		if(!section_gid){
			section_ul_gid = 'form_root';
		}else{
			section_ul_gid = 'sfields_'+section_gid;
		}

		$('#'+section_ul_gid).sortable({
			scroll: true,
			forcePlaceholderSize: true,
			placeholder: 'limiter',
			revert: true,
			update: function(event, ui) {
				var section_gid = ($(this).attr('id') == 'form_root')?'':$(this).attr('id').substring(8);

				if(_self.properties.onActionUpdate){
					_self.update_sorting(section_gid);
				}
			}
		});
	}

	this.update_sorting = function(section_gid){
		if(!section_gid){
			section_ul_gid = 'form_root';
		}else{
			section_ul_gid = 'sfields_'+section_gid;
		}

		var raw_data = [];
		var temp_id = '';
		var item_id = '';
		var item_type = '';

		$('#'+section_ul_gid+' > li').each(function(i){
			temp_id = $(this).attr('id');
			if(temp_id.substring(0, 6) == 'field_'){
				item_id = temp_id.substring(6);
				item_type = 'field';
			}else{
				item_id = temp_id.substring(8);
				item_type = 'section';
			}
			raw_data.push(_self.tree_find_element(item_type, item_id));
		});
		if(!section_gid){
			_self.properties.field_data = raw_data;
		}else{
			var raw_item = _self.tree_find_element('section', section_gid);
			raw_item.section.fields = raw_data;
		}
		_self.save_data();
	}

	this.delete_field = function(gid, section){
		///// unbuild field
		_self.unbuild_field(gid, section);

		///// make js tree changes
		_self.tree_find_element('field', gid, 'delete');

		///// send data to server
		_self.save_data();
	}

	this.delete_section = function(gid){
		///// unbuild section
		_self.unbuild_section(gid);

		///// make js tree changes
		_self.tree_find_element('section', gid, 'delete');

		///// send data to server
		_self.save_data();

		///// delete section names
		$.ajax({url: _self.properties.siteUrl + _self.properties.urlDeleteSection + _self.properties.formId + '/' + gid });
	}

	this.activate_buttons = function(){
		/// activate add field button
		$('#'+_self.properties.btnAddFieldID).bind('click', function(){
			_self.add_field_section_form('');
		});

		/// activate add section button
		$('#'+_self.properties.btnAddSectionID).bind('click', function(){
			_self.add_section_form();
		});

		/// activate sorting button
//		$('#'+_self.properties.addLinkID).bind('click', function(){
//			_self.get_option_form('');
//			return false;
//		});
	}

	this.save_data = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlSave + _self.properties.formId,
			type: 'POST',
			data: ({field_data: _self.properties.field_data}),
			cache: false,
			success: function(data){
//				show_error('{/literal}{l i="page_sorting_save_success"}{literal}');
			}
		});

	}

	this.add_section_form = function(section_gid){
		if(!section_gid) section_gid = 0;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlAddSectionForm + _self.properties.formId + '/' + section_gid,
			cache: false,
			success: function(ret){
				_self.properties.contentObj.show_load_block(ret);

				$('#'+_self.properties.cancelSectionsID).bind('click', function(){
					_self.properties.contentObj.hide_load_block();
				});

				$('#'+_self.properties.saveSectionsID).bind('click', function(){
					var section_gid = $('#ajax_section_gid').val();
					default_lang_val = $('input[name^="langs"]').val();
					if (default_lang_val){
						$.ajax({
							url: _self.properties.siteUrl + _self.properties.urlGetSectionData + _self.properties.formId + '/' + section_gid ,
							cache: false,
							type: 'POST',
							data: $('#save_section_name').serialize(),
							dataType: 'json',
							success: function(r){
								if(r){
									_self.properties.field_names = $.extend(_self.properties.field_names, r.names);
									if(r.action == 'add'){
										_self.build_section(r.data.section.gid, r.names['section_'+r.data.section.gid], r.data.section.fields);
										_self.properties.field_data.push(r.data);
										_self.save_data();
									}else{
										$('#section_'+r.data.section.gid+' > span.section_name').html(r.names['section_'+r.data.section.gid]);
									}
									_self.properties.contentObj.hide_load_block();
								}
							}
						});
					} else {
						_self.properties.errorObj.show_error_block(_self.properties.empty_fields, 'error');
					}	

				});
				return false;
			}
		});
		return false;
	}

	this.add_field_section_form = function(section_gid){
		if(!section_gid) section_gid = '';
		_self.properties.curFieldSectionGid = section_gid;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlAddFieldForm + _self.properties.formId,
			cache: false,
			success: function(ret){
				_self.properties.contentObj.show_load_block(ret);
				$('#'+_self.properties.selectSectionsID+' li').bind('click', function(){
					if($(this).attr('gid')){
						_self.add_field_form($(this).attr('gid'));
					}
				});

				$('#fields_close').bind('click', function(){
					_self.properties.contentObj.hide_load_block();
				});

			}
		});
	}


	this.add_field_form = function(editor_section_gid){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlAddFieldForm + _self.properties.formId+'/'+editor_section_gid,
			cache: false,
			success: function(data){
				_self.properties.contentObj.show_load_block(data);
				$('#fields_back').bind('click', function(){
					_self.add_field_section_form(_self.properties.curFieldSectionGid);
				});
				$('#fields_close').bind('click', function(){
					_self.properties.contentObj.hide_load_block();
				});
				$('#'+_self.properties.selectFieldsID+' li').bind('click', function(){
					if(null === $(this).attr('gid')) {
						return false;
					}
					$.ajax({
						url: _self.properties.siteUrl + _self.properties.urlGetFieldData + _self.properties.formId + '/' + $(this).attr('gid'),
						cache: false,
						dataType: 'json',
						success: function(r){
							if(r){
								_self.properties.field_names = $.extend(_self.properties.field_names, r.names);
								_self.build_field(r.data.field.gid, r.data.field.type, r.names['field_'+r.data.field.gid], _self.properties.curFieldSectionGid);

								if(_self.properties.curFieldSectionGid != ''){
									var temp_section = _self.tree_find_element('section', _self.properties.curFieldSectionGid);
									if (typeof temp_section.section.fields == 'undefined') {
										temp_section.section.fields = [];
									}
									temp_section.section.fields.push(r.data);
								}else{
									_self.properties.field_data.push(r.data);
								}
								_self.properties.contentObj.hide_load_block();
								_self.save_data();
							}
						}
					});

				});
			}
		});
		return false;
	}

	this.field_settings_form = function(field_gid){
		_self.properties.curFieldGid = field_gid;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlFieldSettingsForm + _self.properties.formId+'/'+field_gid,
			cache: false,
			success: function(data){
				if(data){
					_self.properties.contentObj.show_load_block(data);
					$('#cancel_save_settings').bind('click', function(){
						_self.properties.contentObj.hide_load_block();
					});
					$('#save_settings_btn').bind('click', function(){
						var settings = {
							search_type: $('#field_search_type').val(),
							view_type: 	 $('#field_view_type_'+$('#field_search_type').val()+' select').val()
						}
						var temp_field = _self.tree_find_element('field', _self.properties.curFieldGid);
						temp_field.settings = settings;
						_self.properties.contentObj.hide_load_block();
						_self.save_data();
					});
				}
			}
		});
		return false;
	}

	this.tree_find_element = function(type, gid, action){
		var index = 0;
		var temp_item = {};
		var f_index = 0;
		var f_temp_item = {};
		for(index in _self.properties.field_data){
			temp_item = _self.properties.field_data[index];
			if(type == 'field'){
				if(temp_item.type == 'field'){
					if(temp_item.field.gid == gid ){
						if(action == 'delete'){
							delete _self.properties.field_data[index];
						}else{
							return temp_item;
						}
					}
				}else{
					for(f_index in temp_item.section.fields){
						if(temp_item.section.fields[f_index].type == 'field'){
							if(temp_item.section.fields[f_index].field.gid == gid ){
								if(action == 'delete'){
									delete _self.properties.field_data[index].section.fields[f_index];
								}else{
									return temp_item.section.fields[f_index];
								}
							}
						}
					}
				}
			}else{
				if(temp_item.type == 'section'){
					if(temp_item.section.gid == gid ){
						if(action == 'delete'){
							delete _self.properties.field_data[index];
						}else{
							return temp_item;
						}
					}
				}
			}
		}
		return [];
	}

	_self.Init(optionArr);
}