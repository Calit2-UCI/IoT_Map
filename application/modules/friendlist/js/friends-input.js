function friendsInput(optionArr) {
	this.properties = {
		siteUrl: '',
		rand: '',
		id_user: '',
		load_user_link: 'friendlist/ajax_get_friends_data/',
		load_form: 'friendlist/ajax_get_friends_form/',
		load_data: 'friendlist/ajax_get_selected_friends/',
		id_main: '',
		id_text: '',
		id_open: '',
		id_hidden_user: '',
		id_bg: '',
		id_select: '',
		id_items: 'user_select_items',
		id_clear: 'user_clear_link',
		id_close: 'user_close_link',
		id_search: 'user_search',
		id_page: 'user_page',
		values_callback: function() {
		},
		contentObj: new loadingContent({
			loadBlockWidth: '680px', closeBtnPadding: 15
		})
	};
	var _self = this;

	this.errors = {
	};

	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);
		_self.properties.id_main = 'user_select_' + _self.properties.rand;
		_self.properties.id_open = 'user_open_' + _self.properties.rand;
		_self.properties.id_hidden_user = 'user_hidden_' + _self.properties.rand;

		$('#' + _self.properties.id_open).bind('click', function() {
			_self.open_form();
			return false;
		});
	};

	this.open_form = function() {
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.load_form,
			cache: false,
			success: function(data) {
				_self.properties.contentObj.show_load_block(data);
				$('#' + _self.properties.id_clear).unbind().bind('click', function() {
					_self.set_values_text('', 0, 0, '');
					_self.properties.contentObj.hide_load_block();
					return false;
				});
				_self.load_users();
				$('#' + _self.properties.id_search).unbind().bind('keyup', function() {
					_self.load_users($(this).val(), 1);
				});
				$('#' + _self.properties.id_close).unbind().bind('click', function() {
					_self.properties.contentObj.hide_load_block();
					return false;
				});
			}
		});
	};

	this.load_users = function(name, page) {
		name = name || '';
		page = page || 1;

		var send_data = {page: page};
		if (name)
			send_data.name = name;

		$.ajax({
			url: _self.properties.siteUrl + _self.properties.load_user_link,
			dataType: 'json',
			type: 'POST',
			data: send_data,
			cache: false,
			success: function(data) {
				_self.display_select(data);
			}
		});
	};

	this.display_select = function(data) {
		$('#' + _self.properties.id_items).unbind();
		$('#' + _self.properties.id_items).empty();
		for (var id in data.items) {
			$('#' + _self.properties.id_items).append('<li index="' + data.items[id].id + '">' + data.items[id].output_name + (data.items[id].output_name != data.items[id].nickname ? ' (' + data.items[id].nickname + ')' : '') + '</li>');
		}
		_self.generate_pages(data.pages, data.current_page, name);
		$('#' + _self.properties.id_items + ' li').bind('click', function() {
			_self.properties.values_callback($(this).attr('index'), $(this).text(), data);
			_self.properties.contentObj.hide_load_block();
		});
	};

	this.emptyValues = function() {
		_self.properties.id_user = '';
		$('#' + _self.properties.id_hidden_user).val(_self.properties.id_user).change();
	};

	this.generate_pages = function(pages, current_page, name) {
		$('#' + _self.properties.id_page + ' a').unbind();
		$('#' + _self.properties.id_page).empty();
		if (pages > 1) {
			var start = Math.max(current_page - 2, 1);
			var count = Math.min(pages, 5);
			for (var i = start; i <= count; i++) {
				if (i == current_page) {
					$('#' + _self.properties.id_page).append('<ins class="current">' + i + '</ins>');
				} else {
					$('#' + _self.properties.id_page).append('<ins><a href="#">' + i + '</a></ins>');
				}
			}
			$('#' + _self.properties.id_page + ' a').bind('click', function() {
				_self.load_users(name, $(this).text());
				return false;
			});
		}
	};

	_self.Init(optionArr);
}
