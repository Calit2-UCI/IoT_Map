Number.prototype.formatMoney = function(c, d, t){
	var n = this, dash = ('-' == c || '\u2013' == c ? true : false), c = dash ? 0 : isNaN(c = Math.abs(c)) ? 2 : c,	d = d == undefined ? ',' : d,
		t = t == undefined ? '.' : t, s = n < 0 ? '-' : '', i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + '', j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '') + (dash ? d + '\u2013' : '');
};

function adminPayments(optionArr) {

	this.properties = {}

	var _self = this;

	this.errors = {}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		$("div.row:odd").addClass("zebra");
		_self.bind_events();
		_self.set_format();
	}

	this.bind_events = function(){
		$('#gid, #abbr, input[name=dec_sep], input[name=gr_sep], input[name=dec_part]').bind('change keyup click', function() {
			_self.set_format();
		});
	}

	this.set_format = function() {
		var value = 1234.56;
		var gid = $('#gid').val();
		var abbr = $('#abbr').val();
		var decimal_separator = $('input[name=dec_sep]:checked').val();
		var group_separator = $('input[name=gr_sep]:checked').val();
		var decimal_part = $('input[name=dec_part]:checked').val();
		value = (value).formatMoney(decimal_part, decimal_separator, group_separator);
		$('#templates label').each(function() {
			var example = $('#' + ($(this).attr('for'))).val()
				.split('[abbr]').join(abbr)
				.split('[gid]').join(gid)
				.split('[value]').join(value);
			$(this).html(example);
		})
	}

	_self.Init(optionArr);
}