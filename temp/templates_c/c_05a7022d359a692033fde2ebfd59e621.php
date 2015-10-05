<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 04:03:57 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_notifications_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/subscriptions/edit"><?php echo l('link_add_subscriptions', 'subscriptions', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_subscriptions_name', 'subscriptions', '', 'text', array()); ?></th>
        <th class="w150"><a href="<?php echo $this->_vars['sort_links']['subscribe_type']; ?>
"<?php if ($this->_vars['order'] == 'subscribe_type'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_subscribe_type', 'subscriptions', '', 'text', array()); ?></a></th>
	<th><?php echo l('field_sheduler', 'subscriptions', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['subscriptions']) and count((array)$this->_vars['subscriptions'])): foreach ((array)$this->_vars['subscriptions'] as $this->_vars['item']): ?>
<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><?php echo l($this->_vars['item']['name_i'], 'subscriptions', '', 'text', array()); ?></td>
	<td class="center"><?php echo l($this->_vars['item']['subscribe_type'], 'subscriptions', '', 'text', array()); ?></td>
	<td class="center"><?php echo $this->_vars['item']['scheduler_format']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/subscriptions/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_subscriptions', 'subscriptions', '', 'text', array()); ?>" title="<?php echo l('link_edit_subscriptions', 'subscriptions', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/subscriptions/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_subscriptions', 'subscriptions', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_subscriptions', 'subscriptions', '', 'text', array()); ?>" title="<?php echo l('link_delete_subscriptions', 'subscriptions', '', 'text', array()); ?>"></a>
                <a id="link_start_subscribe" href="<?php echo $this->_vars['site_url']; ?>
admin/subscriptions/ajax_start_subscribe/<?php echo $this->_vars['item']['id']; ?>
/" onclick="javascript: open_start_subscribe(this.href); return false;" ><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-play.png" width="16" height="16" border="0" alt="<?php echo l('start_subscribe', 'subscriptions', '', 'text', array()); ?>" title="<?php echo l('start_subscribe', 'subscriptions', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_subscriptions', 'subscriptions', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript">
 <?php echo '
$(function(){
	loading_funds = new loadingContent({
		linkerObjID: \'link_start_subscribe\',
		loadBlockWidth: \'350px\',
		loadBlockLeftType: \'center\',
		loadBlockTopType: \'bottom\',
		closeBtnClass: \'w\'
	});
});

function open_start_subscribe(url){
	$.ajax({
		url: url, 
		type: \'GET\',
		cache: false,
		success: function(data){
			loading_funds.show_load_block(data);
		}
	});
}

 '; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
