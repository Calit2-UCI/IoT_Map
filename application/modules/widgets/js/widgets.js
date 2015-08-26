new function(){
    var Util = {
        extendObject: function(a, b) {
            for(prop in b){
                a[prop] = b[prop];
            }
            return a;
        },
        proto: 'https:' === document.location.protocol ? 'https://' : 'http://'
    };
    
    var interval_id, last_hash, cache_bust = 1, rm_callback, FALSE = !1, 
		postMessage = 'postMessage', addEventListener = 'addEventListener', p_receiveMessage, 
		has_postMessage = window[postMessage];
		
	var p_receiveMessage = function(callback, source_origin, delay){
		if(has_postMessage){
			if(callback){
				rm_callback && p_receiveMessage();
				rm_callback = function(e){
					if(e.origin !== source_origin){
						return FALSE;
					}
					callback(e);
				};
			}
			if(window[addEventListener]){
				window[callback ? addEventListener : 'removeEventListener']('message', rm_callback, FALSE);
			}else{
				window[callback ? 'attachEvent' : 'detachEvent']('onmessage', rm_callback);
			}
		}else{
			interval_id && clearInterval(interval_id);
			interval_id = null;
			if(callback){
				delay = typeof source_origin === 'number' ? source_origin : typeof delay === 'number' ? delay : 100;
				interval_id = setInterval(function(){
					var hash = document.location.hash, re = /^#?\d+&/;
					if(hash !== last_hash && re.test(hash)){
						last_hash = hash;
						callback({ data: hash.replace( re, '' ) });
					}
				}, delay );
			}
		}
	};
   
	var js = document.getElementById('pg-widget');
	var url = js.src.replace('/application/modules/widgets/js/widgets.js', '').replace(/http(s)?:\/\//, '');
	var pos = url.indexOf('/');
	var server = (pos > 0) ? url.substring(0, pos) : url;
	
	var items = document.getElementsByTagName('div');
	for(var i in items){
		if(typeof items[i] !== 'object' || !items[i].className || items[i].className.substring(0, 3) !== 'pg-') continue;
		Widget = {
			created: false,
			item: items[i],
			iframe: null,
			height: 0,
			show: function(){
				if(this.created) return;
				var gid = this.item.className.replace('pg-', '');
				var size = this.item.getAttribute('data-size');
				var lang = this.item.getAttribute('data-lang');
				
				var widget_url = Util.proto + url;
				if(lang) widget_url += '/' + lang;
				widget_url += '/widgets/index/'+gid+'#'+encodeURIComponent(document.location.href);
		
				this.iframe = document.createElement('iframe');
				this.iframe.src = widget_url;
				this.iframe.height = '400px';
				this.iframe.scrolling = 'no';
				if(size) this.iframe.width = this.item.getAttribute('data-size')+'px';
				this.iframe.style.border = 'none';
				//this.iframe.style.overflow = 'auto';
				this.item.appendChild(this.iframe);
		
				var _self = this;
				p_receiveMessage(function(e){
					var h = Number(e.data.replace(new RegExp('.*widget_'+gid+'_height=(\\d+)(?:&|$)'), '$1'));
					if(!isNaN(h) && h > 0 && h !== _self.height) _self.iframe.height = _self.height = h;
				}, Util.proto + server);
            
				this.created = true;
			}
		};
		Widget.show();
	}
}();
