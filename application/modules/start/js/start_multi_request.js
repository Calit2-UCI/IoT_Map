var user_session_id = null;
if(typeof MultiRequest !== 'undefined'){
	MultiRequest.initAction({
		gid: 'ping_request',
		params: {module: 'start', model: 'Start_model', method: 'ping_request'},
		paramsFunc: function(){return {};},
		callback: function(resp){
			if(resp){
				if(typeof resp.user_session_id !== 'undefined'){
					if(user_session_id !== null && user_session_id != resp.user_session_id && resp.user_session_id == 0){
						//locationHref(site_url+'start/index');
						$('html, body').animate({
							scrollTop: $("#ajax_login_link_menu").size() ? $("#ajax_login_link_menu").offset().top : 0
						}, 1000);
						$("#ajax_login_link").click();
					}
					user_session_id = resp.user_session_id;
				}
			}
		},
		period: 3,
		status: 1
	});
}
