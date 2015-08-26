var MultiRequest = (function(){
	
	var public = {};
	
	public.properties={
		url: '/start/ajax_backend/',
		timeout: 1000,
		active: true,
		actions: {},
		error_count: 0,
		error_limit: 10,
		hotstart: 1
	}
	
	
	public.init = function(options){
		public.counter = public.properties.hotstart ? 0 : 1;
		public.properties.active = true;
		public.properties.error_count = 0;
		if(public.properties.hotstart){
			execute();
		}else{
			runTimeout();
		}
		return public;
	}
	
	
	public.unInit = function(){
		if(typeof(public.to) !== 'undefined'){
			clearTimeout(public.to);
		}
		public.properties.active = false;
		return public;
	}
	
	
	public.initAction = function(action){
		if(!public.properties.actions[action.gid]){
			public.properties.actions[action.gid] = action;
			public.properties.actions[action.gid].counter = 0;
		}
		return public;
	}

	public.initActions = function(actions){
		if(typeof(actions)==='object'){
			for(var i in actions){
				if(actions.hasOwnProperty(i) && typeof(actions[i])==='object'){
					public.initAction(actions[i])
				}
			}
		}
		return public;
	}
	
	
	public.disableAction = function(action_gid){
		if(public.properties.actions[action_gid]){
			public.properties.actions[action_gid].status = 0;
		}
		return public;
	}

	
	public.enableAction = function(action_gid){
		if(public.properties.actions[action_gid]){
			public.properties.actions[action_gid].status = 1;
		}
		return public;
	}
	
	
	public.deleteAction = function(action_gid){
		if(public.properties.actions[action_gid]){
			delete public.properties.actions[action_gid];
		}
		return public;
	}
	
	
	public.setProperties = function(property, value){
		value = value || null;
		if(typeof(property)==='object'){
			$.extend(public.properties, property);
		}else if(typeof(property)==='string'){
			public.properties[property] = value;
		}
		return public;
	}
	
	
	var execute = function(){
		var post_data = {data: {}};
		post_data.not_update_online_status = 1;
		for(var gid in public.properties.actions){
			var action = public.properties.actions[gid];
			if(action.update_online_status == 1){
				post_data.not_update_online_status = 0;
			}
			if( ((public.properties.hotstart && public.counter == 0) || (public.counter % action.period == 0)) && action.status ){
				var params = {'gid': action.gid, 'counter': ++action.counter};
				$.extend(params, action.params);
				if(action.paramsFunc){
					$.extend(params, action.paramsFunc(action));
				}
				post_data.data[action.gid] = params;
			}
		}
		
		public.counter++;
		
		if(!$.isEmptyObject(post_data.data)){
			$.ajax({
				type: 'POST',
				url: public.properties.url,
				data: post_data,
				beforeSend: function(xhr, settings){
					/*$(document).one('pjax:start', function(){xhr.abort();});*/
				},
				success: function(resp){
					if(resp){
						if(typeof resp.user_session_id !== 'undefined' && resp.user_session_id == 0){
							$(document).trigger('session:guest');
						}
						for(var gid in resp) if(resp.hasOwnProperty(gid)){
							if(public.properties.actions[gid]){
								public.properties.actions[gid].callback(resp[gid]);
							}
						}
					}
					runTimeout();
				},
				error: function(jqXHR, textStatus, errorThrown){
					public.properties.error_count++;
					if(typeof(console)!=='undefined'){
						console.log(errorThrown);
						if(public.properties.error_count >= public.properties.error_limit){
							console.log('Too many errors. MultiRequest stopped.');
						}
					}
					if(public.properties.error_count >= public.properties.error_limit){
						public.unInit();
					}else{
						runTimeout();
					}
				},
				dataType: 'json',
				backend: 1
			});
		}else{
			runTimeout();
		}
	}
	
	
	var runTimeout = function(){
		if(public.properties.active){
			if(typeof(public.to) !== 'undefined'){
				clearTimeout(public.to);
			}
			public.to = setTimeout(execute, public.properties.timeout);
		}
		return public;
	}
	

	return public;
})();