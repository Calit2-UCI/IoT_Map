function Likes(optionArr){

	this.properties = {
		siteUrl: '/',
		likeUrl: 'likes/ajax_like',
		usersUrl: 'likes/ajax_get_users',
		likeBlock: '.like_block',
		likeBtn: '.like_btn',
		likeMoreBtn: '.like_more_btn',
		numberBlock: '.like_num',
		usersBlock: '.like_users',
		likeClass: 'fa-heart-empty',
		unlikeClass: 'fa-heart',
		likeTitle: '',
		unlikeTitle: '',
		actionAttr: 'action',
		actionLike: 'like',
		actionUnlike: 'unlike',
		usersPositionMy: 'right bottom',
		usersPositionAt: 'right top',
		usersPositionWithin: '.content',
		usersPositionCollision: 'flip none',
		commonAncestor: 'body',
		showUsersTimeout: 300,
		hideUsersTimeout: 300,
		canLike: true
	};

	this.current_obj = {};
	var _self = this;
	var _enterTimer;
	var _leaveTimer;
	var _loaded = [];
	var _sending = false;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.init_controls();
	};

	this.uninit = function(){
		$(_self.properties.commonAncestor)
			.off('mouseenter', _self.properties.likeBlock)
			.off('mouseleave', _self.properties.likeBlock)
			.off('mouseenter', _self.properties.usersBlock)
			.off('mouseleave', _self.properties.usersBlock)
			.off('click', _self.properties.likeBtn+', '+_self.properties.numberBlock);
		return this;
	};

	this.init_controls = function(){
		$(_self.properties.commonAncestor).off('mouseenter', _self.properties.likeBlock).on('mouseenter', _self.properties.likeBlock, function(){
			var obj = $(this);
			clearTimeout(_leaveTimer);
			_enterTimer = setTimeout(function() {
				_self.show_users(obj);
			}, _self.properties.showUsersTimeout);
		}).off('mouseleave', _self.properties.likeBlock).on('mouseleave', _self.properties.likeBlock, function(){
			clearTimeout(_enterTimer);
			_leaveTimer = setTimeout(function() {
				$(_self.properties.usersBlock).fadeOut();
			}, _self.properties.hideUsersTimeout);
		}).off('mouseenter', _self.properties.usersBlock).on('mouseenter', _self.properties.usersBlock, function(){
			clearTimeout(_leaveTimer);
		}).off('mouseleave', _self.properties.usersBlock).on('mouseleave', _self.properties.usersBlock, function(){
			_leaveTimer = setTimeout(function() {
				$(_self.properties.usersBlock).fadeOut();
			}, _self.properties.hideUsersTimeout);
		}).off('click', _self.properties.likeBtn+', '+_self.properties.numberBlock).on('click', _self.properties.likeBtn+', '+_self.properties.numberBlock, function(){
			_self.like($(this).closest(_self.properties.likeBlock));
		}).off('click', _self.properties.likeMoreBtn).on('click', _self.properties.likeMoreBtn, function(){
			_self.show_all_users(_self.current_obj);
		});
	};

	this.like = function(obj) {
		var id = obj.data('gid');
		if(!id_user){
			error_object.errors_access(); 
			return false;
		}
		if(_sending || !id) {
			return false;
		}
		var action = obj.data(_self.properties.actionAttr);
		_sending = true;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.likeUrl,
			type: 'POST',
			dataType : 'json',
			cache: false,
			data: {
				like_id: id,
				like_action: action
			},
			success: function(data){
				_sending = false;
				if(1 === data.status){
					_self.save_like(id, action, data.count);
					if ('0' === data.count) {
						$(_self.properties.usersBlock).fadeOut();
					} else {
						_self.show_users(obj, true);
					}
					return true;
				} else{
					error_object.show_error_block(data.error, 'error');
					console.log(data.error);
					return false;
				}
			},
			error: function(){
				_sending = false;
				console.log('error while liking');
				return false;
			}
		});
	};
	
	this.show_all_users = function(obj){
		_self.show_users(obj, true, true);
		return this;
	}

	this.show_users = function(obj, force_update, get_all) {
		if('0' === obj.find(_self.properties.numberBlock).html()) {
			return false;
		}
		force_update = force_update | false;
		get_all = get_all | false;
		var gid = obj.data('gid');
		if(!gid) {
			return false;
		}
		_self.current_obj = obj;
		if(!get_all){
			$('.like_users').stop(true).hide();
		}
		var obj_class = 'like_' + gid + '_users';
		var users_block;
		users_block = $('.' + obj_class);
		if(-1 !== $.inArray(gid, _loaded) && !force_update) {
			if(!users_block.is(':visible') && !users_block.is(':empty')) {
				users_block
					.fadeIn()
					.position({
						my: _self.properties.usersPositionMy,
						at: _self.properties.usersPositionAt,
						of: obj,
						within: _self.properties.usersPositionWithin,
						collision: _self.properties.usersPositionCollision
					});
			}
			return true;
		}
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.usersUrl,
			type: 'POST',
			dataType : 'html',
			cache: false,
			data: {
				like_id: gid,
				get_all: get_all ? 1 : 0
			},
			success: function(data){
				data = $.trim(data);
				if(users_block.length) {
					if(0 === data.length) {
						users_block.fadeOut(function(){
							users_block.html('');
						});
						return true;
					} else {
						users_block.html($(data).html());
					}
				} else if(data.length) {
					users_block = $(data).addClass(obj_class).data('id-like', gid);
					$(_self.properties.commonAncestor).append(users_block);
				}
				if(!force_update) {
					_loaded.push(gid);
				}
				users_block
					.fadeIn()
					.position({
						my: _self.properties.usersPositionMy,
						at: _self.properties.usersPositionAt,
						of: obj,
						within: _self.properties.usersPositionWithin,
						collision: _self.properties.usersPositionCollision
					});
				return true;
			},
			error: function(){
				console.log('error while getting users list');
			}
		});
	};

	this.save_like = function(likeId, action, count) {
		var likeObj = $('[data-gid="' + likeId + '"]');
		var btnObj = likeObj.find(_self.properties.likeBtn);
		if(_self.properties.actionLike === action) {
			likeObj.data(_self.properties.actionAttr, _self.properties.actionUnlike);
			btnObj.removeClass(_self.properties.likeClass)
				.addClass(_self.properties.unlikeClass)
				.attr('title', _self.properties.unlikeTitle);
		} else if(_self.properties.actionUnlike === action) {
			likeObj.data(_self.properties.actionAttr, _self.properties.actionLike);
			btnObj.removeClass(_self.properties.unlikeClass)
				.addClass(_self.properties.likeClass)
				.attr('title', _self.properties.likeTitle);
		} else {
			console.log('error while saving like: wrong action');
		}
		likeObj.find(_self.properties.numberBlock).html(count);
	};

	_self.Init(optionArr);
}