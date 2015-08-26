function adminMailList(optionArr){
	this.properties = {
		siteUrl: '',
		imgsUrl: ''
	}

	var _self = this;

	this.errors = {}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
	}

	this.bind_users_events = function() {
		$('#grouping_all', '#mail_list').bind('click', function(){
			if($(this).is(':checked')){
				$('input.grouping').prop('checked', true);
			}else{
				$('input.grouping').prop('checked', false);
			}
		});
		$('.subscribe', '#mail_list').bind('click', function(){
			_self.subscribe_users($(this).attr('id'));
		});
		$('.subscribe_one', '#mail_list').on('click', function(){
			var id_user = +$(this).parent().parent().attr('id').replace(/\D+/g, '');
			_self.subscribe_one(id_user, $(this));
		});
		$('.unsubscribe_one', '#mail_list').on('click', function(){
			var id_user = +$(this).parent().parent().attr('id').replace(/\D+/g, '');
			_self.unsubscribe_one(id_user, $(this));
		});
	}
	
	this.bind_filters_events = function() {
		$('.link_delete', '#tbl_filters').bind('click', function() {
			var filter_id = +$(this).attr('id').replace(/\D+/g, '');
			_self.delete_filter(filter_id, $(this).parent().parent());
		})
		$('.link_search', '#tbl_filters').bind('click', function() {
			var filter_id = +$(this).attr('id').replace(/\D+/g, '');
			_self.apply_filter(filter_id);
		})
	}
	
	this.subscribe_one = function(id_user, btn) {
		_self.subscribe_users('subscribe_one', id_user);
		btn.removeClass('subscribe_one').addClass('unsubscribe_one');
		btn.find('img').attr('src', _self.properties.imgsUrl + 'icon-mark.png');
	}

	this.unsubscribe_one = function(id_user, btn) {
		_self.subscribe_users('unsubscribe_one', id_user);
		btn.removeClass('unsubscribe_one').addClass('subscribe_one');
		btn.find('img').attr('src', _self.properties.imgsUrl + 'icon-unmark.png');
	}

	this.subscribe_users = function(action, id_user) {
		var data = {};
		var deltaCount = 0;
		data['id_subscription']	= $('#id_subscription').val();
		data['action'] = action;
		if ('subscribe_one' == action || 'unsubscribe_one' == action) {
			data['id_user'] = id_user;
		} else if ('subscribe_selected' == action || 'unsubscribe_selected' == action) {
			data['id_users'] = [];
			$('.grouping:checked').each(function() {
				id_user = $(this).attr('value');
				data['id_users'].push(id_user);
				if ('subscribe_selected' == action) {
					$('#user' + id_user).find('.subscribe_one img')
						.attr('src', _self.properties.imgsUrl + 'icon-mark.png')
						.parent().removeClass('subscribe_one').addClass('unsubscribe_one');
				} else {
					$('#user' + id_user).find('.unsubscribe_one img')
						.attr('src', _self.properties.imgsUrl + 'icon-unmark.png')
						.parent().removeClass('unsubscribe_one').addClass('subscribe_one');
				}
			});
		}
		$.ajax({
			url:	_self.properties.siteUrl + 'admin/mail_list/ajax_subscribe', 
			type:	'POST',
			cache:	false,
			data:	data
		});
		switch(action) {
			case 'subscribe_one' :
				console.info('User subscribed');
				deltaCount = 1;
				break;
			case 'unsubscribe_one' :
				console.info('User unsubscribed');
				deltaCount = -1;
				break;
			case 'subscribe_selected' :
				console.info('Users subscribed');
				deltaCount = (data['id_users']).length;
				break;
			case 'unsubscribe_selected' :
				console.info('Users unsubscribed');
				deltaCount = -(data['id_users']).length;
				break;
			case 'subscribe_all' :
				console.info('All users subscribed');
				deltaCount = parseInt($('#count_not_subscribed').html());
				break;
			case 'unsubscribe_all' :
				console.info('All users unsubscribed');
				deltaCount = -parseInt($('#count_subscribed').html());
				break;
		}
		_self.updateCount(deltaCount);
	}
	
	this.updateCount = function(count) {
		$('#count_subscribed').html(parseInt($('#count_subscribed').html()) + count);
		$('#count_not_subscribed').html(parseInt($('#count_not_subscribed').html()) - count);
	}
	
	this.save_filter = function(filter_data) {
		$.ajax({
			data:	filter_data,
			type:	'POST',
			cache:	false,
			url:	_self.properties.siteUrl + 'admin/mail_list/ajax_save_filter'
		});
		console.info('Filter (appears to be) saved');
	}

	this.delete_filter = function(id_filter, row) {
		$.ajax({
			url:	_self.properties.siteUrl + 'admin/mail_list/ajax_delete_filter', 
			type:	'POST',
			cache:	false,
			data:	{id_filter: id_filter},
			success: function(response){
				if(true == response) {
					row.remove();
					console.info('Filter removed');
					return true;
				} else {
					console.error('Error while removing filter')
					return false;
				}
			}
		});
	}

	this.apply_filter = function(id_filter) {
		$('#id_filter').val(id_filter);
		$('#frm_apply_filter').submit();
	}

	_self.Init(optionArr);
}