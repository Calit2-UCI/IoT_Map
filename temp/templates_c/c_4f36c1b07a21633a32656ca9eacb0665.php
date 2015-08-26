<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.selectbox.php');
$this->register_function("selectbox", "tpl_function_selectbox"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['dynamic_block_lang_select_data']['count_active'] > 1): ?>
	<div id="lang_select_wrapper_<?php echo $this->_vars['dynamic_block_lang_select_data']['rand']; ?>
"<?php if ($this->_vars['dynamic_block_lang_select_data']['params']['right_align']): ?> class="righted"<?php endif; ?>>
		
		<div class="ib vmiddle">
			<?php echo tpl_function_selectbox(array('input' => 'language','id' => 'lang_select_'.$this->_vars['dynamic_block_lang_select_data']['rand'],'value' => $this->_vars['dynamic_block_lang_select_data']['languages'],'selected' => $this->_vars['dynamic_block_lang_select_data']['lang_id'],'class' => 'cute'), $this);?>
		</div>
	</div>
				
	<script type="text/javascript"><?php echo '
		$(function(){
			loadScripts(
				"';  echo tpl_function_js(array('module' => 'start','file' => 'selectbox.js','return' => 'path'), $this); echo '",
				function(){
					var data = ';  echo tpl_function_json_encode(array('data' => $this->_vars['dynamic_block_lang_select_data']), $this); echo ';
					lang_select';  echo $this->_vars['dynamic_block_lang_select_data']['rand'];  echo ' = new selectBox({
						elementsIDs: [\'lang_select_\'+data.rand],
						force: true,
						dropdownClass: \'dropdown cute\',
						dropdownAutosize: true,
						dropdownRight: data.params.right_align ? true : false
					});
					$(\'#lang_select_wrapper_\'+data.rand).off(\'change\', \'input[name="language"]\').on(\'change\', \'input[name="language"]\', function(){
						location.href = site_url+\'users/change_language/\'+$(this).val();
					});
				},
				\'lang_select';  echo $this->_vars['dynamic_block_lang_select_data']['rand'];  echo '\',
				{async: false}
			);
		});
	</script>'; ?>

<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>