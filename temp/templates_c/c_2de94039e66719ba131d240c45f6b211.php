<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-22 03:01:53 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['site_url']; ?>
/admin/mail_list/users" id="frm_apply_filter">
	<input type="hidden" id="id_filter" name="id_filter" />
</form>
<div id="mail_list">
	<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_mail_list_menu'), $this);?>
	<table id="tbl_filters" cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w100"><?php echo l('field_filter_date', 'mail_list', '', 'text', array()); ?></th>
		<th><?php echo l('field_filter_criteria', 'mail_list', '', 'text', array()); ?></th>
		<th class="w50">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['filters']) and count((array)$this->_vars['filters'])): foreach ((array)$this->_vars['filters'] as $this->_vars['filter']): ?>
	<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
	<tr id="filter_<?php echo $this->_vars['filter']['id']; ?>
"<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
		<td class="first center"><?php echo $this->_vars['filter']['date_search']; ?>
</td>
		<td>
			<dl>
				<?php if ($this->_vars['filter']['search_data']['id_subscription']): ?>
					<dt><?php echo l('field_subscription', 'mail_list', '', 'text', array()); ?>:</dt>
					<dd><?php echo $this->_vars['filter']['search_data']['subscription']; ?>
</dd>
				<?php endif; ?>
				<?php if ($this->_vars['filter']['search_data']['email']): ?>
					<dt><?php echo l('field_email', 'mail_list', '', 'text', array()); ?>:</dt>
					<dd><?php echo $this->_vars['filter']['search_data']['email']; ?>
</dd>
				<?php endif; ?>
				<?php if ($this->_vars['filter']['search_data']['name']): ?>
					<dt><?php echo l('field_nickname', 'mail_list', '', 'text', array()); ?>:</dt>
					<dd><?php echo $this->_vars['filter']['search_data']['name']; ?>
</dd>
				<?php endif; ?>
				<?php if ($this->_vars['filter']['search_data']['user_type']): ?>
					<?php $this->assign('user_type', $this->_vars['filter']['search_data']['user_type']); ?>
					<dt><?php echo l('field_user_type', 'mail_list', '', 'text', array()); ?>:</dt>
					<dd><?php echo $this->_vars['user_types']['option'][$this->_vars['user_type']]; ?>
</dd>
				<?php endif; ?>
				<?php if ($this->_vars['filter']['search_data']['date']): ?>
					<dt><?php echo l('field_date', 'mail_list', '', 'text', array()); ?>:</dt>
					<dd><?php echo $this->_vars['filter']['search_data']['date']; ?>
</dd>
				<?php endif; ?>
				<?php if ($this->_vars['filter']['location']): ?>
					<dt><?php echo l('field_location', 'mail_list', '', 'text', array()); ?>:</dt>
					<dd><?php echo $this->_vars['filter']['location']; ?>
</dd>
				<?php endif; ?>
			</dl>
		</td>
		<td class="icons">
			<a class="link_delete" id="delete_<?php echo $this->_vars['filter']['id']; ?>
" href="javascript:void(0);">
				<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" 
					 alt="<?php echo l('link_delete', 'mail_list', '', 'text', array()); ?>" title="<?php echo l('link_delete', 'mail_list', '', 'text', array()); ?>" />
			</a>
			<a class="link_search" id="apply_<?php echo $this->_vars['filter']['id']; ?>
"href="javascript:void(0);">
				<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-play.png" width="16" height="16" border="0" 
					 alt="<?php echo l('link_search', 'mail_list', '', 'text', array()); ?>" title="<?php echo l('link_search', 'mail_list', '', 'text', array()); ?>" />
			</a>
		</td>
	</tr>
	<?php endforeach; else: ?>
	<tr><td colspan="4" class="center"><?php echo l('no_searches', 'mail_list', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
	</table>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<script type="text/javascript"><?php echo '
	var mail_list;
	$(function(){
		mail_list = new adminMailList({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
			imgsUrl: \'';  echo $this->_vars['site_url'];  echo $this->_vars['img_folder'];  echo '\'
		});
		mail_list.bind_filters_events();
	});
	'; ?>
</script>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
