'use strict';
function locationAutocomplete(optionArr) {
	this.properties = {
		siteUrl: '',
		rand: '',
		id_country: '',
		id_region: '',
		id_city: '',
		load_location_link: 'countries/ajax_get_locations/',
		load_data: 'countries/ajax_get_data/',
		locations: {},
		id_main: '',
		id_text: '',
		id_msg: '',
		id_open: '',
		id_hidden_country: '',
		id_hidden_region: '',
		id_hidden_city: '',
		id_bg: 'locationAutocompleteBg',
		id_select: '',
		id_items: 'country_select_items',
		id_back: 'country_select_back',
		id_clear: 'country_select_clear',
		id_close: 'country_select_close',
		id_search: 'city_search',
		id_city_page: 'city_page',
		timeout_obj: null,
		timeout: 500,
		dropdownClass: 'dropdown'
	};
	var _self = this;

	this.errors = {};

	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
		_self.properties.id_main = 'country_select_' + _self.properties.rand;
		_self.properties.id_text = 'country_text_' + _self.properties.rand;
		_self.properties.id_msg = 'country_msg_' + _self.properties.rand;
		_self.properties.id_hidden_country = 'country_hidden_' + _self.properties.rand;
		_self.properties.id_hidden_region = 'region_hidden_' + _self.properties.rand;
		_self.properties.id_hidden_city = 'city_hidden_' + _self.properties.rand;
		_self.properties.id_select = 'region_select_' + _self.properties.rand;

		$('#' + _self.properties.id_text).bind('keyup', function () {
			if (_self.properties.timeout_obj) {
				clearTimeout(_self.properties.timeout_obj);
			}
			_self.properties.timeout_obj = setTimeout(function () {
				var name = $('#' + _self.properties.id_text).val();
				_self.emptyValues();
				if (name) {
					_self.load_locations(name);
				} else {
					_self.closeBox();
				}
			}, _self.properties.timeout);
			return true;
		});

		_self.initBg();
		_self.initBox();
	};

	var highlightMatch = function (str, substr) {
		var start = str.toLowerCase().indexOf(substr);
		if (-1 === start) {
			return str;
		}
		var end = start + substr.length;
		var result = '';
		result = str.substr(0, end) + '</span>' + str.substr(end);
		result = result.substr(0, start) + '<span class="highlight">' + result.substr(start);
		return result;
	};

	this.load_locations = function (name) {
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.load_location_link + encodeURIComponent(name),
			dataType: 'json',
			type: 'GET',
			cache: false,
			success: function (data) {
				_self.properties.locations = data;
				if (data.all > 0) {
					var ul = $('#' + _self.properties.id_select + ' ul');
					var li;
					var lis = [];
					$('#' + _self.properties.id_select + ' ul').empty();
					for (var id in data.items.cities) {
						li = $('<li>').attr({
							gid: 'rs_' + id,
							city: data.items.cities[id].id,
							region: data.items.cities[id].id_region,
							country: data.items.cities[id].country_code
						}).html(highlightMatch(data.items.cities[id].name, name));
						lis.push(li);
					}
					for (var id in data.items.regions) {
						li = $('<li>').attr({
							gid: 'rs_' + id,
							region: data.items.regions[id].id,
							country: data.items.regions[id].country_code
						}).html(highlightMatch(data.items.regions[id].name, name));
						lis.push(li);
					}
					for (var id in data.items.countries) {
						li = $('<li>').attr({
							gid: 'rs_' + id,
							country: data.items.countries[id].code
						}).html(highlightMatch(data.items.countries[id].name, name));
						lis.push(li);
					}
					ul.append(lis);
					_self.openBox();
				} else {
					_self.closeBox();
				}
				findSuitable();
			}
		});
	};

	var findSuitable = function () {
		var found = false;
		var country = '';
		var region = 0;
		var city = 0;
		var name = '';
		$('li', '#' + _self.properties.id_select).each(function () {
			name = $('span', this).html();
			if ($('#' + _self.properties.id_text).val() === name) {
				country = $(this).attr('country');
				region = $(this).attr('region');
				city = $(this).attr('city');
				found = true;
				return false;
			}
		});
		if (found) {
			_self.set_values_text(country, region, city);
			highlight(name);
			$('#' + _self.properties.id_msg).hide();
		} else {
			$('#' + _self.properties.id_msg).show();
		}
		return found;
	};

	var highlight = function (name) {
		var highlightClass = 'highlight';
		var highlightWord = true;
		var keys = {cities: 'city', regions: 'region', countries: 'country'};
		for (var location_type in _self.properties.locations.items) {
			for (var location in _self.properties.locations.items[location_type]) {
				if (_self.properties.locations.items[location_type][location].name === name) {
					var line = $('[' + keys[location_type] + '="' + location + '"]', '#' + _self.properties.id_select).filter(function () {
						return $('span', this).text() === name;
					});
					if (highlightWord) {
						line.find('span').addClass(highlightClass);
					} else {
						line.addClass(highlightClass);
					}
					return true;
				}
			}
		}
		$('li', '#' + _self.properties.id_select).removeClass(highlightClass);
		return false;
	};

	this.set_values = function (type, variable, value, data) {
		var string_value = "";
		if (type === 'country') {
			$('#' + _self.properties.id_hidden_country).val(variable.toString()).change();
			_self.properties.id_country = variable.toString();

			$('#' + _self.properties.id_hidden_region).val(0).change();
			_self.properties.id_region = 0;

			$('#' + _self.properties.id_hidden_city).val(0).change();
			_self.properties.id_city = 0;

			string_value = value;

		} else if (type === 'region') {

			$('#' + _self.properties.id_hidden_region).val(variable).change();
			_self.properties.id_region = variable;

			$('#' + _self.properties.id_hidden_city).val(0).change();
			_self.properties.id_city = 0;

			string_value = data.country.name + ', ' + value;

		} else if (type === 'city') {

			$('#' + _self.properties.id_hidden_city).val(variable).change();
			_self.properties.id_city = variable;

			string_value = data.country.name + ', ' + data.region.name + ', ' + value;
		}

		if (string_value === '') {
			string_value = '...';
		}
		$('#' + _self.properties.id_text).val(string_value);
	};

	var fillInputs = function () {
		$('#' + _self.properties.id_hidden_country).val(_self.properties.id_country).change();
		$('#' + _self.properties.id_hidden_region).val(_self.properties.id_region).change();
		$('#' + _self.properties.id_hidden_city).val(_self.properties.id_city).change();
	};

	this.set_values_text = function (country, region, city, value) {
		if ('undefined' !== typeof value) {
			$('#' + _self.properties.id_text).val(value);
			$('#' + _self.properties.id_text).attr('title', value);
		}
		_self.properties.id_country = country;
		_self.properties.id_region = region;
		_self.properties.id_city = city;
		fillInputs();
	};

	this.emptyValues = function () {
		_self.properties.id_country = '';
		_self.properties.id_region = '';
		_self.properties.id_city = '';
		fillInputs();
	};

	this.set_values_external = function (type, variable) {
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.load_data + type + '/' + variable,
			dataType: 'json',
			cache: false,
			success: function (data) {
				if (type === 'country') {
					_self.set_values(type, variable, data.country.name, data);
				} else if (type === 'region') {
					_self.set_values(type, variable, data.region.name, data);
				} else if (type === 'city') {
					_self.set_values(type, variable, data.city.name, data);
				}
			}
		});
	};

	this.initBg = function () {
		$('body').append('<div id="' + _self.properties.id_bg + '"></div>');
		$('#' + _self.properties.id_bg).css({
			'display': 'none',
			'position': 'fixed',
			'z-index': '998',
			'width': '1px',
			'height': '1px',
			'left': '1px',
			'top': '1px'
		});
	};

	this.expandBg = function () {
		$('#' + _self.properties.id_bg).css({
			'width': $(window).width() + 'px',
			'height': $(window).height() + 'px',
			'display': 'block'
		}).bind('click', function () {
			_self.closeBox();
		});
	};

	this.collapseBg = function () {
		$('#' + _self.properties.id_bg).css({
			'width': '1px',
			'height': '1px',
			'display': 'none'
		}).unbind();
	};

	this.initBox = function () {
		_self.createDropDown();
		$('#' + _self.properties.id_select).on('click', 'li', function () {
			_self.set_values_text($(this).attr('country'), $(this).attr('region'), $(this).attr('city'), $(this).text());
			_self.closeBox();
		});
	};

	this.unsetBox = function () {
		$('#' + _self.properties.id_select).unbind().remove();
	};

	this.openBox = function () {
		_self.expandBg();
		_self.resetDropDown();
		$('#' + _self.properties.id_select).slideDown();
	};

	this.createDropDown = function () {
		$('body').append('<div class="' + _self.properties.dropdownClass + '" id="' + _self.properties.id_select + '"><ul></ul></div>');
		_self.resetDropDown();
	};

	this.resetDropDown = function () {
		var top = $('#' + _self.properties.id_text).offset().top + $('#' + _self.properties.id_text).outerHeight();

		$('#' + _self.properties.id_select).css({
			width: $('#' + _self.properties.id_text).outerWidth() - 2 + 'px',
			left: $('#' + _self.properties.id_text).offset().left + 'px',
			top: top + 'px'
		});
	};

	this.closeBox = function () {
		_self.collapseBg();
		$('#' + _self.properties.id_select).slideUp();
	};

	this.clearBox = function () {
		_self.set_values_text('', 0, 0, '');
	};

	_self.Init(optionArr);
}
