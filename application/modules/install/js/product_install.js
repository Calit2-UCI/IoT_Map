function productInstall(optionArr){
	this.properties = {
		siteUrl: '',
		installType: 'product',
		installBlockID: 'modules_reload',
		actionsBlockID: 'actions_reload',
		overallBarID: 'overall_bar',
		moduleBarID: 'module_bar',
		modules: '',
		currentModule: '',
		currentModuleNum: '',
		currentLang: '',
		update_file: '',
		update_path: 'product_updates',
		urlRefreshOverallProduct: 'admin/install/ajax_overall_product',
		urlRefreshOverallProductUpdate: 'admin/install/ajax_overall_product_update',
		urlRefreshOverallModule: 'admin/install/ajax_overall_module/',
		urlStartInstallModule: 'admin/install/ajax_start_install/',
		urlDependenciesModule: 'admin/install/ajax_dependencies/',
		urlPermissionsModule: 'admin/install/ajax_permissions/',
		urlRequirementsModule: 'admin/install/ajax_requirements/',
		urlSqlModule: 'admin/install/ajax_sql/',
		urlFilesModule: 'admin/install/ajax_files/',
		urlChmodModule: 'admin/install/ajax_chmod/',
		urlLinkedModule: 'admin/install/ajax_linked/',
		urlSettingsModule: 'admin/install/ajax_settings/',
		urlPublicModule: 'admin/install/ajax_public/',
		urlLangs: 'admin/install/langs/',
		urlLangsUpdate: 'admin/install/ajax_langs_update/',
		urlModulesList: 'admin/install/ajax_modules_list/',
		urlModuleDelete: 'admin/install/ajax_module_delete/',
		urlDemoContent: 'admin/install/ajax_demo_content/',
		///module update
		urlUpdateStartInstallModule: 'admin/install/ajax_update_sql/',
		urlUpdateFilesModule: 'admin/install/ajax_files/',
		urlUpdateChmodModule: 'admin/install/ajax_chmod/',
		urlUpdateSettingsModule: 'admin/install/ajax_update_settings/',
		urlUpdatePublicModule: 'admin/install/ajax_update_public/',
		modules: {},
		reloadTimeout: 100
	};

	var _self = this;
	var _submitting = false;

	this.errors = {
	};

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);

/*		setTimeout( function(){
			_self.test_overall_progress();
		}, _self.properties.reloadTimeout);

		setTimeout( function(){
			_self.test_module_progress();
		}, _self.properties.reloadTimeout);
*/
	};

	this.request = function(type){
		if(type === 'overall_product'){
			url = _self.properties.siteUrl+_self.properties.urlRefreshOverallProduct;
		}
		if(type === 'overall_product_update'){
			url = _self.properties.siteUrl+_self.properties.urlRefreshOverallProductUpdate;
			if (_self.properties.installType == 'product_update'){
				url += '/'+_self.properties.update_file+'/'+_self.properties.update_path;
			}
		}
		if(type === 'overall_module'){
			url = _self.properties.siteUrl+_self.properties.urlRefreshOverallModule+_self.properties.currentModule;
		}
		if(type === 'start_install'){
			url = _self.properties.siteUrl+_self.properties.urlStartInstallModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'start_update'){
			url = _self.properties.siteUrl+_self.properties.urlUpdateStartInstallModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'dependencies'){
			url = _self.properties.siteUrl+_self.properties.urlDependenciesModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'permissions'){
			url = _self.properties.siteUrl+_self.properties.urlPermissionsModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'requirements'){
			url = _self.properties.siteUrl+_self.properties.urlRequirementsModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'sql'){
			url = _self.properties.siteUrl+_self.properties.urlSqlModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type == 'files'){
			url = _self.properties.siteUrl+_self.properties.urlFilesModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type == 'update_files'){
			url = _self.properties.siteUrl+_self.properties.urlUpdateFilesModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type == 'chmod'){
			url = _self.properties.siteUrl+_self.properties.urlChmodModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type == 'update_chmod'){
			url = _self.properties.siteUrl+_self.properties.urlUpdateChmodModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type == 'linked'){
			url = _self.properties.siteUrl+_self.properties.urlLinkedModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'settings'){
			url = _self.properties.siteUrl+_self.properties.urlSettingsModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'update_settings'){
			url = _self.properties.siteUrl+_self.properties.urlUpdateSettingsModule+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		if(type === 'public'){
			url = _self.properties.siteUrl+_self.properties.urlPublicModule+_self.properties.currentModule+'/'+_self.properties.installType;
			if (_self.properties.installType == 'product_update'){
				url += '/'+_self.properties.update_file+'/'+_self.properties.update_path;
			}
		}
		if(type === 'update_public'){
			url = _self.properties.siteUrl+_self.properties.urlUpdatePublicModule+_self.properties.currentModule+'/'+_self.properties.installType;
			if (_self.properties.installType == 'product_update'){
				url += '/'+_self.properties.update_file+'/'+_self.properties.update_path;
			}
		}
		if(type === 'langs_update'){
			url = _self.properties.siteUrl+_self.properties.urlLangsUpdate+_self.properties.currentLang+'/'+_self.properties.currentModule;
		}
		if(type === 'module_delete'){
			url = _self.properties.siteUrl+_self.properties.urlModuleDelete+_self.properties.currentModule+'/'+_self.properties.installType;
			if (_self.properties.installType == 'product_update'){
				url += '/'+_self.properties.update_file+'/'+_self.properties.update_path;
			}
		}
		if(type === 'demo_content'){
			url = _self.properties.siteUrl+_self.properties.urlDemoContent+_self.properties.currentModule+'/'+_self.properties.installType;
		}
		$.ajax({
			url: url,
			cache: false,
			success: function(data){
				$("#"+_self.properties.installBlockID).html(data);
			}
		});
	};

	this.submit_settings = function(){
		if(true === _submitting) {
			return;
		};
		_submitting = true;
		url = _self.properties.siteUrl+_self.properties.urlSettingsModule+_self.properties.currentModule;
		$.ajax({
			url: url,
			type: 'POST',
			cache: false,
			data: $("#settings-submit-form").serialize(),
			success: function(data){
				$("#"+_self.properties.installBlockID).html(data);
				_submitting = false;
			}
		});
	};

	this.delayed_request = function(type, delay){
		delay = delay || _self.properties.reloadTimeout;
		setTimeout( function(){
			_self.request(type);
		}, delay);
	};

	this.update_overall_progress = function(progress){
		$("#"+_self.properties.overallBarID+' div.bar').html(progress + '%');
		$("#"+_self.properties.overallBarID+' div.bar').css('width', progress + '%');
	};

	this.update_module_progress = function(progress){
		$("#"+_self.properties.moduleBarID+' div.bar').html(progress + '%');
		$("#"+_self.properties.moduleBarID+' div.bar').css('width', progress + '%');
	};

	this.test_overall_progress = function(){
		var progress = parseInt($("#"+_self.properties.overallBarID+' div.bar').html());
		progress = progress + 5;
		if(progress > 100) progress=0;
		_self.update_overall_progress(progress);

		setTimeout( function(){
			_self.test_overall_progress();
		}, _self.properties.reloadTimeout);

	};

	this.test_module_progress = function(){
		var progress = parseInt($("#"+_self.properties.moduleBarID+' div.bar').html());
		progress = progress + 10;
		if(progress > 100) progress=0;
		_self.update_module_progress(progress);

		setTimeout( function(){
			_self.test_module_progress();
		}, _self.properties.reloadTimeout);
	};

	this.langs_init = function() {
		_self.langs_bind_events();
		_self.langs_set_default();
	};

	this.langs_bind_events = function() {
		$('#install_langs').find(':checkbox').bind('change', function() {
			var radio = $(this).parent().next('td').find(':radio');
			if($(this).is(':checked')) {
				radio.attr('disabled', null);
			} else {
				if($('#install_langs').find(':checkbox:checked').length == 0){
					$(this).prop('checked', true); return;
				}
				radio.attr('disabled', 'disabled').prop('checked', false);
			}
			_self.langs_set_default();
		});
	};

	this.langs_set_default = function() {
		if('undefined' === typeof(($('[name=default]:checked')).val())){
			$('[name=default]:enabled:first').prop('checked', true);
		};
	};

	this.get_modules = function() {
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlModulesList,
			dataType: 'json',
			cache: false,
			async: false,
			success: function(data) {
				return data;
			},
			error: function() {
				return false;
			}
		});
	};

	this.langs_update = function() {
		if(!_self.properties.currentLang) return;
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlModulesList,
			dataType: 'json',
			cache: false,
			async: false,
			success: function(data) {
				_self.properties.modules = data;
			},
			error: function() {
				return false;
			}
		});
		_self.langs_update_module();
	};

	this.langs_update_module = function() {
		if('' === _self.properties.currentModule) {
			_self.properties.currentModuleNum = 0;
		} else if(Object.keys(_self.properties.modules).length <= _self.properties.currentModuleNum) {
			document.location.href = _self.properties.siteUrl+_self.properties.urlLangs;
			return true;
		}
		_self.properties.currentModule = Object.keys(_self.properties.modules)[_self.properties.currentModuleNum];
		_self.update_overall_progress(Math.round(100 / Object.keys(_self.properties.modules).length * (Object.keys(_self.properties.modules).indexOf(_self.properties.currentModule) + 1)));
		$('#module_name').html(_self.properties.modules[_self.properties.currentModule].module_name);
		$('#module_desc').html(_self.properties.modules[_self.properties.currentModule].module_description);
		_self.delayed_request('langs_update');
		_self.properties.currentModuleNum++;
	};

	_self.Init(optionArr);

};
