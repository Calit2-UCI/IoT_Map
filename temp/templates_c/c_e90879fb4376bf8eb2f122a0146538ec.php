<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:13:28 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a id="users_link_add" href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/"><?php echo l('link_add_user', 'users', '', 'text', array()); ?></a></div></li>
		<li><div class="l" id="delete_select_block"><a id="users_link_delete" href="javascript:void(0)"><?php echo l('link_delete_user', 'users', '', 'text', array()); ?></a></div></li>
		<?php echo tpl_function_helper(array('func_name' => 'button_add_funds','module' => 'users_payments'), $this);?>
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
			<div class="h"><?php echo l('user_type', 'users', '', 'text', array()); ?>:</div>
			<div class="v">
				<select name="user_type"  class="middle_long">
					<option value="all"<?php if ($this->_vars['user_type'] == 'all'): ?> selected<?php endif; ?>>...</option>
					<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
						<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['user_type'] == $this->_vars['key']): ?>} selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
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
			<div class="h"><?php echo l('latest_active', 'users', '', 'text', array()); ?>:</div>
			<div class="v middle_long">
				<input type="text" id="last_active_from" name="last_active_from" maxlength="10" class="short_long" value="<?php echo $this->_vars['search_param']['last_active']['from']; ?>
">
				<label for="last_active_to"><?php echo l('to', 'users', '', 'text', array()); ?></label>
				<input type="text" id="last_active_to" name="last_active_to" maxlength="10" class="short_long fr" value="<?php echo $this->_vars['search_param']['last_active']['to']; ?>
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
	<th class="first"><input type="checkbox" id="grouping_all"></th>
	<th><a href="<?php echo $this->_vars['sort_links']['nickname']; ?>
"<?php if ($this->_vars['order'] == 'nickname'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_nickname', 'users', '', 'text', array()); ?></a></th>
	<th><?php echo l('user_type', 'users', '', 'text', array()); ?></th>
	<th><a href="<?php echo $this->_vars['sort_links']['email']; ?>
"<?php if ($this->_vars['order'] == 'email'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_email', 'users', '', 'text', array()); ?></a></th>
	<th><a href="<?php echo $this->_vars['sort_links']['account']; ?>
"<?php if ($this->_vars['order'] == 'account'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_account', 'users', '', 'text', array()); ?></a></th>
	<th class=""><a href="<?php echo $this->_vars['sort_links']['date_created']; ?>
"<?php if ($this->_vars['order'] == 'date_created'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_created', 'users', '', 'text', array()); ?></a></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['users']) and count((array)$this->_vars['users'])): foreach ((array)$this->_vars['users'] as $this->_vars['item']): ?>
<?php echo tpl_function_counter(array('print' => false,'assign' => 'counter'), $this);?>
<tr class="<?php if (!($this->_vars['counter'] % 2)): ?>zebra <?php endif;  if (! empty ( $this->_vars['item']['net_is_incomer'] )): ?>net_incomer<?php endif; ?>">
	<td class="first w20 center">
		<?php if (! empty ( $this->_vars['item']['net_is_incomer'] )): ?>
			<div class="corner-triangle" title="<?php echo l('network_is_incomer', 'users', '', 'text', array()); ?>"></div>
		<?php endif; ?>
		<input type="checkbox" class="grouping" value="<?php echo $this->_vars['item']['id']; ?>
">
	</td>
	<td>
		<b><?php echo $this->_vars['item']['nickname']; ?>
</b><br><?php echo $this->_vars['item']['fname']; ?>
 <?php echo $this->_vars['item']['sname']; ?>

	</td>
	<td><?php echo $this->_vars['item']['user_type_str']; ?>
</td>
	<td><?php if (! empty ( $this->_vars['item']['net_is_incomer'] )):  echo l('network_email', 'users', '', 'text', array());  else:  echo $this->_vars['item']['email'];  endif; ?></td>
	<td class="center"><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['account']), $this);?></td>
	<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_created'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td class="icons">
		<div id="move_block">
			<?php if ($this->_vars['item']['approved']): ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/users/activate/<?php echo $this->_vars['item']['id']; ?>
/0"><i class="fa fa-circle"></i></a>
			<?php else: ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/users/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><i class="fa fa-circle inactive"></i></a>
			<?php endif; ?>
			<?php if (empty ( $this->_vars['item']['net_is_incomer'] )): ?>
				<a title="<?php echo l('link_edit_user', 'users', '', 'text', array()); ?>" href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/<?php echo $this->_vars['item']['id']; ?>
"><i class="fa fa-pencil"></i></a>
			<?php else: ?>
				<i title="<?php echo l('network_is_incomer', 'users', '', 'text', array()); ?>.<?php echo l('network_error_edit_user', 'users', '', 'text', array()); ?>" class="inactive fa fa-pencil"></i>
			<?php endif; ?>
			<?php echo tpl_function_block(array('name' => 'delete_select_block','module' => 'users','id_user' => $this->_vars['item']['id'],'deleted' => 0), $this);?>
			<?php echo tpl_function_block(array('name' => 'contact_user_link','module' => 'tickets','id_user' => $this->_vars['item']['id']), $this);?>
		</div>
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
 ?>
<?php echo tpl_function_js(array('file' => 'jquery-ui.custom.min.js'), $this);?>
<link href='<?php echo $this->_vars['site_root'];  echo $this->_vars['js_folder']; ?>
jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
<script type="text/javascript">

var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/users/index/";
var filter = '<?php echo $this->_vars['filter']; ?>
';
var order = '<?php echo $this->_vars['order']; ?>
';
var loading_content;
var order_direction = '<?php echo $this->_vars['order_direction']; ?>
';

<?php echo '

$(function(){
	$(\'#grouping_all\').bind(\'click\', function(){
		var checked = $(this).is(\':checked\');
		if(checked){
			$(\'input.grouping\').prop(\'checked\', true);
		}else{
			$(\'input.grouping\').prop(\'checked\', false);
		}
	});

	$(\'#grouping_all\').bind(\'click\', function(){
		var checked = $(this).is(\':checked\');
		if(checked){
			$(\'input[type=checkbox].grouping\').prop(\'checked\', true);
		}else{
			$(\'input[type=checkbox].grouping\').prop(\'checked\', false);
		}
	});
	now = new Date();
	yr =  (new Date(now.getYear() - 80, 0, 1).getFullYear()) + \':\' + (new Date(now.getYear() - 18, 0, 1).getFullYear());
	$( "#last_active_from" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :\'yy-mm-dd\',
		onClose: function( selectedDate ) {
			$( "#last_active_to" ).datepicker( "option", "minDate", selectedDate );
		}
    });
    $( "#last_active_to" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :\'yy-mm-dd\',
		onClose: function( selectedDate ) {
			$( "#last_active_from" ).datepicker( "option", "maxDate", selectedDate );
		}
    });
		
});
delete_select_block = new loadingContent({
	loadBlockWidth: \'620px\',
	loadBlockLeftType: \'center\',
	loadBlockTopType: \'center\',
	loadBlockTopPoint: 100,
	closeBtnClass: \'w\'
}).update_css_styles({\'z-index\': 2000}).update_css_styles({\'z-index\': 2000}, \'bg\');
$(\'#delete_select_block\').unbind(\'click\').click(function(){
	var data = new Array();
	$(\'.grouping:checked\').each(function(i){
		data[i] = $(this).val();
	});
	if(data.length > 0){
		$.ajax({
			url: site_url + \'admin/users/ajax_delete_select/\',
			data: {user_ids: data},
			type: "POST",
			cache: false,
			success: function(data){
				delete_select_block.show_load_block(data);
			}
		});
	}else{
		error_object.show_error_block(\'';  echo l("error_no_users_to_change_group", "users", '', "js", array());  echo '\', \'error\');
	}
});
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
