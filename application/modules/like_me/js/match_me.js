"use strict";
function MatchMe(optionArr) {
	
	this.properties = {
		siteUrl: '',
		show_more_btn_id: 'show_more',
		content_block_id: 'matches',
		match_btn_class: 'match-button-content',
		match_block_class: 'like_me-item',
		page: 1,
		load_on_scroll: 1,
		all_loaded: 0,
		loading_status: 0,
		post_data: {},
		show_more_lang: 'Show more',
		ajax_load_matches_url: 'like_me/ajaxLoadMatches',
		common_ancestor: 'body',
	};

	var _self = this;
	var xhr_load_content = null;
	
	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);
		_self.init_controls();
	};

	this.uninit = function() {
		$(_self.properties.common_ancestor)
			.off('click', '#' + _self.properties.show_more_btn_id)
			.off('mouseenter', '.' + _self.properties.match_block_class + '>div')
			.off('mouseleave', '.' + _self.properties.match_block_class + '>div');
		return this;
	};
	
	this.init_controls = function() {		
		$(_self.properties.common_ancestor)
		.off('click','#' + _self.properties.show_more_btn_id).on('click','#'+ _self.properties.show_more_btn_id, function(){
			_self.nextPage();
		}).off('mouseenter','.' + _self.properties.match_block_class+'>div').on('mouseenter','.'+ _self.properties.match_block_class+'>div', function(){
			$(this).find('.info').stop().slideDown(100);
		}).off('mouseleave','.' + _self.properties.match_block_class+'>div').on('mouseleave','.'+ _self.properties.match_block_class+'>div', function(){
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
	};
	
	this.nextPage = function () {
		_self.properties.page++;
		_self.loadContent();	
	};
	
	this.loadContent = function (force) {
		force = force || false;
		if(force){
			if(xhr_load_content){
				xhr_load_content.abort();
			}
			_self.properties.page = 1;
		}else if(_self.properties.all_loaded == 1 || _self.properties.loading_status){
			return true;
		}
		_self.properties.loading_status = 1;

		xhr_load_content = $.ajax({
			url: _self.properties.siteUrl + _self.properties.ajax_load_matches_url + '/' + _self.properties.page,
			type: 'GET',
			dataType : "html",
			cache: false,
			success: function(resp){
				_self.createItemBlock(resp);
			},
			complete: function(){
				_self.properties.loading_status = 0;
			}
		});	
	};
	
	this.createItemBlock = function (content) {
		content = _self.addJSParams(content);
		var data = ''; var button_html = '';
		$('.' + _self.properties.match_btn_class).remove();
		$(content).each(function(){
			button_html = $(this).find('.' + _self.properties.match_btn_class);
			$(this).find('.' + _self.properties.match_block_class + ' i').addClass('w');
			data = $(this).find('.' + _self.properties.match_block_class + '>div').unwrap();
		});
		$('#' + _self.properties.content_block_id).find('.' + _self.properties.match_block_class).append(data);
		if (button_html) {
			$('#' + _self.properties.content_block_id).append(button_html);
		} else {
			_self.properties.load_on_scroll = 0;
			_self.properties.all_loaded = 1;
		}
	}
	
	this.escapeRegExp = function (str) {
		return str.replace(/[]/g, "\\$&");
	};
	
	this.addJSParams = function (data) {
		var str = 'siteUrl: site_url,';
		var reg = new RegExp( _self.escapeRegExp(str).replace(/[]/g,'|') , 'gi' );
		return data.replace(reg, '$&' + ' singleton: 0,');
	};
	
	_self.Init(optionArr);
	
}