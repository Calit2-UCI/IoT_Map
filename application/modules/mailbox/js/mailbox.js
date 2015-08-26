function mailbox(optionArr){
	this.properties = {
		siteUrl: '/',
		folder: 'inbox',
		page: 1,
		mainContent: '#mailbox_content',
		topMenu: '#mailbox_top_menu',
		selectAllElement: '#select_all_checkbox',
		checkboxMask: 'input[data-role="msg-checkbox"]',
		deleteButton: '#mailbox_delete',
		deleteMessagesUrl: 'mailbox/ajax_delete_messages',
		deleteMessageButton: '#message_delete',
		deleteMessageUrl: 'mailbox/ajax_delete_message',
		deleteForeverButton: '#mailbox_delete_forever',
		deleteForeverUrl: 'mailbox/ajax_delete_forever',
		markSpamButton: '#mark_spam',
		markSpamMessagesUrl: 'mailbox/ajax_mark_spam_messages',
		markSpamMessageButton: '#message_mark_spam',
		markSpamMessageUrl: 'mailbox/ajax_mark_spam_message',
		unmarkSpamMessagesButton: '#unmark_spam',
		unmarkSpamMessagesUrl: 'mailbox/ajax_unmark_spam_messages',
		unmarkSpamMessageButton: '#message_unmark_spam',
		unmarkSpamMessageUrl: 'mailbox/ajax_unmark_spam_message',
		untrashButton: '#mailbox_untrash',
		untrashUrl: 'mailbox/ajax_untrash_messages',
		untrashMessageButton: '#message_untrash',
		untrashMessageUrl: 'mailbox/ajax_untrash_message',
		threadUrl: 'mailbox/ajax_thread',
		viewMessageUrl: 'mailbox/view',
		editMessageUrl: 'mailbox/edit',
		writeMessageButton: '#btn_write_message',
		savePeriod: 10,
		saveFormId: '#write_form',
		saveMessageUrl: 'mailbox/ajax_save_draft',
		messageId: 0,
		sendMessageButton: '#btn_send_message',
		sendMessageUrl: 'mailbox/ajax_send_message',
		formMessageUrl: 'mailbox/ajax_show_form',
		fullWriteButton: '#write_message_full',
		fullWriteUrl: 'mailbox/write',
		deleteAttachUrl: 'mailbox/ajax_delete_attach',
		saveMessageButton: '#save_message',
		saveReplyUrl: 'mailbox/ajax_save_reply',
		replyMessageUrl: 'mailbox/ajax_reply_message',
		replyFormButton: '#reply_message',
		replyFormUrl: 'mailbox/view',
		replyId: 0,
		deleteThreadButton: '#delete_thread',
		deleteThreadUrl: 'mailbox/ajax_delete_thread',
		searchMessagesButton: '#btn_search_messages',
		searchMessagesUrl: 'mailbox/ajax_search_messages',
		editMessageButton: '#edit_message',
		editMessageUrl: 'mailbox/edit',
		statusTimeout: 2,
		contactId: 0,
		accessAvailableView: null,
		readAvailableView: null,
		sendAvailableView: null,
		windowObj: null,
		loadContent: false,
		writeMessage: false,
	};
	
	var _self = this;
	var save_request_lock = false;
	var save_request_data = [];
	var save_reply_request_lock = false;
	var save_reply_request_data = [];
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.init_objects();
		_self.init_controls();		
	};
	
	this.uninit = function(){
		_self.main_content_obj.off('click', _self.properties.selectAllElement);
		_self.main_content_obj.off('click', _self.properties.deleteButton);
		_self.main_content_obj.off('click', _self.properties.markSpamButton);
		_self.main_content_obj.off('click', _self.properties.unmarkSpamMessagesButton);
		_self.mark_spam_message_btn_obj.off('click');
		_self.unmark_spam_message_btn_obj.off('click');
		_self.main_content_obj.off('click', _self.properties.deleteForeverButton);
		_self.main_content_obj.off('click', _self.properties.untrashButton);
		_self.untrash_message_btn_obj.off('click');
		_self.main_content_obj.off('click', '.btn-toggle-message');
		_self.main_content_obj.off('click', '.btn_thread');
		_self.main_content_obj.off('click', '.btn_read_message');
		_self.main_content_obj.off('click', '.btn_edit_message');
		_self.main_content_obj.off('click', '.pointer input[type=checkbox]');
		_self.write_message_btn_obj.off('click');
		_self.save_message_btn_obj.off('click');
		_self.send_message_btn_obj.off('click');
		_self.full_write_btn_obj.off('click');
		_self.reply_form_btn_obj.off('click');
		_self.delete_thread_btn_obj.off('click');
		_self.delete_message_btn_obj.off('click');
		_self.top_menu_obj.off('click', '.a');
		_self.main_content_obj.off('click', '.btn_delete_attach');
		//_self.search_messages_btn_obj.off('click');
		_self.main_content_obj.off('click', '.btn_search_messages');
		_self.edit_message_btn_obj.off('click');
		
		if(_self.properties.loadContent) delete _self.properties.windowObj;
		if(_self.properties.writeMessage) clearInterval(_self.save_interval_obj);
	}
	
	this.init_objects = function(){
		_self.main_content_obj = $(_self.properties.mainContent);
		//_self.select_all_obj = $(_self.properties.selectAllElement);
		//_self.mark_spam_btn_obj = $(_self.properties.markSpamButton);
		//_self.unmark_spam_messages_btn_obj = $(_self.properties.unmarkSpamMessagesButton);
		_self.mark_spam_message_btn_obj = $(_self.properties.markSpamMessageButton);
		_self.unmark_spam_message_btn_obj = $(_self.properties.unmarkSpamMessageButton);
		//_self.delete_btn_obj = $(_self.properties.deleteButton);
		//_self.delete_forever_btn_obj = $(_self.properties.deleteForeverButton);
		//_self.untrash_btn_obj = $(_self.properties.untrashButton);
		_self.untrash_message_btn_obj = $(_self.properties.untrashMessageButton);
		_self.write_message_btn_obj = $(_self.properties.writeMessageButton);
		_self.save_message_btn_obj = $(_self.properties.saveMessageButton);
		_self.send_message_btn_obj = $(_self.properties.sendMessageButton);
		_self.full_write_btn_obj = $(_self.properties.fullWriteButton);
		_self.reply_form_btn_obj = $(_self.properties.replyFormButton);
		_self.delete_thread_btn_obj = $(_self.properties.deleteThreadButton);
		_self.delete_message_btn_obj = $(_self.properties.deleteMessageButton);
		_self.top_menu_obj = $(_self.properties.topMenu);
		//_self.search_messages_btn_obj = $(_self.properties.searchMessagesButton);
		_self.edit_message_btn_obj = $(_self.properties.editMessageButton);
		if(_self.properties.loadContent) _self.properties.windowObj = new loadingContent({loadBlockWidth: '520px', closeBtnClass: 'w'});
		if(_self.properties.writeMessage) _self.save_interval_obj = setInterval(function(){_self.save_message.call(self)}, _self.properties.savePeriod*1000);
	}
	
	this.init_controls = function(){
		_self.main_content_obj.off('change', _self.properties.selectAllElement).on('change', _self.properties.selectAllElement, function(){
			_self.main_content_obj.find(_self.properties.checkboxMask).prop('checked', $(this).is(':checked'));
		})
		_self.main_content_obj.off('click', _self.properties.deleteButton).on('click', _self.properties.deleteButton, function(){
			_self.delete_messages();
		})
		_self.main_content_obj.off('click', _self.properties.markSpamButton).on('click', _self.properties.markSpamButton, function(){
			_self.mark_spam_messages();
		})
		_self.main_content_obj.off('click', _self.properties.unmarkSpamMessagesButton).on('click', _self.properties.unmarkSpamMessagesButton, function(){
			_self.unmark_spam_messages();
		})
		_self.mark_spam_message_btn_obj.off('click').on('click', function(){
			_self.mark_spam_message();
		})
		_self.unmark_spam_message_btn_obj.off('click').on('click', function(){
			_self.unmark_spam_message();
		})
		_self.main_content_obj.off('click', _self.properties.deleteForeverButton).on('click', _self.properties.deleteForeverButton, function(){
			_self.delete_forever();
		})
		_self.main_content_obj.off('click', _self.properties.untrashButton).on('click', _self.properties.untrashButton, function(){
			_self.untrash_messages();
		})
		_self.untrash_message_btn_obj.off('click').on('click', function(){
			_self.untrash_message();
		})
		_self.main_content_obj.off('click', '.btn-toggle-message').on('click', '.btn-toggle-message', function(){
			_self.toggle_message($(this));
		})
		_self.main_content_obj.off('click', '.btn_thread').on('click', '.btn_thread', function(){
			_self.thread_messages($(this));
			return false;
		})
		_self.main_content_obj.off('click', '.btn_read_message').on('click', '.btn_read_message', function(){
			_self.is_read_message($(this));
		})
		_self.main_content_obj.off('click', '.btn_edit_message').on('click', '.btn_edit_message', function(){
			_self.is_edit_message($(this));
		})
		_self.main_content_obj.off('click', '.pointer input[type=checkbox]').on('click', '.pointer input[type=checkbox]', function(e){
			e.stopPropagation();
		})	
		_self.write_message_btn_obj.off('click').on('click', function(){
			_self.is_write_message();
		})
		_self.save_message_btn_obj.off('click').on('click', function(){
			_self.save_message(null, true);
		})
		_self.send_message_btn_obj.off('click').on('click', function(){
			_self.is_send_message();
		})
		_self.reply_form_btn_obj.off('click').on('click', function(){
			var url = _self.properties.siteUrl + _self.properties.replyFormUrl + '/' + _self.properties.messageId;
			locationHref(url + '#reply_form');
		})
		_self.full_write_btn_obj.off('click').on('click', function(){
			_self.save_message(function(data){
				var url = _self.properties.siteUrl + _self.properties.fullWriteUrl;
				if(_self.properties.messageId) url += '/' + _self.properties.messageId;
				locationHref(url);
			});
		})
		_self.delete_thread_btn_obj.off('click').on('click', function(){
			_self.delete_thread();
		})
		_self.delete_message_btn_obj.off('click').on('click', function(){
			_self.delete_message();
		})
		_self.top_menu_obj.off('click', '.a').on('click', '.a', function(){
			$(this).parent().parent().find('input[type=checkbox]').prop('checked', false);
		})
		_self.main_content_obj.off('click', '.btn_delete_attach').on('click', '.btn_delete_upload', function(){
			_self.delete_attach($(this));
			return false;
		})
		/*_self.search_messages_btn_obj.off('click').on('click', function(){
			_self.search_messages();
		})*/
		_self.main_content_obj.off('click', '.btn_search_messages').on('click', '.btn_search_messages', function(){
			var keywords = $(this).parents('ul').find('#mail_keywords').val();
			_self.search_messages(keywords);
		})
		_self.edit_message_btn_obj.off('click').on('click', function(){
			var url = _self.properties.siteUrl + _self.properties.editMessageUrl + '/' + _self.properties.messageId;
			locationHref(url);
		})
	}
	
	this.get_marked_ids = function(){
		var ids = [];
		_self.main_content_obj.find(_self.properties.checkboxMask + ':checked').each(function() {
			ids.push($(this).attr('value'))
		});
		return ids;
	}
	
	this.delete_messages = function(){
		ids = _self.get_marked_ids();
		if(ids.length == 0) return;
		_self.properties.page = 1;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.deleteMessagesUrl + '/' + _self.properties.folder + '/' + _self.properties.page,
			type: 'POST',
			dataType : 'json',
			cache: false,
			data: {ids: ids},
			success: function(data){
				if(data.status == 1){
					_self.main_content_obj.html(data.content)
					error_object.show_error_block(data.message, 'success');
					//locationHref(_self.properties.siteUrl + 'mailbox/' + _self.properties.folder);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.delete_message = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.deleteMessageUrl + '/' + _self.properties.messageId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.status == 1){
					error_object.show_error_block(data.message, 'success');
					locationHref(_self.properties.siteUrl + _self.properties.viewMessageUrl + '/' + _self.properties.messageId);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.mark_spam_messages = function(){
		ids = _self.get_marked_ids();
		if(ids.length == 0) return;
		_self.properties.page = 1;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.markSpamMessagesUrl + '/' + _self.properties.folder + '/' + _self.properties.page,
			type: 'POST',
			dataType : 'json',
			cache: false,
			data: {ids: ids},
			success: function(data){
				if(data.status == 1){
					_self.main_content_obj.html(data.content)
					error_object.show_error_block(data.message, 'success');
					//locationHref(_self.properties.siteUrl + 'mailbox/' + _self.properties.folder);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.mark_spam_message = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.markSpamMessageUrl + '/' + _self.properties.messageId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.status == 1){
					error_object.show_error_block(data.message, 'success');
					locationHref(_self.properties.siteUrl + _self.properties.viewMessageUrl + '/' + _self.properties.messageId);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.unmark_spam_messages = function(){
		ids = _self.get_marked_ids();
		if(ids.length == 0) return;
		_self.properties.page = 1;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.unmarkSpamMessagesUrl + '/' + _self.properties.folder + '/' + _self.properties.page,
			type: 'POST',
			dataType : 'json',
			cache: false,
			data: {ids: ids},
			success: function(data){
				if(data.status == 1){
					_self.main_content_obj.html(data.content)
					error_object.show_error_block(data.message, 'success');
					//locationHref(_self.properties.siteUrl + 'mailbox/' + _self.properties.folder);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.unmark_spam_message = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.unmarkSpamMessageUrl + '/' + _self.properties.messageId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.status == 1){
					error_object.show_error_block(data.message, 'success');
					locationHref(_self.properties.siteUrl + _self.properties.viewMessageUrl + '/' + _self.properties.messageId);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.delete_forever = function(){
		ids = _self.get_marked_ids();
		if(ids.length == 0) return;
		_self.properties.page = 1;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.deleteForeverUrl + '/' + _self.properties.folder + '/' + _self.properties.page,
			type: 'POST',
			dataType : 'json',
			cache: false,
			data: {ids: ids},
			success: function(data){
				if(data.status == 1){
					_self.main_content_obj.html(data.content)
					error_object.show_error_block(data.message, 'success');
					//locationHref(_self.properties.siteUrl + 'mailbox/' + _self.properties.folder);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.untrash_message = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.untrashMessageUrl + '/' + _self.properties.messageId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.status == 1){
					error_object.show_error_block(data.message, 'success');
					locationHref(_self.properties.siteUrl + _self.properties.viewMessageUrl + '/' + _self.properties.messageId);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.untrash_messages = function(){
		ids = _self.get_marked_ids();
		if(ids.length == 0) return;
		_self.properties.page = 1;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.untrashUrl + '/' + _self.properties.folder + '/' + _self.properties.page,
			type: 'POST',
			dataType : 'json',
			cache: false,
			data: {ids: ids},
			success: function(data){
				if(data.status == 1){
					_self.main_content_obj.html(data.content)
					error_object.show_error_block(data.message, 'success');
					//locationHref(_self.properties.siteUrl + 'mailbox/' + _self.properties.folder);
				} else{
					error_object.show_error_block(data.message, 'error');
				}
			}
		});
	}
	
	this.toggle_message = function(item){
		var i = item.find('i');
		if(i.hasClass('fa-caret-right')){
			i.removeClass('fa-caret-right').addClass('fa-caret-down');
			item.parent().find('.teaser').removeClass('teaser').addClass('full');
		}else{
			i.removeClass('fa-caret-down').addClass('fa-caret-right');
			item.parent().find('.full').removeClass('full').addClass('teaser');
		}
	}
	
	this.thread_messages = function(item){
		var page = parseInt(item.attr('data-page'));
		var direction = item.attr('data-dir');
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.threadUrl + '/' + item.attr('data-id') + '/' + direction + '/' + page,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				var container = item.parent();
				item.html(data.count);
				item.attr('data-page', page + 1);
				if(!data.count){
					item.parent().hide();
				}
				if(direction == 'next'){
					container.after(data.content);
				}else{
					container.before(data.content);
				}
			}
		});
	}

	this.is_read_message = function(item){
		_self.properties.messageId = item.attr('data-id');
		_self.properties.readAvailableView.check_available(_self.properties.messageId);
	}
	
	this.read_message = function(){
		locationHref(_self.properties.siteUrl + _self.properties.viewMessageUrl + '/' + _self.properties.messageId);
	}
	
	this.is_edit_message = function(item){
		_self.properties.messageId = item.attr('data-id');
		_self.edit_message();
	}
	
	this.edit_message = function(){
		locationHref(_self.properties.siteUrl + _self.properties.editMessageUrl + '/' + _self.properties.messageId);
	}

	this.is_write_message = function(item){
		_self.properties.accessAvailableView.check_available();
	}

	this.write_message = function(user_id, type){
		type = type || 'full';
		
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.formMessageUrl + '/' + user_id + '/' + type,
			type: 'GET',
			cache: false,
			success: function(data){
				_self.properties.windowObj.show_load_block(data);
			}
		});
	}
	
	this.is_save_message = function(data){
		for(var i in data){
			switch(data[i].name){
				case 'id_to_user': if(data[i].value > 0) return true; break;
				case 'name_to_user': break;
				default: if(data[i].value) return true; break;
			}
		}
		return false;
	}
	
	this.save_message = function(callback, noCheckChange){
		var save_data = $(_self.properties.saveFormId).serializeArray();
		if(!noCheckChange && !_self.is_save_message(save_data)) return;
		_self.do_save_message(callback, save_data);
	}
	
	this.do_save_message = function(callback, save_data){
		var url = _self.properties.siteUrl + _self.properties.saveMessageUrl;
		if(_self.properties.messageId){
			url += '/' + _self.properties.messageId;
		}else if(_self.save_request_lock){
			if(_self.save_request_data){
				_self.save_request_data.push({callback: callback, data: save_data});
				return;
			}
		}else{
			_self.save_request_lock = true;
		}
		
		$.ajax({
			url: url,
			type: 'POST',
			data: save_data,
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.message_id) _self.properties.messageId = data.message_id;
				if(callback){
					if(data.message_id) callback(data)
				}else{
					$('#save_status').html(data.status).show();
					setTimeout(function(){$('#save_status').hide()}, _self.properties.statusTimeout*1000);
				}
				if(_self.save_request_lock){
					_self.save_request_lock = false;
					_self.do_save_requests(!data.message_id);
				}
			}
		});
	}
	
	this.do_save_requests = function(only_req){
		for(var i in _self.save_request_data){
			_self.do_save_message(_self.save_request_data[i].callback, _self.save_request_data[i].data);
			delete _self.save_request_data[i];
			if(only_req) break;
		}
	}
	
	this.save_reply = function(callback, noCheckChange){
		var save_data = $(_self.properties.saveFormId).serializeArray();
		if(!noCheckChange && !_self.is_save_message(save_data)) return;
		_self.do_save_reply(callback, save_data);
	}
	
	this.do_save_reply = function(callback, save_data){
		var url = _self.properties.siteUrl + _self.properties.saveReplyUrl + '/' + _self.properties.messageId;
		if(_self.properties.replyId){
			url += '/' + _self.properties.replyId;
		}else if(_self.save_reply_request_lock){
			_self.save_reply_request_data.push({callback: callback, data: save_data});
			return;
		}else{
			_self.save_reply_request_lock = true;
		}
		
		$.ajax({
			url: url,
			type: 'POST',
			data: save_data,
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.reply_id) _self.properties.replyId = data.reply_id;
				if(callback){
					if(data.reply_id) callback(data)
				}else{
					$('#save_status').html(data.status).show();
					setTimeout(function(){$('#save_status').hide()}, _self.properties.statusTimeout*1000);
				}
				if(_self.save_reply_request_lock){
					_self.do_save_reply_requests(!data.reply_id);
					_self.save_reply_request_lock = false;
				}
			}
		});
	}
	
	this.do_save_reply_requests = function(only_req){
		for(var i in _self.save_reply_request_data){
			_self.do_save_message(_self.save_reply_request_data[i].callback, _self.save_reply_request_data[i].data);
			delete _self.save_reply_request_data[i];
			if(only_req) break;
		}
	}
	
	this.is_send_message = function(){
		_self.properties.sendAvailableView.check_available();
	}
	
	this.send_message = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.sendMessageUrl + '/' + _self.properties.messageId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.error){
					error_object.show_error_block(data.error, 'error');
				}else{
					error_object.show_error_block(data.success, 'success');
                    if (document.location.href == _self.properties.siteUrl + _self.properties.fullWriteUrl + '/' + _self.properties.messageId) {
                        locationHref(_self.properties.siteUrl + 'mailbox/outbox');
                    } else {
                        _self.properties.messageId = 0;
                        $('.load_content_close').click();
                    }
				}
			}
		});
	}
	
	this.reply_message = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.replyMessageUrl + '/' + _self.properties.replyId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.error){
					error_object.show_error_block(data.error, 'error');
				}else{
					error_object.show_error_block(data.success, 'success');
					locationHref(_self.properties.siteUrl + 'mailbox/outbox');
				}
			}
		});
	}
	
	this.delete_attach = function(item){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.deleteAttachUrl + '/' + item.attr('data-id') + '/' + _self.properties.messageId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.error){
					error_object.show_error_block(data.error, 'error');
				}else{
					//error_object.show_error_block(data.success, 'success');
					var attaches = item.parents('#attaches')
					item.parent().parent().remove();
					if(attaches.find('ul').children().length == 0) attaches.hide();
				}
			}
		});
	}

	this.delete_thread = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.deleteThreadUrl + '/' + _self.properties.messageId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.error){
					error_object.show_error_block(data.error, 'error');
				}else{
					error_object.show_error_block(data.success, 'success');
					locationHref(_self.properties.siteUrl + 'mailbox/' + _self.properties.folder);
				}
			}
		});
	}
	
	this.search_messages = function(keywords){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.searchMessagesUrl + '/' + _self.properties.folder + '/' + _self.properties.page + '/' + encodeURIComponent(keywords),
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				_self.main_content_obj.html(data.content)
			}	
		});
	}

	_self.Init(optionArr);
}
