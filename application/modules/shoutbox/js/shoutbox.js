function Shoutbox(params){

	this.properties = {
		id: 'shoutbox',
		site_url: '',
		active_ajax_timeout: 3,
		inactive_ajax_timeout: 6,
		id_user: 0,
		max_id: 0,
		min_id: 0,
		count_newm: 0,
		msg_max_length: 150,
		user_name: '',
		position: 'right',
		get_messages_url: 'shoutbox/ajax_get_messages',
		post_message_url: 'shoutbox/ajax_post_message',
		new_msgs: {messages: {}, count_new: 0},

		shoutbox_msg_btns_id: 'shoutbox_msg_btns',
		is_dom_init: false,
		load_old: false
	}

	var _p = {
		shoutbox_obj: {},
		msg_textarea_obj: {},
		msg_chat_obj: {},
		msg_btns_obj: {},
		counter_new_messages_obj: {},
		btn_in_top_obj: {},
		counter_to: {},

		xhr_send_message: null,
	};

	this.init_vars = function(){
		_p.xhr_send_message = null;

		this.shoutbox_messages = {max_id: 0, min_id: 0,msg: []};
	}

	var _self = this;
	_self.init_vars();

	
	this.init = function(options){
		options = options || {};
		$.extend(true, _self.properties, options);
		
		if(!_self.properties.id_user){
			return this;
		}

		_p.initDomObjects();
		_self.shoutbox_messages = {max_id: _self.properties.max_id, min_id: _self.properties.min_id, msg: []};
		_p.renderMessages(_self.properties.new_msgs, 'init');
		_p.initMultiRequest();
		$('body').append('<div id="'+_self.properties.id+'_full_text" class="shoutbox_full_text" style="display:none;"><div>')
			.on('mouseleave', '#'+_self.properties.id+'_full_text', function(){$('#'+_self.properties.id+'_full_text').fadeOut(0);});

		_self.initScroll();
		return this;
	}
	

	this.initScroll = function(){
		try{
			_p.msg_chat_obj.slimScroll({railVisible: true, height:'300px', size:'5px', position:_self.properties.position, endScrollY: function(){_p.loadOldMessage()}});
		}catch(e){}
	}

	_p.loadOldMessage = function(){
		if (_self.properties.load_old) return;
		_self.properties.load_old = true;
		var min_id = _p.getMinId();
		if (min_id<2) return;
		
		$.ajax({
			type: 'POST',
			url: _self.properties.site_url + _self.properties.get_messages_url,
			data: {min_id: min_id},
			success: function(resp, textStatus, jqXHR){
				_p.setShoutboxMessages(resp);

				if(_self.properties.new_msgs.messages){
					delete(_self.properties.new_msgs.messages);
				}
				_p.renderMessages(_self.shoutbox_messages.msg, 'replace_old');
				_self.shoutbox_messages.loaded = 1;
				_self.properties.load_old = false;
				_self.initScroll();
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(typeof(console)!=='undefined'){
					console.error(errorThrown);
				}
			},
			dataType: 'json',
			backend: 1
		});

		return this;
	}

	this.uninit = function(){
		if(_self.properties.is_dom_init){
			_p.msg_btns_obj.off('click', 'input[name="sendbtn"]');
			_p.msg_textarea_obj.off('keydown');
			_p.shoutbox_obj.hide();
		}
		_self.properties.new_msgs = {messages: {}, count_new: 0};
		_self.init_vars();
		return this;
	}

	this.die = function(){
		MultiRequest.disableAction('shoutbox_new_msgs');
		_self.uninit();
		$('#shoutbox_block').remove();
		return this;
	}

	this.sendMessage = function(){
		if(_p.xhr_send_message && _p.xhr_send_message.readyState != 4){
			return this;
		}

		var text = _p.trimStr(_p.msg_textarea_obj.val());
		if(!text){
			return this;
		}

		_p.xhr_send_message = $.ajax({
			type: 'POST',
			url: _self.properties.site_url + _self.properties.post_message_url,
			data: { text: text, max_id: _p.getMaxId(), id_user: _self.properties.id_user},
			success: function(resp, textStatus, jqXHR){
				if(!resp.errors.length){
					_p.msg_textarea_obj.val('');
					$('#'+_self.properties.id+'_msg_count').text(_self.properties.msg_max_length);
					if(resp.messages.msg.length){
						_p.setShoutboxMessages(resp.messages);	
						_p.renderMessages(resp.messages.msg, 'replace_my');
					}
				}else{
					error_object.show_error_block(resp.errors, 'error');
				}
				if(resp.notices && resp.notices.length){
					error_object.show_error_block(resp.notices, 'info');
				}
			},
			error: function(jqXHR, textStatus, errorThrown){

			},
			dataType: 'json',
			async: true,
			backend: 0
		});
	}


	this.updateNewMessages = function(data){
		_self.properties.new_msgs = {count_new: data.count_new, messages: {}};
		
		for(var i = 0; i < data.messages.length; i++){
			_p.getMessages();
		}
		_self.setCounterNewMessages();
		return this;
	}


	this.setCounterNewMessages = function(){
		if(_p.counter_to){
			clearTimeout(_p.counter_to);
		}
		
		if (_p.msg_chat_obj.scrollTop()<55){
			_p.counter_new_messages_obj.text('');
			_self.properties.count_newm = 0;
			_p.btn_in_top_obj.hide();
			return;
		}
		_p.counter_to = setTimeout(function(){_self.setCounterNewMessages();}, 500);
		return this;
	}


	_p.getMessages = function(){

		$.ajax({
			type: 'POST',
			url: _self.properties.site_url + _self.properties.get_messages_url,
			data: {max_id: _p.getMaxId()},
			success: function(resp, textStatus, jqXHR){
				_p.setShoutboxMessages(resp);

				if(_self.properties.new_msgs.messages){
					delete(_self.properties.new_msgs.messages);
				}
				_p.renderMessages(_self.shoutbox_messages.msg, 'replace');
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(typeof(console)!=='undefined'){
					console.error(errorThrown);
				}
			},
			dataType: 'json',
			backend: 1
		});

		return this;
	}


	_p.setShoutboxMessages = function(data){
		if(_self.shoutbox_messages){
			if(_self.shoutbox_messages.max_id < data.max_id){
				_self.shoutbox_messages.max_id = data.max_id;
			}
			if(_self.shoutbox_messages.min_id > data.min_id){
				_self.shoutbox_messages.min_id = data.min_id;
			}
		}else{
			_self.shoutbox_messages = {
				min_id: data.min_id,
				max_id: data.max_id,
				msg: []
			}
		}
		for(var i = 0; i < data.msg.length; i++){
			var msg_id = parseInt(data.msg[i].id);
			_self.shoutbox_messages.msg[msg_id] = data.msg[i];
		}
	}


	_p.renderMessages = function( messages, type){
		var html = '', replace_from_msg_obj = null, replace_to_msg_obj = null, user_name;
		if (type == 'replace_old') {
			messages.reverse(); 
		}
		for(var i in messages) if(messages.hasOwnProperty(i)){
			if(!$('#shoutbox_msg_'+messages[i].id).size()){
				var user_name = '';
				if (messages[i].user_info.nickname) {
					user_name = messages[i].user_info.nickname;
				} else {
					user_name = messages[i].user_info.output_name;
				}
				var elem = $('<div id="shoutbox_msg_'+messages[i].id+'" data-msg-id="'+messages[i].id+'" class="shoutbox_msg_cont"><div class="fleft image small"><a href="'+messages[i].user_info.link+'"><img src="'+messages[i].user_info.media.user_logo.thumbs.small+'" style="height: 40px" /></a></div> <div class="shoutbox_msg"><div class="shoutbox_msg_header"><span class="shoutbox_user_name a">'+user_name+'</span><span class="fright">'+messages[i].date_str+'</span></div><div class="shoutbox_msg_body">'+messages[i].message+'</div></div></div>');
				if (type == 'replace_old') {
					$(elem).appendTo(_p.msg_chat_obj);
				} else {
					$(elem).prependTo(_p.msg_chat_obj);				
				}
				if ((type != 'replace_old') && (type != 'init') && (type != 'replace_my')) {
					_p.msg_chat_obj.scrollTop(_p.msg_chat_obj.scrollTop()+$(elem).height()+5);
					_self.properties.count_newm = _self.properties.count_newm + 1;
					if (_self.properties.count_newm!=0) {
						_p.counter_new_messages_obj.text('('+_self.properties.count_newm+')');
					}
					_self.initScroll();
				}
				
				//_p.setMessageElement(elem);
			}
		}
		
		if ((type != 'replace_old') && (type != 'init') && (type != 'replace_my')) {
			if (_p.msg_chat_obj.scrollTop()>55){
				_p.btn_in_top_obj.show();
				setTimeout(function(){_p.btn_in_top_obj.hide();}, 6000);
			} else {
				_p.msg_chat_obj.scrollTop(0);
				_p.counter_new_messages_obj.text('');
				_self.properties.count_newm = 0;
			}
		}else if (type == 'replace_my') {
			_p.msg_chat_obj.scrollTop(0);
			_p.counter_new_messages_obj.text('');
			_self.properties.count_newm = 0;
			_self.initScroll();
		}
		return this;
	}

	/*_p.setMessageElement = function(elem){
		if($(elem).find('.shoutbox_msg').height() > $(elem).height()){
			$(elem).append('<div class="shoutbox_dots">...</div>');
			$(elem).on('mouseenter', '.shoutbox_msg_body', function(){
				var top = $(this).offset().top-1;
				var left = $(this).offset().left-1;
				var width = $(this).width()+2;
				$('#'+_self.properties.id+'_full_text').html($(this).html()).css({'top': top+'px', 'left': left+'px', width: width+'px'}).fadeIn(0);
			});
		}
	}*/

	_p.initDomObjects = function(){
		_p.shoutbox_obj = $('#'+_self.properties.id);
		_p.msg_textarea_obj = _p.shoutbox_obj.find('.shoutbox-bottom textarea');
		_p.msg_chat_obj = _p.shoutbox_obj.find('.shoutbox-content .shoutbox-scroller');
		_p.msg_btns_obj = $('#'+_self.properties.shoutbox_msg_btns_id);
		_p.counter_new_messages_obj = $('#shoutbox_counter_nm');
		_p.btn_in_top_obj = _p.shoutbox_obj.find('.shoutbox-in-top');

		_p.msg_btns_obj.off('click', 'input[name="sendbtn"]').on('click', 'input[name="sendbtn"]', function(){
			_self.sendMessage();
			_p.msg_textarea_obj.focus();
		});
		_p.btn_in_top_obj.off('click').on('click', function(){
			_p.msg_chat_obj.scrollTop(0);
			_p.counter_new_messages_obj.text('');
			_self.properties.count_newm = 0;
			_p.btn_in_top_obj.hide();
			$('.slimScrollBar').css('top',0);
		});
		_p.msg_chat_obj.off('click', '.shoutbox_user_name').on('click', '.shoutbox_user_name', function(){
			_p.msg_textarea_obj.val(_p.msg_textarea_obj.val() + $(this).text() + ', ');
			_p.msg_textarea_obj.trigger('keyup');
		});
		_p.msg_textarea_obj.off('keydown').on('keydown', function(e){
			if(e.ctrlKey && e.keyCode === 13){
				_self.sendMessage();
			}
		}).off('keyup blur').on('keyup blur', function(){
			var msg_length = $(this).val().length;
			if (msg_length > _self.properties.msg_max_length) {
				$(this).val($(this).val().substring(0, _self.properties.msg_max_length)).scrollTop(1000);
			}
			msg_length = $(this).val().length;
			$('#'+_self.properties.id+'_msg_count').text(_self.properties.msg_max_length - msg_length);
		});

		_p.shoutbox_obj.show();

		_self.properties.is_dom_init = true;

		return this;
	}



	_p.initMultiRequest = function(){
		var actions = [
			{
				gid: 'shoutbox_new_msgs',
				params: {module: 'shoutbox', model: 'Shoutbox_model', method: 'check_new_messages', 'max_id': _p.getMaxId},
				paramsFunc: function(){return {}},
				callback: function(resp){
					if(resp){
						_self.updateNewMessages(resp);
					}
				},
				period: 3,
				status: 1
			}
		];

		MultiRequest.initActions(actions);
		return this;
	}
	
	_p.getMaxId = function(){
		var  max_id = 0;
		if(_self.shoutbox_messages){
			max_id = _self.shoutbox_messages.max_id;
		}
		return max_id;
	}
	_p.getMinId = function(){
		var  min_id = 0;
		if(_self.shoutbox_messages){
			min_id = _self.shoutbox_messages.min_id;
		}
		return min_id;
	}

	_p.trimStr = function(s) {
		s = s.replace( /^\s+/g, '');
		return s.replace( /\s+$/g, '');
	}


	_self.init(params);

	return this;
};
