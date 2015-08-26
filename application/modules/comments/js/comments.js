/**
* Comments js class
*
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
*/
function Comments(optionArr){
	if(!Comments.instance){
		Comments.instance = this;
	}else if(Comments.instance.properties.singleton){
		return Comments.instance;
	}
	
	this.properties = {
		siteUrl: '',
		addUrl: '',
		deleteUrl: '',
		loadUrl: '',
		likeUrl: '',
		singleton: 1,
		min_visible_comments: 2,
		lng:{
			error: 'Error!',
			error_comment_text: 'Empty text!',
			error_user_name: 'Enter your name!',
			added: 'Comment successfully added',
			added_moderation: 'Comment successfully added and awaiting administrator approval',
			deleted: 'Comment successfully deleted'
		}
	}

	var _self = this;
	var xhr_like,
		xhr_send;

	this.Init = function(options){
		_self.properties = $.extend(true, _self.properties, options);
		if(!_self.properties.addUrl){
			_self.properties.addUrl = _self.properties.siteUrl+'comments/ajax_add_comment';
		}
		if(!_self.properties.deleteUrl){
			_self.properties.deleteUrl = _self.properties.siteUrl+'comments/ajax_delete_comment';
		}
		if(!_self.properties.loadUrl){
			_self.properties.loadUrl = _self.properties.siteUrl+'comments/ajax_load_comments';
		}
		if(!_self.properties.likeUrl){
			_self.properties.likeUrl = _self.properties.siteUrl+'comments/ajax_like_comment';
		}

		$(document).on('keypress keyup change blur', '.comments .form_wrapper textarea', function(e){
			_self.countChars(this);
		});
	}
	
	this.uninit = function(){
		$(document).off('keypress keyup change blur', '.comments .form_wrapper textarea');
		Comments.instance = undefined;
	}
	
	this.addComment = function(gid, id_obj){
		if(xhr_send && xhr_send.state() == 'pending'){
			return;
		}
		id_obj = parseInt(id_obj);
		jq_obj = _self.getObjectsByGidObj(gid, id_obj);
		
		var range_id = _self.getMinMaxId(gid, id_obj);
		
		var text = _trimStr(jq_obj.textarea.val());
		var user_name = _trimStr(jq_obj.user_name.val());
		var email = _trimStr(jq_obj.email.val());
		
		var err = [];
		if(!text){
			err.push(_self.properties.lng.error_comment_text);
		}
		if(jq_obj.user_name.size() && !user_name){
			err.push(_self.properties.lng.error_user_name);
		}
		if(!id_obj){
			err.push(_self.properties.lng.error);
		}
		if(err.length){
			_self.showNotice(err);
			return _self;
		}

		xhr_send = $.post(
			_self.properties.addUrl,
			{
				gid: gid,
				id_obj: id_obj,
				text: text,
				user_name: user_name,
				email: email,
				max_id: range_id.max_id,
				min_id: range_id.min_id
			},
			function(resp){
				if(resp.status && resp.id){
					if(resp.comments_html){
						jq_obj.cont.prepend(resp.comments_html);
					}
					if(typeof resp.comments.count_all !== 'undefined'){
						jq_obj.counter.html(resp.comments.count_all);
					}
					jq_obj.textarea.val('');
					_self.countChars(jq_obj.textarea);
					jq_obj.user_name.val('');
					if(resp.moderation){
						_self.showNotice(_self.properties.lng.added_moderation, 'success');
					}else{
						/*_self.showNotice(_self.properties.lng.added, 'success');*/
					}
				}else if(!resp.status && resp.errors){
					_self.showNotice(resp.errors);
				}
			},
			'json'
		);
		return _self;
	}
	
	this.deleteComment = function(id){
		id = parseInt(id);
		var jq_obj = _self.getObjectsById(id);
		var gid_obj = _self.getGidObjById(id);

		$.post(
			_self.properties.deleteUrl,
			{id: id},
			function(resp){
				if(resp.status && resp.is_deleted){
					jq_obj.block.remove();
					if(typeof resp.count_all !== 'undefined'){
						jq_obj.counter.html(resp.count_all);
					}
					jq_obj = _self.getObjectsByGidObj(gid_obj.gid, gid_obj.id_obj);
					var visible_count = jq_obj.block.size();
					if(visible_count <= _self.properties.min_visible_comments && jq_obj.more_button.size()){
						_self.loadComments(gid_obj.gid, gid_obj.id_obj);
					}
					/*_self.showNotice(_self.properties.lng.deleted, 'success');*/
				}else{
					_self.showNotice(_self.properties.lng.error);
				}
			},
			'json'
		);
		return _self;
	}
	
	this.loadComments = function(gid, id_obj, load_form){
		id_obj = parseInt(id_obj);
		load_form = load_form || false;
		var with_form = load_form ? 1 : 0;
		var range_id = _self.getMinMaxId(gid, id_obj);
		
		$.post(
			_self.properties.loadUrl,
			{gid: gid, id_obj: id_obj, max_id: range_id.max_id, min_id: range_id.min_id, with_form: with_form},
			function(resp){
				if(resp.status && resp.comments_html){
					if(with_form){
						$(load_form).html(resp.comments_html);
						jq_obj = _self.getObjectsByGidObj(gid, id_obj);
						jq_obj.slider.hide().slideDown(150);
					}else{
						jq_obj = _self.getObjectsByGidObj(gid, id_obj);
						jq_obj.cont.append(resp.comments_html);
					}
					if(typeof resp.comments.count_all !== 'undefined'){
						jq_obj.counter.html(resp.comments.count_all);
					}
					range_id = _self.getMinMaxId(gid, id_obj);
					if(range_id.min_id == resp.comments.bd_min_id){
						jq_obj.more_button.remove();
					}
				}else{
					_self.showNotice(_self.properties.lng.error);
				}
			},
			'json'
		);
		
		return _self;
	}
	
	this.like = function(id){
		if(xhr_like && xhr_like.state() == 'pending'){
			return;
		}
		id = parseInt(id);
		jq_obj = _self.getObjectsById(id);
		xhr_like = $.post(
			_self.properties.likeUrl,
			{id: id},
			function(resp){
				if(resp.status && typeof resp.likes !== 'undefined'){
					jq_obj.likes_counter.fadeOut(50, function(){
						$(this).html(resp.likes).fadeIn(50);
					});
				}else if(!resp.status && resp.error){
					_self.showNotice(resp.error);
				}
				jq_obj.likes_counter.parents('a').attr('title', resp.a_title);
			},
			'json'
		);
		
		return _self;
	}
	
	this.showNotice = function(notice, type){
		type = type || 'error';
		var msg = '';
		if(typeof notice === 'object'){
			msg = notice.join('<br/>');
		}else if(typeof notice === 'string'){
			msg = notice;
		}
		if(msg){
			error_object.show_error_block(msg, type);
		}
	}
	
	this.getMinMaxId = function(gid, id_obj){
		id_obj = parseInt(id_obj);
		var jq_obj = _self.getObjectsByGidObj(gid, id_obj);
		var first_comment_obj_id = jq_obj.cont.find('.comment_block:first').attr('id');
		var last_comment_obj_id =jq_obj.cont.find('.comment_block:last').attr('id');
		var first_id = 0;
		var last_id = 0;
		if(typeof first_comment_obj_id !== 'undefined'){
			first_id = parseInt(first_comment_obj_id.replace('comment_id_',''));
		}
		if(typeof last_comment_obj_id !== 'undefined'){
			last_id = parseInt(last_comment_obj_id.replace('comment_id_',''));
		}
		var max_id = first_id > last_id ? first_id : last_id;
		var min_id = first_id < last_id ? first_id : last_id;
		
		return {min_id: min_id, max_id: max_id};
	}

	this.visibleCount = function(gid, id_obj){
		id_obj = parseInt(id_obj);
		var jq_obj = _self.getObjectsByGidObj(gid, id_obj);
		return jq_obj.block.size();
	}
	
	this.countChars = function(obj){
		var msg_length = $(obj).val().length;
		var max_count = parseInt($(obj).attr('maxcount'));
		if(msg_length > max_count) {
			$(obj).val($(obj).val().substring(0, max_count)).scrollTop(1000);
		}
		msg_length = $(obj).val().length;
		$(obj).parents('.edit_block').find('.char_counter').html(max_count - msg_length);
	}
	
	this.getGidObjById = function(id){
		id = parseInt(id);
		var jq_obj = _self.getObjectsById(id);
		var gid = jq_obj.form_cont.attr('gid');
		var id_obj = jq_obj.form_cont.attr('id_obj');
		return {gid: gid, id_obj: id_obj};
	}

	this.getObjectsByGidObj = function(gid, id_obj){
		id_obj = parseInt(id_obj);
		var child_obj = $('#comments_form_cont_'+gid+'_'+id_obj).find('.comment_block');
		var root_obj = $('#comments_form_cont_'+gid+'_'+id_obj);
		return _getObjects(child_obj, root_obj);
	}

	this.getObjectsById = function(id){
		id = parseInt(id);
		var child_obj = $('#comment_id_'+id);
		return _getObjects(child_obj);
	}
	
	var _getObjects = function(child_obj, root_obj){
		root_obj = root_obj || false;
		var obj = {};
		obj.block = child_obj;
		obj.likes_counter = child_obj.find('.likes_counter');
		obj.form_cont = (root_obj || !child_obj.size()) ? root_obj : child_obj.parents('.form_wrapper');
		obj.cont = obj.form_cont.find('.comments_wrapper');
		obj.more_button = obj.form_cont.find('.more_button');
		obj.slider = obj.form_cont.find('.comments_slider');
		obj.counter = obj.form_cont.find('.counter');
		obj.textarea = obj.form_cont.find('textarea');
		obj.user_name = obj.form_cont.find('input[name="user_name"]');
		obj.email = obj.form_cont.find('input[name="email"]');
		return obj;
	}
	
	var _trimStr = function(s) {
		if(typeof s !== 'string'){
			return '';
		}
		s = s.replace( /^\s+/g, '');
		return s.replace( /\s+$/g, '');
	}
	
	_self.Init(optionArr);
	
	return _self;
}