function cookiePolicy(optionArr){
	this.properties = {
		siteUrl: '',
		blockId: 'cookie_policy_block',
		linkId: 'cookie_policy_link',
		closeId: 'cookie_policy_close',
		name: 'cookie_policy',
		expires: 604800,
		path: '/',
		domain: '',
		secure: false,
	};

	var _self = this;
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
			
		if (typeof _self.properties.expires == "number" && _self.properties.expires) {
			var d = new Date();
			d.setTime(d.getTime() + _self.properties.expires*1000);
			_self.properties.expires = _self.properties.expires = d;
		}
		
		if (_self.properties.expires && _self.properties.expires.toUTCString) {
			_self.properties.expires = _self.properties.expires.toUTCString();
		}
		
		$('#'+_self.properties.linkId).bind('click', function(){
			_self.set_cookie();
			$('#'+_self.properties.blockId).hide();
			return true;
		});
		
		$('#'+_self.properties.closeId).bind('click', function(){
			_self.set_cookie();
			$('#'+_self.properties.blockId).hide();
			return false;
		});
	}
	
	this.set_cookie = function() {
		var cookie = _self.properties.name + "=" + encodeURIComponent(1);
		
		if(_self.properties.expires) cookie += ';expires=' + _self.properties.expires;
		if(_self.properties.secure) cookie += ';path=' + _self.properties.path;
		if(_self.properties.secure) cookie += ';domain=' + _self.properties.domain;
		if(_self.properties.secure) cookie += ';secure=true'
							
		document.cookie = cookie;
	}
	
	_self.Init(optionArr);
}
