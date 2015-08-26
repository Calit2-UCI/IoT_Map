if(typeof MultiRequest !== 'undefined'){
	MultiRequest.initAction({
		gid: 'kisses',
		params: {module: 'kisses', model: 'Kisses_model', method: 'kisses_count'},
		paramsFunc: function(){return {};},
		callback: function(resp){
			$('.kisses_count').html(parseInt(resp.count));
		},
		period: 6,
		status: 0
	});

	if(id_user){
		MultiRequest.enableAction('kisses');
	}
	$(document).on('users:login', function(){
		MultiRequest.enableAction('kisses');
	}).on('users:logout, session:guest', function(){
		MultiRequest.disableAction('kisses');
	});
}
