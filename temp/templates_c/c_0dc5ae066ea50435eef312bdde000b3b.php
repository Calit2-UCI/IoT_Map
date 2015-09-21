<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:45:50 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  echo $this->_vars['data']['js']; ?>

<div class="content-block">

	<h1 class="inl"><?php echo l('header_payment_form', 'payments', '', 'text', array()); ?></h1>

	<div class="content-value">
		

		<form method="post">
		<input type="hidden" name="payment_type_gid" value="<?php echo $this->_vars['data']['payment_type_gid']; ?>
">
		<input type="hidden" name="amount" value="<?php echo $this->_vars['data']['amount']; ?>
">
		<input type="hidden" name="currency_gid" value="<?php echo $this->_vars['data']['currency_gid']; ?>
">
		<input type="hidden" name="system_gid" value="<?php echo $this->_vars['data']['system_gid']; ?>
">
		<input type="hidden" name="payment_data[name]" value="<?php echo $this->_run_modifier($this->_vars['data']['payment_data']['name'], 'escape', 'plugin', 1); ?>
">

		<div class="edit_block">
			<div class="payment_table">
				<table>
					<tr>
						<td><?php echo l('field_payment_name', 'payments', '', 'text', array()); ?>:</td>
						<td class="value"><?php echo $this->_vars['data']['payment_data']['name']; ?>
 (<?php echo $this->_vars['data']['payment_type_gid']; ?>
)</td>
					</tr>
					<tr>
						<td><?php echo l('field_amount', 'payments', '', 'text', array()); ?>:</td>
						<td class="value"><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['data']['amount'],'cur_gid' => $this->_vars['data']['currency_gid']), $this);?></td>
					</tr>
					<tr>
						<td><?php echo l('field_billing_type', 'payments', '', 'text', array()); ?>:</td>
						<td class="value"><?php echo $this->_vars['data']['system']['name']; ?>
</td>
					</tr>
					<?php if ($this->_vars['data']['system']['info_data']): ?>
						<tr>
							<td><?php echo l('field_info_data', 'payments', '', 'text', array()); ?>:</td>
							<td class="value"><?php echo $this->_vars['data']['system']['info_data']; ?>
</td>
						</tr>
					<?php endif; ?>
					<?php if (is_array($this->_vars['data']['map']) and count((array)$this->_vars['data']['map'])): foreach ((array)$this->_vars['data']['map'] as $this->_vars['key'] => $this->_vars['item']): ?>
					<tr>
						<td><?php echo $this->_vars['item']['name']; ?>
:</td>
						<td class="value">
							<?php if ($this->_vars['item']['type'] == 'text'): ?>
							<input type="text" name="map[<?php echo $this->_vars['key']; ?>
]" value="<?php echo $this->_vars['data']['payment_data'][$this->_vars['key']]; ?>
" <?php if ($this->_vars['item']['size'] == 'small'): ?>class="short"<?php elseif ($this->_vars['item']['size'] == 'big'): ?>class="long"<?php endif; ?>>
							<?php elseif ($this->_vars['item']['type'] == 'textarea'): ?>
							<textarea name="map[<?php echo $this->_vars['key']; ?>
]" rows="10" cols="40"><?php echo $this->_vars['data']['payment_data'][$this->_vars['key']]; ?>
</textarea>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; endif; ?>

				</table>
			</div>
			<div class="b outside">
				<input type="submit" class='btn' value="<?php echo l('btn_send', 'start', '', 'button', array()); ?>" name="btn_save">
				<a class="btn-link" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => account,'action' => 'payments_history'), $this);?>">
					<i class="icon-arrow-left icon-big edge hover"></i><i><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></i>
				</a>
			</div>
		</div>
		</form>
	</div>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
