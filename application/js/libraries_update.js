function librariesUpdate(optionArr){
	this.properties = {
		siteUrl: '',
		library_gid: '',
		library_version: '',
		library_update_url: '',
		library_archive_file: '',

		urlGet: 'admin/install/ajax_get_library_update',
		urlUnpack: 'admin/install/ajax_unpack_library_update',
		urlCopy: 'admin/install/ajax_copy_library_update',
		reloadTimeout: 600
	}

	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.action('get');
	}

	this.action = function(action_type){
		if(action_type == 'get'){
			var url = _self.properties.siteUrl+_self.properties.urlGet;
			var data = {gid: _self.properties.library_gid, url: _self.properties.library_update_url}
		}
		if(action_type == 'unpack'){
			var url = _self.properties.siteUrl+_self.properties.urlUnpack;
			var data = {gid: _self.properties.library_gid, file: _self.properties.library_archive_file}
		}
		if(action_type == 'copy'){
			var url = _self.properties.siteUrl+_self.properties.urlCopy;
			var data = {gid: _self.properties.library_gid}
		}

		$.ajax({
			url: url, 
			cache: false,
			type: 'POST',
			data: data, 
			dataType: 'json',
			success: function(data){
				if(typeof(data.error) != 'undefined' && data.error != ''){
					$("#"+action_type+"_block > .action-block").html('<font class="error">'+data.error+"</font>");
					$("#"+action_type+"_block > .action-block").append('<br><a href="#" id="'+action_type+'_refresh">Refresh</a>');
					$('#'+action_type+'_refresh').unbind().bind('click', function(){
						_self.action(action_type);
					});
				}else{
					$("#"+action_type+"_block > .action-block").html('');
					if(typeof(data.info) != 'undefined' && data.info != ''){
						$("#"+action_type+"_block > .action-block").append(data.info+'<br>');
					}
					$("#"+action_type+"_block > .action-block").append('<font class="success">'+data.success+"</font>");
					$("#"+action_type+"_block").removeClass('hided');

					if(data.next_step){
						setTimeout( function(){
							_self.action(data.next_step);
						}, _self.properties.reloadTimeout);
					}
				}
			}
		});
	}
	_self.Init(optionArr);

};
