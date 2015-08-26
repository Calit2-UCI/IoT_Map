"use strict";
function winks(optionArr) {

	var _self = this;

	this.properties = {
		btnSearch: '#winks-search-button',
		btnWink: '.btn-wink',
		btnWinkBack: '.btn-wink-back',
		btnWinkIgnore: '.btn-wink-ignore',
		dataNew: 'is-new',
		dataPending: 'is-pending',
		errIsPending: 'Already winked',
		errIsOnList: 'Already in the list',
		wink: '.wink',
		list: '#winks-list',
		noWinks: '#no-winks',
		replyUrl: 'winks/ajax_reply',
		searchUrl: 'users/search',
		siteUrl: '/',
		succIgnored: 'Ignored',
		succResponded: 'Responded',
		succWinked: 'Winked',
		titleWink: 'Wink',
		titleWinkBack: 'Wink back',
		winkUrl: 'winks/ajax_wink',
        wink_button_rand: ''
	};

	var errorObj = new Errors();

	var request = function(url, data) {
		return $.ajax({
			data: data,
			dataType: "json",
			type: "POST",
			url: _self.properties.siteUrl + url
		});
	};

	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);
		_self.uninit();
		_self.bindEvents();
	};

	this.uninit = function() {
		$(_self.properties.btnWink).off('click');
		$(_self.properties.btnWinkBack).off('click');
		$(_self.properties.btnWinkIgnore).off('click');
	};

	this.getPageIds = function() {
		var userId = 0;
		var userIds = [];
		$(_self.properties.wink, _self.properties.list).each(function(){
		  userId = parseInt($(this).data('user-id'));
		  if(userId > 0) {
			 userIds.push(userId);
		  }
		});
		return userIds;
	};

	this.addUserToList = function(data) {
		if(in_array(data.id, _self.getPageIds())) {
			var msg = _self.properties.errIsOnList.replace(/\[username\]/, data.output_name);
			errorObj.show_error_block(msg, 'error');
			return false;
		}
		var newItem = $('#wink-_user-id_').outerHTML();
		newItem = newItem.replace(/_user\-id_/g, data.id)
				.replace(/_user\-name_/g, data.output_name)
				.replace(/_date_/g, '')
				.replace(/_user\-img_/g, data.media.user_logo.thumbs.small);
		$(_self.properties.list).prepend($(newItem).removeClass('hide-always'));
		$(_self.properties.noWinks).hide();
		_self.bindEvents();
	};

	this.hideFromList = function(id) {
		$('#wink-' + id).remove();
		if (1 === $('.wink').length) {
			$(_self.properties.noWinks).show();
		}
	};

	this.bindEvents = function() {
		_self.uninit();
		$(_self.properties.btnWink).on('click', function() {
			var btnObj = $(this);
			if (!btnObj.data(_self.properties.dataPending)) {
				var destUser = btnObj.data('user-id');
				request(_self.properties.winkUrl, {user_id: destUser, type: 'new'})
					.done(function(data) {
						if(data['errors']) {
							errorObj.show_error_block(data['errors'], 'error');
						} else {
                            $(_self.properties.btnWink).each(function(){
                                if (destUser == $(this).data('user-id')) {
                                    $(this).find('i').addClass('g');
                                    $(this).data(_self.properties.dataNew, false);
                                    $(this).data(_self.properties.dataPending, true);
                                }
                            });
							_self.hideFromList(destUser);
							errorObj.show_error_block(_self.properties.succWinked, 'success');
							_self.bindEvents();
						}
					}).fail(function(data) {
						console.error(data);
					});
			} else {
				errorObj.show_error_block(_self.properties.errIsPending, 'error');
			}
		});

		$(_self.properties.btnWinkBack).on('click', function() {
			var btnObj = $(this);
			var destUser = btnObj.data('user-id');
			if (!btnObj.data(_self.properties.dataPending)) {
				request(_self.properties.replyUrl, {user_id: destUser, type: 'replied'})
					.done(function(data) {
                        $(_self.properties.btnWink).each(function(){
                            if (destUser == $(this).data('user-id')) {
                                $(this).attr('title', _self.properties.titleWink);
                                $(this).find('i').addClass('g');
                                $(this).data(_self.properties.dataPending, true);
                                $(this).data(_self.properties.dataNew, false);
                            }
                        });
						_self.hideFromList(destUser);
						errorObj.show_error_block(_self.properties.succResponded, 'success');
						_self.bindEvents();
					}).fail(function(data) {
						console.error('error');
					});
			} else {
				errorObj.show_error_block(_self.properties.errIsPending, 'error');
			}
		});

		$(_self.properties.btnWinkIgnore).on('click', function() {
			var destUser = $(this).data('user-id');
			request(_self.properties.replyUrl, {user_id: destUser, type: 'ignored'})
				.done(function(data) {
					errorObj.show_error_block(_self.properties.succIgnored, 'success');
					_self.hideFromList(destUser);
				}).fail(function(data) {
					console.error('error');
				});
		});

		$(_self.properties.btnSearch).on('click', function() {
			redirect(_self.properties.siteUrl + _self.properties.searchUrl);
		});
	};

	_self.Init(optionArr);
}