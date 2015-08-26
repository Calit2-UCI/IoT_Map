'use strict';
function options(optionArr) {
	this.properties = {
		alertPleaseSelect: 'Please select at least one option',
		allSelected: {},
		allText: 'All',
		allValue: 'all',
		bgBlock: {},
		bgBlockClass: 'multiselect_bg',
		bgBlockId: 'multiselect_bg',
		btnApply: {},
		btnApplySelector: '.btn_apply',
		btnClose: {},
		btnCloseSelector: '.close',
		btnOpen: {},
		btnOpenSelector: '.options_open',
		btnSelectAll: {},
		btnSelectAllSelector: '.btn_select_all',
		classSelected: 'selected',
		clearTabOnChange: true,
		fields: {},
		itemsList: {},
		itemsListSelector: '.items .item',
		limitBlock: {},
		limitBlockSelector: '.limit',
		limits: {},
		multiselectBlock: {},
		optionsBlock: {},
		optionsBlockSelector: '.options',
		optionsBlockId: '',
		optionsSelectedItems: {},
		optionsSelectedItemsBlock: {},
		optionsSelectedItemsBlockSelector: '.options-selected .options-selected-items',
		rand: '',
		selectedField: '',
		selectedItems: {},
		selectedItemsBlock: {},
		selectedItemsBlockSelector: '.selected .selected-items',
		selectedLeft: 0,
		selectedNum: 0,
		siteUrl: '',
		tabsList: {},
		tabsListSelector: '.tab'
	};

	this.errors = {};

	var _self = this;

	var allSelected = function() {
		return true === _self.properties.allSelected[_self.properties.selectedField];
	};
	var itemsSelected = function() {
		return Object.keys(_self.properties.selectedItems[_self.properties.selectedField]).length || allSelected();
	};
	var cancel = function () {
		_self.closeForm(_self.properties.selectedField);
	};
	var hideLimit = function () {
		_self.properties.limitBlock.hide();
	};
	var showLimit = function () {
		_self.properties.limitBlock.show();
	};
	var initBg = function () {
		var id = _self.properties.bgBlockId + '_' + _self.properties.rand;
		var bg = $('<div>').attr({
			id: id,
			class: _self.properties.bgBlockClass
		});
		$('body').append(bg);
		return _self;
	};
	var li = function (value) {
		var li = $('<li>').attr({
			class: 'remove-selected item ' + _self.properties.selectedField + '-selected-' + value,
			'data-value': value
		});
		var text;
		if (_self.properties.allValue !== value) {
			text = _self.properties.fields[_self.properties.selectedField]['option'][value];
		} else {
			text = _self.properties.allText;
		}
		li.html(text + '<i class="fa fa-times"></i>');
		return li;
	};
	var hidden = function (value) {
		return $('<input>').attr({
			type: 'hidden',
			value: value,
			name: _self.properties.selectedField + '[]'
		});
	};
	var initObjects = function () {
		_self.properties.multiselectBlock = $('#multiselect_' + _self.properties.rand);
		_self.properties.bgBlock = $('#' + _self.properties.bgBlockId + '_' + _self.properties.rand);
		_self.properties.btnOpen = _self.properties.multiselectBlock.find(_self.properties.btnOpenSelector);
		_self.properties.selectedItemsBlock = _self.properties.multiselectBlock.find(_self.properties.selectedItemsBlockSelector);
		_self.properties.optionsBlock = _self.properties.multiselectBlock.find(_self.properties.optionsBlockSelector);
		_self.properties.btnClose = _self.properties.optionsBlock.find(_self.properties.btnCloseSelector);
		_self.properties.btnApply = _self.properties.multiselectBlock.find(_self.properties.btnApplySelector);
		_self.properties.btnSelectAll = _self.properties.multiselectBlock.find(_self.properties.btnSelectAllSelector);
		_self.properties.tabsList = _self.properties.optionsBlock.find(_self.properties.tabsListSelector);
		_self.properties.itemsList = _self.properties.optionsBlock.find(_self.properties.itemsListSelector);
		_self.properties.optionsSelectedItemsBlock = _self.properties.optionsBlock.find(_self.properties.optionsSelectedItemsBlockSelector);
		_self.properties.limitBlock = _self.properties.optionsBlock.find(_self.properties.limitBlockSelector);
	};
	var bindEvents = function () {
		_self.properties.btnOpen.bind('click', function () {
			_self.openForm(_self.properties.selectedField);
		});
		_self.properties.bgBlock.bind('click', function () {
			cancel();
		});
		_self.properties.btnClose.bind('click', function() {
			cancel();
		});
		_self.properties.tabsList.bind('click', function () {
			changeTab($(this).data('tab'));
		});
		_self.properties.btnApply.bind('click', function () {
			if(apply()) {
				_self.closeForm();
			} else {
				return false;
			}
		});
		_self.properties.btnSelectAll.bind('click', function () {
			if(allSelected()) {
				deselectOfAll(_self.properties.allValue);
			} else {
				selectAll();
			}
		});
		_self.properties.multiselectBlock.on('click', '.remove-selected', function () {
			deselectItem($(this).data('value'), false);
		});
		_self.properties.itemsList.bind('selectstart mousedown', function () {
			return false;
		});
		_self.properties.itemsList.bind('click', function () {
			if ($(this).hasClass(_self.properties.classSelected)) {
				deselectItem($(this).data('value'), false);
			} else {
				selectItem($(this).data('value'), false);
			}
		});
		_self.properties.selectedItemsBlock.bind('click', function() {
			
			if('nowrap' === $(this).css('white-space')) {
				$(this).css('white-space', 'inherit');
			} else {
				$(this).css('white-space', 'nowrap');
			}
		});
	};

	var changeTab = function (selectedTab) {
		var tab = _self.properties.optionsBlock.find('.tab_' + selectedTab);
		if (tab.hasClass('active')) {
			return false;
		}
		if(_self.properties.clearTabOnChange) {
			deselectAll();
		}
		_self.properties.selectedField = selectedTab;
		_self.properties.tabsList.removeClass('active');
		tab.addClass('active');
		_self.properties.optionsBlock.find('.tab-content').hide();
		_self.properties.optionsBlock.find('.options_' + selectedTab).show();
		updateLimit();
		updateHTMLForSelected(false);
		if(true === _self.properties.allSelected[_self.properties.selectedField]) {
			hideLimit();
		} else {
			showLimit();
		}
	};

	var updateHTMLForSelected = function (permanent) {
		removeSelectedHTML(permanent);
		if (true === _self.properties.allSelected[_self.properties.selectedField] || (permanent && (0 === _self.properties.selectedNum))) {
			addSelectedHTML(_self.properties.allValue, permanent);
		} else {
			for (var value in _self.properties.selectedItems[_self.properties.selectedField]) {
				addSelectedHTML(value, permanent);
			}
		}
	};

	var addSelectedHTML = function (value, permanent) {
		permanent = permanent || false;
		if (_self.properties.allValue === value) {
			li(value).appendTo(_self.properties.optionsSelectedItemsBlock);
			if (true === permanent) {
				for (var optionVal in _self.properties.fields[_self.properties.selectedField]['option']) {
					li(optionVal).appendTo(_self.properties.selectedItemsBlock);
				}
				hidden(value).prependTo(_self.properties.selectedItemsBlock);
			}
		} else {
			li(value).appendTo(_self.properties.optionsSelectedItemsBlock);
			if (true === permanent) {
				li(value).appendTo(_self.properties.selectedItemsBlock);
				hidden(value).prependTo(_self.properties.selectedItemsBlock);
			}
		}
	};

	var removeSelectedItemHTML = function (value, permanent) {
		permanent = permanent || false;
		_self.properties.optionsSelectedItemsBlock.find('.' + _self.properties.selectedField + '-selected-' + value)
				.remove();
		if (true === permanent) {
			_self.properties.selectedItemsBlock.find('.' + _self.properties.selectedField + '-selected-' + value)
					.remove();
		}
	};

	var removeSelectedHTML = function (permanent) {
		permanent = permanent || false;
		_self.properties.optionsSelectedItemsBlock.html('');
		if (true === permanent) {
			_self.properties.selectedItemsBlock.html('');
		}
	};

	var selectAll = function () {
		_self.properties.selectedItems = [];
		removeSelectedHTML(false);
		_self.properties.itemsList.addClass(_self.properties.classSelected);
		updateLimit();
		hideLimit();
		selectItem(_self.properties.allValue);
		_self.properties.allSelected[_self.properties.selectedField] = true;
	};

	var selectItem = function (itemValue, permanent) {
		if (_self.properties.selectedLeft <= 0) {
			return false;
		}
		if(_self.properties.allSelected[_self.properties.selectedField]) {
			selectOfAll();
		}
		$('.' + _self.properties.selectedField + '-option-' + itemValue)
				.addClass(_self.properties.classSelected);
		if ('undefined' === typeof _self.properties.selectedItems[_self.properties.selectedField]) {
			_self.properties.selectedItems[_self.properties.selectedField] = {};
		}
		_self.properties.selectedItems[_self.properties.selectedField][itemValue] =
				_self.properties.fields[_self.properties.selectedField]['option'][itemValue];
		updateLimit();
		addSelectedHTML(itemValue, permanent);
	};

	var selectOfAll = function() {
		removeSelectedHTML(false);
		_self.properties.allSelected[_self.properties.selectedField] = false;
		delete _self.properties.selectedItems[_self.properties.selectedField][_self.properties.allValue];
	};

	var deselectAll = function() {
		_self.properties.selectedItems = [];
		removeSelectedHTML(false);
		_self.properties.itemsList.removeClass(_self.properties.classSelected);
		updateLimit();
		//hideLimit();
		//selectItem(_self.properties.allValue);
		_self.properties.allSelected[_self.properties.selectedField] = false;
	};

	var deselectOfAll = function(itemValue) {
		_self.properties.itemsList.removeClass(_self.properties.classSelected);
		removeSelectedHTML(false);
		_self.properties.allSelected[_self.properties.selectedField] = false;
		delete _self.properties.selectedItems[_self.properties.selectedField][_self.properties.allValue];

		if(_self.properties.allValue !== itemValue) {
			selectItem(itemValue, false);
		}
		updateLimit();
		showLimit();
	};

	var deselectItem = function (itemValue, permanent) {
		if (true === _self.properties.allSelected[_self.properties.selectedField]) {
			deselectOfAll(itemValue);
			return false;
		}
		delete _self.properties.selectedItems[_self.properties.selectedField][itemValue];
		$('.' + _self.properties.selectedField + '-option-' + itemValue).removeClass(_self.properties.classSelected);
		updateLimit();
		removeSelectedItemHTML(itemValue, permanent);
	};

	var apply = function () {
		if(itemsSelected()) {
			updateHTMLForSelected(true);
			return true;
		} else {
			alert(_self.properties.alertCantSaveEmpty);
			return false;
		}
	};

	var updateLimit = function () {
		if ('object' === typeof _self.properties.selectedItems[_self.properties.selectedField]) {
			_self.properties.selectedNum = Object.keys(_self.properties.selectedItems[_self.properties.selectedField]).length;
		} else {
			_self.properties.selectedNum = 0;
		}
		var max = _self.properties.limits[_self.properties.selectedField];
		_self.properties.selectedLeft = max - _self.properties.selectedNum;
		_self.properties.limitBlock.find('.selected_num').html(_self.properties.selectedNum);
		_self.properties.limitBlock.find('.max_num').html(max);
		return _self.properties.selectedLeft;
	};

	var fillEmptyObjects = function() {
		for(var field in _self.properties.fields) {
			if('undefined' === typeof(_self.properties.allSelected[field])) {
				_self.properties.allSelected[field] = false;
			}
			if('undefined' === typeof _self.properties.selectedItems[field]) {
				_self.properties.selectedItems[field] = [];
			}
		}
	};

	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
		initBg();
		initObjects();
		bindEvents();
		updateLimit();
		fillEmptyObjects();
	};

	this.openForm = function () {
		_self.properties.bgBlock.show();
		_self.properties.optionsBlock.show();
		updateLimit();
	};

	this.closeForm = function () {
		_self.properties.multiselectBlock.find('.header').html(
				_self.properties.fields[_self.properties.selectedField]['header']);
		_self.properties.optionsBlock.hide();
		_self.properties.bgBlock.hide();
	};

	_self.Init(optionArr);
}
