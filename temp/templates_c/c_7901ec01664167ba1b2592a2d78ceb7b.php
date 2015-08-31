<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:38 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="highlight p10 mb20 fltl" id="shoutbox_block">
	<h1><?php echo l('block_header', 'shoutbox', '', 'text', array()); ?> <span id="shoutbox_counter_nm"></span></h1>
	<div id="shoutbox" class="shoutbox">
		<div class="shoutbox-content box-sizing" style="height:<?php echo $this->_vars['shoutbox_data']['height_block_messages']; ?>
px">
			<div class="shoutbox-scroller box-sizing shoutbox-scroll"></div>
			<i class="hide icon-chevron-up w edge-shout shoutbox-in-top" style="top:-<?php echo $this->_vars['shoutbox_data']['top_top_icon']; ?>
px;"></i>
		</div>
		<div class="shoutbox-bottom box-sizing">
			<div class="ptb10"><textarea class="box-sizing wp100"></textarea></div>
			<div id="shoutbox_msg_btns" class="table-div vmiddle wp100">
				<div>
				<input id="shoutbox_btn_send" type="button" name="sendbtn" value="<?php echo l('btn_send', 'shoutbox', '', 'text', array()); ?>" />
				<span class="fright" id="shoutbox_msg_count"><?php echo $this->_vars['shoutbox_data']['msg_max_length']; ?>
</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<?php echo tpl_function_js(array('file' => 'jquery-slimscroll.js'), $this);?>
<script><?php echo '
	loadScripts(
		"';  echo tpl_function_js(array('module' => 'shoutbox','file' => 'shoutbox.js','return' => 'path'), $this); echo '",
		function(){
			var data = ';  echo $this->_vars['shoutbox_json_data'];  echo ';
			shoutbox = new Shoutbox({
				site_url: site_url,
				new_msgs: data.new_msgs,
				id_user: parseInt(data.id_user),
				max_id: parseInt(data.max_id),
				min_id: parseInt(data.min_id),
				';  if ($this->_vars['_LANG']['rtl'] == 'rtl'):  echo 'position: \'left\',';  endif;  echo '
				msg_max_length: parseInt(data.msg_max_length),
				user_name: data.user_name,
				site_status: parseInt(typeof data.user_status !== \'undefined\' ? data.user_status.site_status : 0),
			});
		},
		\'\',
		{async: false}
	);

</script>'; ?>
