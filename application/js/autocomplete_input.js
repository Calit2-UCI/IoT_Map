function autocompleteInput(optionArr) {
	this.properties = {
		siteUrl: '/',
		dataUrl: '',
		id: 0,
		id_input: '',
		id_bg: '',
		id_text: '',
		id_hidden: '',
		format_callback: null,
		msg_no_results: '',
		select_callback: null,
		timeout_obj: null,
		timeout: 500,
		dropdownClass: 'dropdown',
		rand: 1,
		data: {}
	};

	var _self = this;

	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);

		if (!_self.properties.id_input) {
			_self.properties.id_input = 'input_select_' + _self.properties.rand;
		}
		if (!_self.properties.id_bg) {
			_self.properties.id_bg = 'input_bg_' + _self.properties.rand;
		}

		$('#' + _self.properties.id_text).bind('keyup', function() {
			if (_self.properties.timeout_obj) {
				clearTimeout(_self.properties.timeout_obj);
			}
			_self.properties.timeout_obj = setTimeout(function() {
				var keyword = $('#' + _self.properties.id_text).val();
				_self.emptyValues();
				_self.load_data(keyword);

			}, _self.properties.timeout);
			return true;
		});

		//_self.initBg();
		_self.initBox();
	};

	this.uninit = function() {
		_self.unsetBox();
		$('#' + _self.properties.id_bg).remove();
		$('body').unbind('click.dropBox');
	};

	this.initBg = function() {
		$('body').append('<div id="' + _self.properties.id_bg + '"></div>');
		$('#' + _self.properties.id_bg).css({
			'display': 'none',
			'position': 'fixed',
			'z-index': '98999',
			'width': '1px',
			'height': '1px',
			'left': '1px',
			'top': '1px'
		});
	};

	this.expandBg = function() {
		$('#' + _self.properties.id_bg).css({
			'width': $(window).width() + 'px',
			'height': $(window).height() + 'px',
			'display': 'block'
		}).bind('click', function() {
			_self.closeBox();
		});

	};

	this.collapseBg = function() {
		$('#' + _self.properties.id_bg).css({
			'width': '1px',
			'height': '1px',
			'display': 'none'
		}).unbind();
	};

	this.initBox = function() {
		$('body').append('<div class="' + _self.properties.dropdownClass + '" id="' + _self.properties.id_input + '"><ul></ul></div>');
		_self.resetDropDown();

		$('#' + _self.properties.id_input).on('click', 'li', function() {
			var id = $(this).attr('data-id');
			if(id) {
				_self.set_values_text(id, $(this).text());
			}
		});
	};

	this.unsetBox = function() {
		$('#' + _self.properties.id_input).unbind().remove();
	};

	this.openBox = function() {
		_self.expandBg();
		_self.resetDropDown();
		$('#' + _self.properties.id_input).slideDown();
		$('body').unbind('click.dropBox').bind('click.dropBox', function(e) {
			_self.closeBox();
		});
	};

	this.resetDropDown = function() {
		var top = $('#' + _self.properties.id_text).offset().top + $('#' + _self.properties.id_text).outerHeight();

		$('#' + _self.properties.id_input).css({
			width: $('#' + _self.properties.id_text).width() + 'px',
			left: $('#' + _self.properties.id_text).offset().left + 'px',
			top: top + 'px'
		});
	};

	this.closeBox = function() {
		$('body').unbind('click.dropBox');
		_self.collapseBg();
		$('#' + _self.properties.id_input).slideUp();
	};

	this.clearBox = function() {
		_self.set_values_text('', '');
		_self.properties.contentObj.hide_load_block();
	};

	this.load_data = function(keyword) {
		if (!keyword) {
			return _self.closeBox();
		}
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.dataUrl,
			dataType: 'json',
			type: 'POST',
			data: {search: keyword},
			cache: false,
			success: function(data) {
				_self.properties.data = data;
				_self.display(_self.properties.data);
			}
		});
	};

	this.display = function(data) {
		if (data.all > 0) {
			$('#' + _self.properties.id_input + ' ul').empty();
			for (var id in data.items) {
				$('#' + _self.properties.id_input + ' ul')
						.append('<li gid="rs_' + id + '" data-id="' + id + '">' + _self.properties.format_callback(data.items[id]) + '</li>');
			}
			_self.openBox();
		} else if(_self.properties.msg_no_results.length) {
			$('#' + _self.properties.id_input + ' ul').empty();
			$('#' + _self.properties.id_input + ' ul')
					.append('<li gid="rs_empty">' + _self.properties.msg_no_results + '</li>');
			_self.openBox();
		} else {
			_self.closeBox();
		}
	};

	this.set_values_text = function(id, value) {
		$('#' + _self.properties.id_text).val(value);
		_self.properties.id = id;
		$('#' + _self.properties.id_hidden).val(_self.properties.id).change();
		_self.closeBox();
		if(_self.properties.select_callback) {
			_self.properties.select_callback(_self.properties.data.items[id]);
		}
	};

	this.emptyValues = function() {
		_self.properties.id_user = '';
		$('#' + _self.properties.id_hidden).val(_self.properties.id).change();
	};

	_self.Init(optionArr);
}
