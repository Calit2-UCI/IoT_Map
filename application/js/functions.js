var globals = {}, // global vars
	loading_object,
	error_object,
	timeout,
	loaded_scripts = [],
	tmp_objects = [],
	tmp_objects_alien = ['FB'],
	tmp_scripts_alien = ['//connect.facebook.net/'],
	//use_pjax = 0,
	log_events = 0;

var jq_remove = $.fn.remove;
$.fn.remove = function() {
	if(typeof arguments[0] !== 'undefined'){
		$(this).filter(arguments[0]).trigger('remove');
	}else{
		$(this).trigger('remove');
	}
	return jq_remove.apply(this, arguments);
};

$.fn.outerHTML = function() {
   return (this[0]) ? this[0].outerHTML : '';  
};

if($.support.pjax && window.use_pjax){
	$(document).on('click', 'a[data-pjax!="0"][target!="_blank"]', function(event) {
		var container = $(this).attr('data-pjax-container') || pjax_container;
		var options = {};
		if($(this).attr('data-pjax-no-scroll')){
			options.scrollTo = false;
		}
		$.pjax.click(event, container, options);
	});
	$(window).bind('popstate', function(){});
	$.pjax.defaults.timeout = 30000;
}


$(document)
	.on('click', '[data-history]', function(){
		var url = $(this).attr('data-history');
		if(url){
			if($.support.pjax){
				//window.history.pushState(null, "", url);
				window.history.replaceState(null, "", url);
			}
		}
	})
	.on('scroll ready pjax:success', function(){
		if($('#top_bar_fixed').size()){
			if($(document).scrollTop() > $('#top_bar_fixed').offset().top){
				if($('#top_bar_fixed').find('.menu-search-bar').css('position') != 'fixed'){
					$('#top_bar_fixed').css({height:$('#top_bar_fixed').height()+'px'});
					$('#top_bar_fixed').find('.menu-search-bar').css({position: 'fixed', width: '100%', top: '0', left: '0'});
					$('#top_bar_fixed').find('.menu-search-bar .submenu').css({position: 'fixed'});
				}
			}else{
				if($('#top_bar_fixed').find('.menu-search-bar').css('position') != 'static'){
					$('#top_bar_fixed').css({height:'auto'});
					$('#top_bar_fixed').find('.menu-search-bar').css({position: 'static', width: 'auto', top: 'auto'});
					$('#top_bar_fixed').find('.menu-search-bar .submenu').css({position: 'absolute'});
				}
			}
		}
	})
	.on('pjax:error', function(e){
		if(log_events) log(e.type);
	})
	.on('pjax:hardload', function(e, data){
		if(log_events) log(e.type);
		$('body').html(data.responseText);
	})
	.on('pjax:start', function(e){
		if(log_events) log(e.type);
		$(pjax_container).stop().animate({opacity: 0.3}, 400);
		for(var i in tmp_objects){
			if(tmp_objects.hasOwnProperty(i) && typeof window[i] !== 'undefined'){
				if(typeof window[i].uninit === 'function'){
					window[i].uninit();
					if(log_events) log('uninit: ' + /(\w+)\(/.exec(window[i].constructor.toString())[1]);
				}
				delete window[i];
				delete tmp_objects[i];
			}
		}

		for(var i in tmp_objects_alien) if(tmp_objects_alien.hasOwnProperty(i) && typeof window[i] !== 'undefined'){
			delete window[tmp_objects_alien[i]];
		}
		for(var i in tmp_scripts_alien) if(tmp_scripts_alien.hasOwnProperty(i)){
			$('script[src*="'+tmp_scripts_alien[i]+'"]').remove();
		}
	})
	.on('pjax:end', function(e){
		if(log_events) log(e.type);
		$(pjax_container).stop().animate({opacity: 1}, 100);
	})
	.on('pjax:send', function(e) {
		if(log_events) log(e.type);
		loading_object.setLoading();
	})
	.on('pjax:complete', function(e) {
		if(log_events) log(e.type);
		loading_object.unsetLoading();
	})
	.on('submit', 'form', function(event) {
		if($.support.pjax && window.use_pjax){
			var container = $(pjax_container);
			var form = event.currentTarget;
			if(!form.action){
				form.action = location.href;
			}
			var eventHandler = event.delegateTarget.activeElement;
			var options = {
				processData: false,
				contentType: false,
				cache: false,
				data:{}
			};

			options.data = new FormData($(form)[0]);
			if(typeof eventHandler.type !== 'undefined' && (eventHandler.type == 'submit' || eventHandler.type == 'button') && eventHandler.name){
				options.data.append(eventHandler.name, eventHandler.value);
			}else{
				var submit_btn = $(form).find('input[type="submit"][data-pjax-submit!="0"]');
				if(submit_btn.attr('name') && submit_btn.val()){
					options.data.append(submit_btn.attr('name'), submit_btn.val());
				}
			}
			$.pjax.submit(event, container, options);
		}
	})
	.ready(function(e){
		loading_object=new Loading;
		$(document).ajaxSend(function(e, jqxhr, options){
			if(!options.backend){
				loading_object.setLoading();
			}
		}).ajaxStop(function() {
			loading_object.unsetLoading();
		});

		if(typeof MultiRequest !== 'undefined'){
			MultiRequest.setProperties('url', site_url+'start/ajax_backend/').init();
		}
	})
	.on('ready pjax:success', function(e){
		if(log_events) log(e.type);
		error_object=new Errors({position: site_error_position});
		timeout = 0;

		if($('#error_block').html()){
			error_object.show_error_block($('#error_block').html(), 'error');
			timeout = 2000;
		}
		if($('#info_block').html()){
			if(timeout){
				setTimeout( function(){
					error_object.show_error_block($('#info_block').html(), 'info');
				}, timeout);
			}else{
				error_object.show_error_block($('#info_block').html(), 'info');
			}
		}
		if($('#success_block').html()){
			if(timeout){
				setTimeout( function(){
					error_object.show_error_block($('#success_block').html(), 'success');
				}, timeout);
			}else{
				error_object.show_error_block($('#success_block').html(), 'success');
			}
		}
		if(typeof $().placeholder === 'function'){
			$('input, textarea').placeholder();
		}
		if(window.js_events && js_events.length){
			if(typeof js_events === 'object'){
				for(var i in js_events) if(js_events.hasOwnProperty(i)){
					$(document).trigger(js_events[i]);
					if(log_events) log('js event: ' + js_events[i]);
				}
			}else{
				$(document).trigger(js_events);
				if(log_events) log('js event: ' + js_events);
			}
			js_events = null;
		}
	})
	.on('scriptLoad', function(e, obj_name){
		obj_name = obj_name || null;
		if(obj_name){
			if(log_events) log('scriptLoad:'+obj_name);
			$(document).trigger('scriptLoad:'+obj_name);
		}
	}).on('session:guest', function(){
		id_user = 0;
	});


function loadScripts(url, callback, obj_for_kill, ajaxOptions){
	obj_for_kill = obj_for_kill || '';
	ajaxOptions = ajaxOptions || {};
	var script_url = '', cb;

	if(typeof url === 'object' && url.length){
		script_url = url.shift();
		cb = url.length ? function(){loadScripts(url, callback, obj_for_kill, ajaxOptions);} : callback;
	}else if(typeof url === 'string'){
		script_url = url;
		cb = callback;
	}

	if(typeof obj_for_kill === 'object' && obj_for_kill.length){
		for(var i in obj_for_kill){
			tmp_objects[obj_for_kill[i]] = script_url;
		}
	}else if(obj_for_kill){
		tmp_objects[obj_for_kill] = script_url;
	}

	if(script_url){
		var scriptname = script_url.match(/[^\/?#]+(?=$|[?#])/)[0];
		if(typeof scriptname !== 'undefined'){
			var ext = scriptname.lastIndexOf('.');
			if(ext){
				scriptname = scriptname.substr(0,ext);
			}
			if(scriptname.substr(-4) === '.min'){
				scriptname = scriptname.substr(0,scriptname.length-4);
			}
		}else{
			var scriptname = '';
		}
		var event = scriptname ? 'scriptLoad:'+scriptname : 'scriptLoad';

		for(var i in loaded_scripts){
			if(script_url === loaded_scripts[i]){
				cb();
				$(document).trigger(event);
				if(log_events) log(event);
				return;
			}
		}

		var options = {
			url: script_url,
			success: function(){loaded_scripts.push(script_url); cb(); $(document).trigger(event); if(log_events) log(event);},
			error: function(xhr, textStatus, errorThrown){log('error loading script: '+script_url+'. '+textStatus+'. '+errorThrown, 'error');},
			dataType: 'script',
			cache: true
		};

		$.extend(true, options, ajaxOptions);
		$.ajax(options);
	}else if(typeof console !== 'undefined'){
		console.warn('Error. Load script: invalid url');
	}
}

function locationHref(url, new_window){
	new_window = new_window || false;
	if($.support.pjax && window.use_pjax){
		$.pjax({url: url, container: pjax_container});
	}else{
		if(new_window){
			window.open(url);
		}else{
			location.href = url;
		}
	}
}

function log(str, type){
	type = type || 'log';
	if(typeof console !== 'undefined'){
		switch(type){
			case 'error':
				console.error(str);
				break;
			case 'warn':
				console.warn(str);
				break;
			case 'log':
			default:
				console.log(str);
				break;
		}
	}
}

function removeHTML(str) {
	var tmp = document.createElement('div');
	tmp.innerHTML = str;
	return tmp.textContent || tmp.innerText || '';
}

function in_array(needle, haystack, argStrict) {
	var key = '',
		strict = !!argStrict;
	if (strict) {
		for (key in haystack) {
			if (haystack[key] === needle) {
				return true;
			}
		}
	} else {
		for (key in haystack) {
			if (haystack[key] == needle) {
				return true;
			}
		}
	}
	return false;
}

function autoResize(id){
    var newheight;
    var newwidth;
    if(document.getElementById){
        newheight = document.getElementById(id).contentWindow.document.body.scrollHeight;
        newwidth = document.getElementById(id).contentWindow.document.body.scrollWidth;
    }
    document.getElementById(id).height = newheight + 'px';
    document.getElementById(id).width = newwidth + 'px';
}

function redirect(url) {
	if (window.$ && $.support.pjax && window.use_pjax) {
		$.pjax({url: url, container: '#pjaxcontainer'});
	} else {
		window.location.href = url;
	}
}

/*window.system_alert = window.alert;
window.system_confirm = window.confirm;
window.alert = function(msg, title){
	title = title || '&nbsp;';
	if(window.$ && typeof $().dialog !== 'undefined' && typeof loadingContent !== 'undefined'){
		alert_window = new loadingContent({loadBlockWidth: '300px', closeBtnClass: 'w'});
		alert_window.show_load_block('<div class="load_content"><h1>'+title+'</h1><div class="inside"><p>'+msg+'</p><p><input type="button" value="Ok" onclick="alert_window.destroy()" /></p></div></div>');
	}else{
		this.system_alert.apply(this, arguments);
	}
}
window.confirm = function(msg, title){
	title = title || '&nbsp;';
	if(window.$ && typeof $().dialog !== 'undefined' && typeof loadingContent !== 'undefined'){
		confirm_window = new loadingContent({loadBlockWidth: '300px', closeBtnClass: 'w'});
		confirm_window.show_load_block('<div class="load_content"><h1>'+title+'</h1><div class="inside"><p>'+msg+'</p><p><input type="button" value="Ok" onclick="confirm_window.destroy()" /></p></div></div>');
	}else{
		this.system_confirm.apply(this, arguments);
	}
}
*/