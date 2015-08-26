function search(optionArr) {
	this.properties = {
		siteUrl: '',
		currentForm: 'user',
		slideSteps: 2,
		slideCurrentStep: 1,
		currentFormType: 'line',
		selects: [],
		checkboxes: [],
		hlboxes: [],
		selectObject: {},
		checkboxObject: {},
		hlboxObject: {},
		pmFormUrl: 'users/ajax_perfect_match_form',
		userFormUrl: 'users/ajax_search_form',
		pmSearchUrl: 'users/ajax_perfect_match',
		userSearchUrl: 'users/ajax_search',
		pmCountUrl: 'users/ajax_search_counts/perfect_match',
		userCountUrl: 'users/ajax_search_counts/advanced',
		check_counts: true,
		resultsBlockId: {
			user: 'main_users_results',
			perfect_match: 'main_users_results'
		},
		preloadInterval: 1000,
		preloadUTS: 0,
		preloadTID: false,
		errorObj: new Errors(),
		resultPopupTO: 5000,
		resultPopupObjTO: null,
		preloadResultsObjTO: null,
		preloader: null,
		popup_autoposition: false,
		hide_popup: false
	};

	var _self = this;


	this.Init = function(options) {
		_self.properties = $.extend(_self.properties, options);
		_self.properties.formId = _self.properties.currentForm + '_' + _self.properties.currentFormType;

		_self.init_tabs();
		_self.init_form(_self.properties.currentForm);
		_self.init_result_popup();
	};

	this.init_tabs = function() {
		$('#resume-form-tab').bind('click', function() {
			_self.properties.currentForm = 'resume';
			_self.load_form(_self.properties.currentForm);
			return false;
		});

		$('#vacancy-form-tab').bind('click', function() {
			_self.properties.currentForm = 'vacancy';
			_self.load_form(_self.properties.currentForm);
			return false;
		});
	};

	this.load_form = function(form) {
		var url;
		if (form === 'user') {
			url = _self.properties.siteUrl + _self.properties.userFormUrl;
		} else if (form === 'perfect_match') {
			url = _self.properties.siteUrl + _self.properties.pmFormUrl;
		}

		$.ajax({
			url: url,
			type: 'GET',
			cache: false,
			success: function(data) {
				_self.properties.selectObject.clear();
				$('#search-form-block_' + _self.properties.formId).html(data);
				_self.init_form(form);
			}
		});

	};

	this.init_result_popup = function(form) {
		if (!$('#result_popup_' + _self.properties.formId).size()) {
			$('#search-form-block_' + _self.properties.formId).append('<div id="result_popup_' + _self.properties.formId + '" class="result-popup"></div>');
			$(document).on('mouseover', '#result_popup_' + _self.properties.formId, function() {
				if (_self.properties.resultPopupObjTO) {
					clearTimeout(_self.properties.resultPopupObjTO);
				}
			}).on('mouseout', '#result_popup_' + _self.properties.formId, function() {
				_self.properties.resultPopupObjTO = setTimeout(function() {
					$('#result_popup_' + _self.properties.formId).hide();
				}, _self.properties.resultPopupTO);
			});
		}
	};

	this.init_form = function(form) {
		$('#vacancy-form-tab, #resume-form-tab').removeClass('active');
		$('#' + form + '-form-tab').addClass('active');
		_self.properties.selectObject = new selectBox({elementsIDs: selects, force: true});
		_self.properties.checkboxObject = new checkBox({elementsIDs: checkboxes, force: true});
		_self.properties.hlboxObject = new hlBox({elementsIDs: hlboxes, force: true});

		// form type
		_self.properties.slideSteps = 1;
		_self.properties.slideCurrentStep = 1;
		if ($('#line-search-form_' + _self.properties.formId).length > 0) {
			_self.properties.currentFormType = 'line';
		}

		if ($('#short-search-form_' + _self.properties.formId).length > 0) {
			_self.properties.currentFormType = 'short';
		}

		if ($('#full-search-form_' + _self.properties.formId).length > 0) {
			_self.properties.slideSteps = 2;
			if ($('#full-search-form_' + _self.properties.formId).is(':visible')) {
				_self.properties.currentFormType = 'full';
				_self.properties.slideCurrentStep = 2;
			}
		}

		if (_self.properties.currentFormType !== 'line' && _self.properties.check_counts) {
			$('#search-form-block_' + _self.properties.formId)
					.on('change', 'input[type=text], input[type=hidden], input[type=checkbox]', function() {
						var top = ($(this).attr('type') === 'hidden') ? ($(this).next().hasClass('selectBox') ? $(this).next().position().top : $(this).parent().position().top) : $(this).position().top;
						var bottom = ($(this).attr('type') === 'hidden') ? ($(this).next().hasClass('selectBox') ? $(this).next().position().top + $(this).next().outerHeight() : $(this).parent().position().top + $(this).parent().outerHeight()) : $(this).position().top + $(this).outerHeight();
						var left = $(this).parent().position().left;
						var right = $(this).parent().width() + $(this).parent().position().left + parseInt($(this).parent().css('margin-left'));
						clearTimeout(_self.properties.preloadResultsObjTO);
						_self.properties.preloadResultsObjTO = setTimeout(function() {
							_self.preload_results(top, left, right, bottom);
						}, 250);
					})
					.on('remove', 'input[type=hidden]', function() {
						var top = $(this).parent().position().top;
						var bottom = $(this).parent().position().top + $(this).parent().outerHeight();
						var left = $(this).parent().position().left;
						var right = $(this).parent().width() + $(this).parent().position().left + parseInt($(this).parent().css('margin-left'));
						$(this).removeAttr('name'); // for prevent preload results before removing element from DOM
						clearTimeout(_self.properties.preloadResultsObjTO);
						_self.properties.preloadResultsObjTO = setTimeout(function() {
							_self.preload_results(top, left, right, bottom);
						}, 250);
					});
		}

		// more | less link
		if (_self.properties.currentFormType === 'line') {
			$('#more-options-link_' + _self.properties.formId).bind('click', function() {
				_self.load_form(_self.properties.currentForm);
				return false;
			});
		} else {
			$('#more-options-link_' + _self.properties.formId + ', #less-options-link_' + _self.properties.formId).hide();
			$('#more-options-link_' + _self.properties.formId + ', #less-options-link_' + _self.properties.formId).bind('click', function() {
				_self.slide_form();
				return false;
			});
			if (_self.properties.slideSteps > 1 && _self.properties.slideCurrentStep === _self.properties.slideSteps) {
				$('#less-options-link_' + _self.properties.formId).show();
			} else if (_self.properties.slideSteps > 1) {
				$('#more-options-link_' + _self.properties.formId).show();
			}
		}

		// submit button
		if (_self.properties.currentFormType !== 'line') {
			$('#main_search_button_' + _self.properties.formId).unbind().bind('click', function() {
				var blockID = _self.properties.resultsBlockId[form];
				if ($('#' + blockID).length > 0) {
					// ajax results loading
					_self.load_results();
					return false;
				}
			});
		}
	};

	this.slide_form = function() {
		if (_self.properties.slideSteps > 1 && _self.properties.slideCurrentStep === _self.properties.slideSteps) {
			$('#less-options-link_' + _self.properties.formId).hide();
			$('#more-options-link_' + _self.properties.formId).show();
			$('#advanced-search-form_' + _self.properties.formId + ', #full-search-form_' + _self.properties.formId).stop(true).slideUp(150);
			$('#result_popup_' + _self.properties.formId).hide();
			_self.properties.slideCurrentStep = 1;
		} else if (_self.properties.slideSteps > 1) {
			if (_self.properties.slideCurrentStep === 1) {
				$('#full-search-form_' + _self.properties.formId).stop(true).slideDown(150);
			} else if (_self.properties.slideCurrentStep === 2) {
				$('#advanced-search-form_' + _self.properties.formId).stop(true).slideDown(150);
			}
			_self.properties.slideCurrentStep++;
			if (_self.properties.slideCurrentStep === _self.properties.slideSteps) {
				$('#more-options-link_' + _self.properties.formId).hide();
				$('#less-options-link_' + _self.properties.formId).show();
			}
		}
	};

	this.load_results = function() {
		var url;
		if (_self.properties.currentForm === 'user') {
			url = _self.properties.siteUrl + _self.properties.userSearchUrl;
		} else if (_self.properties.currentForm === 'perfect_match') {
			url = _self.properties.siteUrl + _self.properties.pmSearchUrl;
		}

		$.ajax({
			url: url,
			type: 'POST',
			data: $('#main_search_form_' + _self.properties.formId).serialize(),
			cache: false,
			success: function(data) {
				var blockID = _self.properties.resultsBlockId[_self.properties.currentForm];
				$('#' + blockID).html(data);
				if (typeof users_list === 'object' && typeof users_list.init_links === 'function') {
					users_list.init_links();
				}
			}
		});
	};

	this.preload_results = function(top, left, right, bottom) {
		if (_self.properties.hide_popup) {
			return;
		}
		var date = new Date();

		/*if(!_self.properties.preloader){
		 if((typeof PreloaderAnimation !== 'undefined')) {
		 _self.properties.preloader = new PreloaderAnimation({
		 selector: '#result_popup_'+_self.properties.formId,
		 iconSize: 1,
		 centered: false
		 });
		 } else {
		 _self.properties.preloader = false;
		 }
		 }*/

		if (_self.properties.preloadUTS !== 0) {
			if (date.getTime() - _self.properties.preloadUTS < _self.properties.preloadInterval) {
				if (_self.properties.preloadTID === false) {
					_self.properties.preloadTID = setTimeout(function() {
						_self.preload_results(top, left, right);
					}, _self.properties.preloadInterval);
				}
				return;
			}
		}

		_self.properties.preloadTID = false;
		_self.properties.preloadUTS = date.getTime();
		var url;
		if (_self.properties.currentForm === 'user') {
			url = _self.properties.siteUrl + _self.properties.userCountUrl;
		} else if (_self.properties.currentForm === 'perfect_match') {
			url = _self.properties.siteUrl + _self.properties.pmCountUrl;
		}

		$.ajax({
			url: url,
			type: 'POST',
			data: $('#main_search_form_' + _self.properties.formId).serialize(),
			cache: false,
			dataType: 'json',
			success: function(data) {
				if (_self.properties.resultPopupObjTO) {
					clearTimeout(_self.properties.resultPopupObjTO);
				}
				var preresult_html = '<a href="javascript:;" onclick="$(\'#main_search_button_' + _self.properties.formId + '\').click(); $(this).parent().hide();">' + data.string + '</a>';
				var top_pos = top - $('#result_popup_' + _self.properties.formId).outerHeight() - 6;
				var bottom_pos = top + $('#result_popup_' + _self.properties.formId).outerHeight();
				var popup_top = top_pos;
				if (_self.properties.popup_autoposition) {
					if (top_pos < 0) {
						$('#result_popup_' + _self.properties.formId).addClass('on-bottom');
						popup_top = bottom_pos;
					} else {
						$('#result_popup_' + _self.properties.formId).removeClass('on-bottom');
					}
				}
				$('#result_popup_' + _self.properties.formId).hide().html(preresult_html).css({top: popup_top + 'px'}).show();
				if (window.site_rtl_settings === 'rtl') {
					var cont_width = $('#result_popup_' + _self.properties.formId).parent().width();
					$('#result_popup_' + _self.properties.formId).css('right', cont_width - right + 'px');
				} else {
					$('#result_popup_' + _self.properties.formId).css('left', left + 'px');
				}
				_self.properties.resultPopupObjTO = setTimeout(function() {
					$('#result_popup_' + _self.properties.formId).hide();
				}, _self.properties.resultPopupTO);
				$('#search-preresult_' + _self.properties.formId).html(data.string);
			},
			complete: function() {
				/*_self.properties.preloader.uninit();
				 _self.properties.preloader = null;*/
			}
		});
	};

	_self.Init(optionArr);
}