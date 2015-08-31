<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 23:57:19 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div id="mail_list">
	<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_mail_list_menu'), $this);?>
	<div class="actions">
		<ul>
			<?php if ($this->_vars['data']['filter'] != 'subscribed'): ?>
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="subscribe_all">
						<?php echo l('link_subscribe_all', 'mail_list', '', 'text', array()); ?>
					</a>
				</div></li>
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="subscribe_selected">
						<?php echo l('link_subscribe_selected', 'mail_list', '', 'text', array()); ?>
					</a>
				</div></li>
			<?php endif; ?>
			<?php if ($this->_vars['data']['filter'] != 'not_subscribed'): ?>
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="unsubscribe_all">
						<?php echo l('link_unsubscribe_all', 'mail_list', '', 'text', array()); ?>
					</a>
				</div></li>
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="unsubscribe_selected">
						<?php echo l('link_unsubscribe_selected', 'mail_list', '', 'text', array()); ?>
					</a>
				</div></li>
			<?php endif; ?>
		</ul>&nbsp;
	</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "mail_list". $this->module_templates.  $this->get_current_theme_gid('"admin"', '"mail_list"'). "users_form.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<div class="menu-level3">
		<ul>
			<li class="<?php if ($this->_vars['data']['filter'] == 'all'): ?>active<?php endif;  if (! $this->_vars['users_count']['all']): ?> hide<?php endif; ?>">
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/mail_list/users/all">
					<?php echo l('filter_all', 'mail_list', '', 'text', array()); ?> (<span id="count_all"><?php echo $this->_vars['users_count']['all']; ?>
</span>)
				</a>
			</li>
			<li class="<?php if ($this->_vars['data']['filter'] == 'not_subscribed'): ?>active<?php endif;  if (! $this->_vars['users_count']['not_subscribed']): ?> hide<?php endif; ?>">
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/mail_list/users/not_subscribed">
					<?php echo l('filter_not_subscribed', 'mail_list', '', 'text', array()); ?> (<span id="count_not_subscribed"><?php echo $this->_vars['users_count']['not_subscribed']; ?>
</span>)
				</a>
			</li>
			<li class="<?php if ($this->_vars['data']['filter'] == 'subscribed'): ?>active<?php endif;  if (! $this->_vars['users_count']['subscribed']): ?> hide<?php endif; ?>">
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/mail_list/users/subscribed">
					<?php echo l('filter_subscribed', 'mail_list', '', 'text', array()); ?> (<span id="count_subscribed"><?php echo $this->_vars['users_count']['subscribed']; ?>
</span>)
				</a>
			</li>
		</ul>
		&nbsp;
	</div>
	<table cellspacing="0" cellpadding="0" id="tbl_users" class="data" width="100%">
	<tr>
		<th class="first"><input type="checkbox" id="grouping_all"></th>
		<th><?php echo l('field_nickname', 'mail_list', '', 'text', array()); ?></th>
		<th><?php echo l('field_email', 'mail_list', '', 'text', array()); ?></th>
		<th class="w150"><?php echo l('field_registration_date', 'mail_list', '', 'text', array()); ?></th>
		<th><?php echo l('field_user_type', 'mail_list', '', 'text', array()); ?></th>
		<th class="w150"><?php echo l('field_location', 'mail_list', '', 'text', array()); ?></th>
		<th class="w50">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['users']) and count((array)$this->_vars['users'])): foreach ((array)$this->_vars['users'] as $this->_vars['user']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?> id="user<?php echo $this->_vars['user']['id']; ?>
">
			<td class="first w20 center"><input id="cb_user_<?php echo $this->_vars['user']['id']; ?>
" type="checkbox" class="grouping" value="<?php echo $this->_vars['user']['id']; ?>
"></td>
			<td><label for="cb_user_<?php echo $this->_vars['user']['id']; ?>
"><b><?php echo $this->_vars['user']['nickname']; ?>
</b><br><?php echo $this->_vars['user']['fname']; ?>
 <?php echo $this->_vars['user']['sname']; ?>
</label></td>
			<td><?php echo $this->_vars['user']['email']; ?>
</td>
			<td class="center"><?php echo $this->_run_modifier($this->_vars['user']['date_created'], 'date_format', 'plugin', 1, $this->_vars['date_format']); ?>
</td>
			<td class="center"><?php echo $this->_vars['user']['user_type_str']; ?>
</td>
			<td class="center"><?php echo $this->_vars['user']['location']; ?>
</td>
			<td class="icons">
				<?php if ($this->_vars['user']['id_subscription'] == $this->_vars['data']['id_subscription']): ?>
					<a class="unsubscribe_one mark" href="javascript:void(0);" title="<?php echo l('link_unsubscribe_user', 'mail_list', '', 'text', array()); ?>">
						<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-mark.png" width="16" height="16" border="0" alt="<?php echo l('link_unsubscribe_user', 'mail_list', '', 'text', array()); ?>">
					</a>
				<?php else: ?>
					<a class="subscribe_one" href="javascript:void(0);" title="<?php echo l('link_subscribe_user', 'mail_list', '', 'text', array()); ?>">
						<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-unmark.png" width="16" height="16" border="0" alt="<?php echo l('link_subscribe_user', 'mail_list', '', 'text', array()); ?>">
					</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="7" class="center"><?php echo l('no_users', 'mail_list', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
	</table>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<div class="btn">
		<div class="l">
			<a id="btn_save" name="btn_save" href="javascript:void(0);"><?php echo l('btn_save_filter', 'mail_list', '', 'button', array()); ?></a>
		</div>
	</div>
		<div class="clr"></div>
	<script type="text/javascript">
	<?php echo '
	var mail_list;
	$(function(){
		mail_list = new adminMailList({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
			imgsUrl: \'';  echo $this->_vars['site_url'];  echo $this->_vars['img_folder'];  echo '\'
		});
		mail_list.bind_users_events();
	});
	'; ?>

	</script>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
