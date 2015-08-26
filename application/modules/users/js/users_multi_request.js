if(typeof MultiRequest !== 'undefined'){
	MultiRequest.initAction({
		gid: 'visitors',
		params: {module: 'users', model: 'Users_views_model', method: 'get_viewers_count'},
		paramsFunc: function(){return {};},
		callback: function(resp){
			$('.visitors_count').html(parseInt(resp.count));
		},
		period: 12,
		status: 0
	});

	if(id_user){
		MultiRequest.enableAction('visitors');
	}
	$(document).on('users:login', function(){
		MultiRequest.enableAction('visitors');
	}).on('users:logout, session:guest', function(){
		MultiRequest.disableAction('visitors');
	});
}