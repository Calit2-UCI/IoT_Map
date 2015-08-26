function adminPolls(optionArr) {
	
	this.properties = {
		siteUrl: ''
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		$('div.row:odd').addClass('zebra');
		$('.datepicker').datepicker({dateFormat:'dd-mm-yy'});
	}

	this.bind_events = function(){
		$('#use_expiration').click(function() {
			if (this.checked){
				$('#date_end').removeAttr('disabled');
			} else {
				$('#date_end').attr('disabled', 'disabled');
			}
		});
		$('#poll_language').change(function(){
			if ($(this).val() > 0) {
				$('.question').hide();
				$('#question_' + $(this).val()).show();
			} else {
				$('.question').show();
			}
		});
	}
	
	_self.Init(optionArr);
}

function adminPollsAnswers(optionArr) {
	
	this.properties = {
		siteUrl: '',
		counter: 0,
		show_results: false
	}
	
	var _self = this;

	this.errors = {
	}
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		$('.lef div.row:odd, .ref div.row:odd').addClass('zebra');
		$('.datepicker').datepicker({dateFormat:'dd-mm-yy'});
		$('.color-pick').each(function(){
			if (!$(this).val()) {
				$(this).val(_self.get_rand_color());
			};
		});
		_self.update_preview();
	}
	
	this.bind_events = function() {
		$('input.default_answer').on('keyup change click mouseup', function() {
			_self.update_preview();
		});
        $('input.color-pick').on('change', function(){
            _self.update_preview();
        });
		$('a.delete_answer').on('click', function() {
			var id = +$(this).attr('id').replace(/\D+/g, '');
			_self.delete_answer(id);
		});
		
		$('#add_answer').click(function(){
			_self.add_answer();
		});
	}
	
	this.add_answer = function() {
		var counter = ++polls.properties.counter;
		var new_answer = $('#answer_tpl').html().replace(/-id-/g, counter);
		$('#add_answer').before(new_answer);
		var new_answer_preview = $('#preview_tpl').html().replace(/-id-/g, counter);
		$('#preview_tpl').before(new_answer_preview);
		$('#color_answer_' + counter).attr('value', polls.get_rand_color());
		_self.update_preview();
		jscolor.init();
		$('.lef div.row, .ref div.row').removeClass('zebra');
		$('.lef div.row:odd, .ref div.row:odd').addClass('zebra');
	}
	
	this.update_preview = function() {
		$('#results_answers').html('');
		for (i = 1; i <= _self.properties.counter; i++) {
			var answer_id = 'answer_' + i;
			var preview_id = $('.'+answer_id).attr('id');
			if ($('#' + answer_id).val() != undefined && $('input#color_'+answer_id).val() != undefined) {
				$('label[for=' + preview_id+']').html($('#' + answer_id).val());
				if(_self.properties.show_results) {
					$('#results_answers').append('<div class="result" id="result_answer_' + i + '">' + $('#' + answer_id).val()
						+'<div class="progress" style="background-color: #' + $('input#color_' + answer_id).val() + '; width: ' + (i * 1.5) + '%;"></div></div>');
				}
			}
		}
	}
	
	this.get_rand_color = function() {
		var min = 0;
		var max = 16777215;
		var random_int = Math.floor(min + (max - min) * Math.random());
		var rand_color_hex = random_int.toString(16);
		if(rand_color_hex.length != 6) {
			rand_color_hex = _self.get_rand_color();
		}
		return rand_color_hex;
	}

	this.delete_answer = function(id) {
		$('#row_answer_' + id).remove();
		$('#preview_answer_' + id).remove();
		if(_self.properties.show_results) {
			$('#result_answer_' + id).remove();
		}
		$('.lef div.row, .ref div.row').removeClass('zebra');
		$('.lef div.row:odd, .ref div.row:odd').addClass('zebra');
		_self.update_preview();
	}
	
	_self.Init(optionArr);
}