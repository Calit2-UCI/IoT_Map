<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 03:11:22 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_news_menu'), $this);?>
<div class="actions">&nbsp;</div>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_news_settings', 'news', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_userside_items', 'news', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['userside_items_per_page']; ?>
" name="userside_items_per_page" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_userhelper_items', 'news', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['userhelper_items_per_page']; ?>
" name="userhelper_items_per_page" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_news_max_count', 'news', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['rss_news_max_count']; ?>
" name="rss_news_max_count" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_use_feeds_news', 'news', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" <?php if ($this->_vars['data']['rss_use_feeds_news']): ?>checked<?php endif; ?> name="rss_use_feeds_news" ></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_channel_title', 'news', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['rss_feed_channel_title']; ?>
" name="rss_feed_channel_title" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_channel_description', 'news', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['rss_feed_channel_description']; ?>
" name="rss_feed_channel_description" class="middle" ></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_image_title', 'news', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['rss_feed_image_title']; ?>
" name="rss_feed_image_title" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_settings_rss_image_url', 'news', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="file" name="rss_logo">
				<?php if ($this->_vars['data']['rss_feed_image_url']): ?>
				<br><img src="<?php echo $this->_vars['data']['rss_feed_image_media']['thumbs']['rss']; ?>
"  hspace="2" vspace="2" />
				<br><input type="checkbox" name="rss_logo_delete" value="1" id="uichb"><label for="uichb"><?php echo l('field_icon_delete', 'news', '', 'text', array()); ?></label>
				<?php endif; ?>
			</div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
</form>
<div class="clr"></div>
<script><?php echo '
$(function(){
	$("div.row:odd").addClass("zebra");
});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>