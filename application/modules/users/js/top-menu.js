function topMenu(optionArr){

	this.properties = {
		siteUrl: '/',
		parent: '#users-top-menu',
		summands: '.summand',
		sum: '.sum',
		noNotifications: '.no-notifications',
		hide: 'hide-always'
	};

	var _self = this;

	var init_multi_request = function() {
		if(typeof MultiRequest === 'undefined'){
			return false;
		}
		MultiRequest.initAction({
			gid: 'notifications-sum',
			params: {},
			paramsFunc: function(){return {};},
			callback: function(){
				_self.init_objects();
				_self.update_num();
			},
			period: 3,
			status: 0
		});

		if(id_user){
			MultiRequest.enableAction('notifications-sum');
		}
		$(document).on('users:login', function(){
			MultiRequest.enableAction('notifications-sum');
		}).on('users:logout, session:guest', function(){
			MultiRequest.disableAction('notifications-sum');
		});

	};

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.init_objects();
		_self.update_num();
		init_multi_request();
	};

	this.init_objects = function(){
		_self.summands = $(_self.properties.summands, _self.properties.parent);
		_self.sum = $(_self.properties.sum, _self.properties.parent);
	};

	this.update_num = function() {
		var sum = get_sum();
		_self.sum.html(sum ? sum : '');
		if(!sum) {
			$(_self.properties.noNotifications).removeClass(_self.properties.hide);
		} else {
			$(_self.properties.noNotifications).addClass(_self.properties.hide);
		}
	};

	var get_sum = function() {
		var sum = 0;
		var count = 0;
		_self.summands.each(function(){
			count = parseInt($(this).html());
			var parent = $(this).parents('li:first');
			if(count) {
				sum += count;
				parent.removeClass(_self.properties.hide);
			} else {
				parent.addClass(_self.properties.hide);
			}
		});
		return sum;
	};

	_self.Init(optionArr);
}