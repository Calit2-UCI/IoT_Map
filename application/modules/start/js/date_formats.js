function date_formats(optionArr){
	var _self = this;

	this.properties = {
		siteUrl: ''
	};
	this.errors = {};

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.bind_events();
		_self.update_formats($(this));
	};

	this.bind_events = function() {
		$('.format').bind('change', function() {
			_self.update_formats();
		});
		$('#tpl').bind('keyup', function() {
			_self.check_radio();
			_self.update_formats();
		});
		$('.sample').bind('click', function() {
			$('#tpl').val($('#tpl').val() + $(this).html());
			_self.check_radio();
			_self.update_formats();
		});
	};

	this.check_radio = function() {
		var name;
		var elem;
		$('.sample').each(function() {
			name = $(this).html().replace(/[\[\]]/g, '');
			elem = $('[name="' + name + '"]');
			if('radio' === elem.attr('type') && !elem.is(':checked') &&
					$('#tpl').val().indexOf('[' + name + ']') > 0) {
				$('[name="' + name + '"]:first').attr('checked', true);
			};
		});
	};

	this.update_formats = function() {
		var data = $('#date_format').serialize();
		$.ajax({
			type: "GET",
			url: _self.properties.siteUrl + '/admin/start/ajax_get_example',
			data: data,
			success: function(data) {
				$('#example').html(data);
			},
			error: function() {
				$('#example').html('error');
			}
		});
	};

	_self.Init(optionArr);
}