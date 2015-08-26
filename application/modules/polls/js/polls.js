function Polls(optionArr) {

	this.properties = {
		siteUrl: '',
		poll_id: ''
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.bind_events();
	}

	this.bind_events = function(){
		$('#poll_block_' + _self.properties.poll_id + ' .respond').bind('click', function(){
			_self.save_result($('#poll_block_' + _self.properties.poll_id));
			return false;
		});
		$('#poll_block_' + _self.properties.poll_id + ' .next_poll').bind('click', function(){
			_self.update_poll($('#poll_block_' + _self.properties.poll_id));
			return false;
		});
	}

	this.update_poll = function(poll_block, poll_id) {
		poll_id = poll_id || 0;
		$.get(_self.properties.siteUrl + 'polls/ajax_poll/' + poll_id, function(response) {
			poll_block.addClass('old_block').hide();
			poll_block.before(response);
			$('.old_block').remove();
		});
	}

	this.save_result = function(poll_block) {
		var form = poll_block.find('form');
		if (form.find('input[name^=answer]:checked').val() > 0) {
			poll_block.addClass('old_block').hide();
			var poll_data = form.serialize();
			var url = _self.properties.siteUrl + 'polls/ajax_save_result/';
			$.post(url, poll_data, function(response) {
				if(response!='error'){
					poll_block.before(response);
					$('.old_block').remove();
				}else{
					location.href=_self.properties.siteUrl+'polls';
				}
			});
		}
	}

	_self.Init(optionArr);
}


function PollsList(optionArr) {
	this.properties = {
		siteUrl: ''
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.bind_events();
	}

	this.bind_events = function() {
		$('.poll_question_link').bind('click', function(){
			if ($(this).next('.poll_results_content').is(':hidden')){
				_self.show_poll($(this));
			} else {
				_self.hide_poll($(this));
			}
		});
	}

	// Polls list
	this.show_poll = function(block) {
		var poll_id =  block.attr('id').replace(/\D+/g, '');
		var url		= _self.properties.siteUrl + 'polls/ajax_poll/' + poll_id + '/1';
		block.find('[data-role="expander"]').removeClass('down').addClass('up');
		if(block.next('.poll_results_content').html() == '') {
			block.next('.poll_results_content').load(url, function(){
				block.next('.poll_results_content').show();
			});
		} else {
			block.next('.poll_results_content').show();
		}
	}

	this.hide_poll = function(block) {
		block.find('[data-role="expander"]').removeClass('up').addClass('down');
		block.next('.poll_results_content').hide();
	}

	_self.Init(optionArr);

}