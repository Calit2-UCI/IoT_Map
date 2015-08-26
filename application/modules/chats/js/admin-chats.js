function adminChats(optionArr) {

	this.properties = {
		siteUrl: ''
	};

	var _self = this;

	this.errors = {};

	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);
		$('tr:odd', '#chatsList').addClass('zebra');
		_self.bind_events();
	};

	this.bind_events = function() {
	};

	_self.Init(optionArr);
}