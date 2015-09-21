<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:09:43 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/"><?php echo l('link_add_user', 'users', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['filter'] == 'all'): ?>active<?php endif;  if (! $this->_vars['filter_data']['all']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/all/<?php echo $this->_vars['user_type']; ?>
"><?php echo l('filter_all_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['all']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'not_active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['not_active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_active/<?php echo $this->_vars['user_type']; ?>
"><?php echo l('filter_not_active_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['not_active']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/active/<?php echo $this->_vars['user_type']; ?>
"><?php echo l('filter_active_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['active']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'not_confirm'): ?>active<?php endif;  if (! $this->_vars['filter_data']['not_confirm']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_confirm/<?php echo $this->_vars['user_type']; ?>
"><?php echo l('filter_not_confirm_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['not_confirm']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'deleted'): ?>active<?php endif;  if (! $this->_vars['filter_data']['deleted']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/deleted"><?php echo l('filter_deleted_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['deleted']; ?>
)</a></li>
                <?php echo tpl_function_helper(array('func_name' => 'not_registered_add_filter','module' => 'incomplete_signup','func_param' => $this->_vars['filter']), $this);?>
        </ul>
	&nbsp;
</div>
<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="filter" value="<?php echo $this->_vars['filter']; ?>
">
	<input type="hidden" name="order" value="<?php echo $this->_vars['order']; ?>
">
	<input type="hidden" name="order_direction" value="<?php echo $this->_vars['order_direction']; ?>
">
	<div class="filter-form">
		<div class="row">
			<div class="h"><?php echo l('search_by', 'users', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" name="val_text" value="<?php echo $this->_vars['search_param']['text']; ?>
" class="short_long">
				<select name="type_text" class="ml20 short_long">
					<option value="all" <?php if ($this->_vars['search_param']['type'] == 'all'): ?> selected<?php endif; ?>><?php echo l('filter_all', 'users', '', 'text', array()); ?></option>
					<option value="email" <?php if ($this->_vars['search_param']['type'] == 'email'): ?> selected<?php endif; ?>><?php echo l('field_email', 'users', '', 'text', array()); ?></option>
					<option value="fname" <?php if ($this->_vars['search_param']['type'] == 'fname'): ?> selected<?php endif; ?>><?php echo l('field_fname', 'users', '', 'text', array()); ?></option>
					<option value="sname" <?php if ($this->_vars['search_param']['type'] == 'sname'): ?> selected<?php endif; ?>><?php echo l('field_sname', 'users', '', 'text', array()); ?></option>
					<option value="nickname" <?php if ($this->_vars['search_param']['type'] == 'nickname'): ?> selected<?php endif; ?>><?php echo l('field_nickname', 'users', '', 'text', array()); ?></option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_deleted_from', 'users', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" id="date_deleted_from" name="date_deleted_from" maxlength="10" class="short_long" value="<?php echo $this->_vars['search_param']['date_deleted']['from']; ?>
">
				<label for="date_deleted_to"><?php echo l('to', 'users', '', 'text', array()); ?></label>
				<input type="text" id="date_deleted_to" name="date_deleted_to" maxlength="10" class="short_long" value="<?php echo $this->_vars['search_param']['date_deleted']['to']; ?>
">
			</div>
		</div>
		<div class="row">
			<div class="btn">
				<div class="l">
					<input type="submit" value="<?php echo l('header_user_find', 'users', '', 'text', array()); ?>" name="btn_search">
				</div>
			</div>
		</div>		
	</div>
</form>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="<?php echo $this->_vars['sort_links']['nickname']; ?>
"<?php if ($this->_vars['order'] == 'nickname'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_nickname', 'users', '', 'text', array()); ?></a></th>
	<th><a href="<?php echo $this->_vars['sort_links']['email']; ?>
"<?php if ($this->_vars['order'] == 'email'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_email', 'users', '', 'text', array()); ?></a></th>
	<th class=""><a href="<?php echo $this->_vars['sort_links']['date_deleted']; ?>
"<?php if ($this->_vars['order'] == 'date_deleted'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_deleted_date', 'users', '', 'text', array()); ?></a></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['users']) and count((array)$this->_vars['users'])): foreach ((array)$this->_vars['users'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><b><?php echo $this->_vars['item']['nickname']; ?>
</b><br><?php echo $this->_vars['item']['fname']; ?>
 <?php echo $this->_vars['item']['sname']; ?>
</td>
	<td><?php echo $this->_vars['item']['email']; ?>
</td>
	<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_deleted'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td class="icons">
		<?php echo tpl_function_block(array('name' => 'delete_select_block','module' => 'users','id_user' => $this->_vars['item']['id_user'],'callback_user' => $this->_vars['item']['data'],'deleted' => 1), $this);?>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7" class="center"><?php echo l('no_users', 'users', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'jquery-ui.custom.min.js'), $this);?>
<link href='<?php echo $this->_vars['site_root'];  echo $this->_vars['js_folder']; ?>
jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
<script type="text/javascript">

var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/users/deleted/";
var filter = '<?php echo $this->_vars['filter']; ?>
';
var order = '<?php echo $this->_vars['order']; ?>
';
var loading_content;
var order_direction = '<?php echo $this->_vars['order_direction']; ?>
';

<?php echo '

$(function(){
	now = new Date();
	yr =  (new Date(now.getYear() - 80, 0, 1).getFullYear()) + \':\' + (new Date(now.getYear() - 18, 0, 1).getFullYear());
	$( "#date_deleted_from" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :\'yy-mm-dd\',
		onClose: function( selectedDate ) {
			$( "#date_deleted_to" ).datepicker( "option", "minDate", selectedDate );
		}
    });
    $( "#date_deleted_to" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :\'yy-mm-dd\',
		onClose: function( selectedDate ) {
			$( "#date_deleted_from" ).datepicker( "option", "maxDate", selectedDate );
		}
    });
});
function reload_this_page(value){
	var link = reload_link + filter + \'/\' + value + \'/\' + order + \'/\' + order_direction;
	location.href=link;
}
delete_select_block = new loadingContent({
	loadBlockWidth: \'620px\',
	loadBlockLeftType: \'center\',
	loadBlockTopType: \'center\',
	loadBlockTopPoint: 100,
	closeBtnClass: \'w\'
}).update_css_styles({\'z-index\': 2000}).update_css_styles({\'z-index\': 2000}, \'bg\');
function reload_this_page(value){
	var link = reload_link + filter + \'/\' + value + \'/\' + order + \'/\' + order_direction;
	location.href=link;
}
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
