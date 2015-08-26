<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function create_editor($id, $content, $width=800, $height=400, $toolbar='Default'){
	$editor_params = get_editor_params();
	$toolbar_content = !empty($editor_params['toolbars'][$toolbar]) ? ", toolbar: ".$editor_params['toolbars'][$toolbar] : '';
	$code = "<script src='{$editor_params['script_src']}'></script>";
	$code .= '<textarea id="'.$id.'" name="'.$id.'" style="width: '.$width.'px; height: '.$height.'px;">'.$content.'</textarea>';
	$code .= "<script>CKEDITOR.replace('".$id."', {language: '".$editor_params['language']."'".$toolbar_content."});</script>";
	return $code;
}

function get_editor_params(){
	$CI =& get_instance();
	$lang = $CI->pg_language->get_lang_by_id($CI->pg_language->current_lang_id);
	$result['toolbars'] = array(
		'Default' => '',
		'Middle' => "
			[
				{ name: 'document', items: [ 'Source' ] },
				{ name: 'actions', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
				{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
				{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'SpecialChar' ] },
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
				{ name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
				{ name: 'colors', items: [ 'TextColor', 'BGColor' ] }
			]
		",
		'Basic' => "
			[
				{ name: 'document', items: [ 'Source' ] },
				{ name: 'actions', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
				{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
				{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'SpecialChar' ] }
			]
		",

	);
	$result['script_src'] = '/'.SITE_SUBFOLDER.'system/plugins/ckeditor/ckeditor.js';
	$result['jquery_adapter_script_src'] = '/'.SITE_SUBFOLDER.'system/plugins/ckeditor/adapters/jquery.js';
	$result['language'] = strtolower($lang['code']);
	return $result;
}