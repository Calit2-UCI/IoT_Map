<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:13:25 Pacific Daylight Time */ ?>

<?php echo tpl_function_js(array('file' => 'admin_lang_inline_editor.js','module' => 'start'), $this);?>
<script type="text/javascript"><?php echo '
var inlineEditor;
$(function(){
	inlineEditor = new langInlineEditor({
		siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
		multiple: \'';  echo $this->_vars['multiple'];  echo '\',
		';  if ($this->_vars['textarea']): ?>textarea: true,<?php endif;  echo '
	});
});
'; ?>
</script>
