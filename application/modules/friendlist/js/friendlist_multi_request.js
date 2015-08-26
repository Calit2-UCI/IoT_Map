if (typeof MultiRequest !== 'undefined') {
	MultiRequest.initAction({
		gid: 'friendlist_request',
		params: {module: 'friendlist', model: 'Friendlist_model', method: 'get_request_notifications'},
		paramsFunc: function() {
			return {};
		},
		callback: function(resp) {
			if (resp.friends && resp.friends.length) {
				for (var i in resp.friends) {
					var req = resp.friends[i];
					if (req.notified) {
						continue;
					}
					var options = {
						title: req.title,
						text: req.text,
						image: req.user_icon,
						image_link: req.user_link,
						sticky: true,
						time: 15000
					};
					notifications.show(options);
				}
				$('.friend_requests_count').html(resp.friends.length);
			}
		},
		period: 12,
		status: 0
	});
	MultiRequest.initAction({
		gid: 'friendlist_accept',
		params: {module: 'friendlist', model: 'Friendlist_model', method: 'get_accept_notifications'},
		paramsFunc: function() {
			return {};
		},
		callback: function(resp) {
			if (resp.friends && resp.friends.length) {
				for (var i in resp.friends) {
					var req = resp.friends[i];
					if (req.notified) {
						continue;
					}
					var gritter_id = $.gritter.add({
						title: req.title,
						text: req.text,
						image: req.user_icon,
						image_link: req.user_link,
						sticky: false,
						time: 15000
					});
					$('#gritter-item-' + gritter_id).on('click', 'a', function() {
						$.gritter.remove(gritter_id);
					});
				}
			}
		},
		period: 12,
		status: 0
	});

	if (id_user) {
		MultiRequest.enableAction('friendlist_request').enableAction('friendlist_accept');
	}
	$(document).on('users:login', function() {
		MultiRequest.enableAction('friendlist_request').enableAction('friendlist_accept');
	}).on('users:logout, session:guest', function() {
		MultiRequest.disableAction('friendlist_request').disableAction('friendlist_accept');
	});
}
