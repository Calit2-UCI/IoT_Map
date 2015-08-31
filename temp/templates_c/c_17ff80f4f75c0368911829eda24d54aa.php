<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 23:57:19 Pacific Daylight Time */ ?>

<form method="post" enctype="application/x-www-form-urlencoded" id="search_form" name="search_form">
	<div class="edit-form n150">
		<div class="row header">
			<?php echo l('admin_header_subscription', 'mail_list', '', 'text', array()); ?>
		</div>
		<div class="row">
			<div class="h"><label for="id_subscription"><?php echo l('field_subscription', 'mail_list', '', 'text', array()); ?>:</label></div>
			<div class="v">
				<select id="id_subscription" name="id_subscription" class="middle">
					<?php if (is_array($this->_vars['subscriptions']) and count((array)$this->_vars['subscriptions'])): foreach ((array)$this->_vars['subscriptions'] as $this->_vars['subscription']): ?>
						<option value="<?php echo $this->_vars['subscription']['id']; ?>
" <?php if ($this->_vars['subscription']['id'] == $this->_vars['data']['id_subscription']): ?>selected="selected"<?php endif; ?>>
							<?php echo $this->_vars['subscription']['name']; ?>

						</option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<br />
		<div class="row header">
			<?php echo l('admin_header_users_data', 'mail_list', '', 'text', array()); ?>
		</div>
		<div class="row">
			<div class="h"><label for="email"><?php echo l('field_email', 'mail_list', '', 'text', array()); ?>:</label></div>
			<div class="v"><input type="text" id="email" name="email" value="<?php echo $this->_run_modifier($this->_vars['data']['email'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><label for="name"><?php echo l('field_nickname', 'mail_list', '', 'text', array()); ?>:</label></div>
			<div class="v"><input type="text" id="name" name="name" value="<?php echo $this->_run_modifier($this->_vars['data']['name'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><label for="date"><?php echo l('field_registration_date', 'mail_list', '', 'text', array()); ?>:</label></div>
			<div class="v">
				<input type='text' value='<?php echo $this->_vars['data']['date']; ?>
' id="date" name="date" class="datepicker short_long" maxlength="10">
			</div>
		</div>
		<div class="row">
			<div class="h"><label for="user_type"><?php echo l('field_user_type', 'mail_list', '', 'text', array()); ?>:</label></div>
			<div class="v">
				<select id="user_type" name="user_type" class="short_long">
						<option value="0" <?php if ($this->_vars['user_type'] == '0'): ?> selected<?php endif; ?>>...</option>
						<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
							<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['data']['user_type'] == $this->_vars['key']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
						<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="location"><?php echo l('field_location', 'mail_list', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<?php echo tpl_function_block(array('name' => 'location_select','module' => 'countries','select_type' => 'city','id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city']), $this);?>
			</div>
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn_search" value="<?php echo l('btn_search', 'start', '', 'button', array()); ?>">
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn_cancel" value="<?php echo l('btn_cancel', 'start', '', 'button', array()); ?>">
		</div>
	</div>
</form>
<div class="clr"></div>
<?php echo tpl_function_js(array('file' => 'jquery-ui.custom.min.js'), $this);?>
<link href='<?php echo $this->_vars['site_root'];  echo $this->_vars['js_folder']; ?>
jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
<script type='text/javascript'><?php echo '
	$(function(){
		var minYear = -5;
		var yr = new Date().getFullYear() + minYear + \':\' + new Date().getFullYear();
		$(\'.datepicker\').datepicker({
			dateFormat:	\'yy-mm-dd\',
			changeYear: true,
			changeMonth:true,
			yearRange:	yr
		});
		$(\'div.row:odd\').addClass(\'zebra\');
		$(\'#btn_save\').bind(\'click\', function() {
			mail_list.save_filter($(\'#search_form\').serializeArray());
		});
		$(\'#id_subscription\').bind(\'change\', function() {this.form.submit()});
	});
'; ?>
</script>