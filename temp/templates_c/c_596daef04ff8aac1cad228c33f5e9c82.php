<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-26 11:13:24 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'date.js'), $this); echo tpl_function_js(array('module' => 'start','file' => 'date_formats.js'), $this); $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "start". $this->module_templates.  $this->get_current_theme_gid('', '"start"'). "numerics_menu.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<form id="date_format" method="post" action="" name="save_form" enctype="multipart/form-data">
	<input type="hidden" id="format_id" name="format_id" value="<?php echo $this->_vars['format']['gid']; ?>
" />
	<div class="edit-form n150">
		<div class="row header"><?php echo $this->_vars['settings_name']; ?>
</div>		
		<?php if (is_array($this->_vars['format']['available']) and count((array)$this->_vars['format']['available'])): foreach ((array)$this->_vars['format']['available'] as $this->_vars['field'] => $this->_vars['values']): ?>
			<?php if ($this->_run_modifier($this->_vars['values'], 'count', 'PHP', 0) > 1): ?>
			<?php echo tpl_function_counter(array('print' => false,'assign' => 'counter'), $this);?>
				<div class="row <?php if (!($this->_vars['counter'] % 2)): ?>zebra<?php endif; ?>">
					<div class="h"><?php echo l('date_format_'.$this->_vars['field'], 'start', '', 'text', array()); ?></div>
					<div class="v format">
						<?php if (is_array($this->_vars['values']) and count((array)$this->_vars['values'])): foreach ((array)$this->_vars['values'] as $this->_vars['item']): ?>
							<div class="middle_short fl">
							<input type="radio" name="<?php echo $this->_vars['field']; ?>
" id="<?php echo $this->_vars['item']; ?>
" value="<?php echo $this->_vars['item']; ?>
"<?php if ($this->_vars['format']['current'][$this->_vars['field']] == $this->_vars['item']): ?> checked="checked"<?php endif; ?>><label for="<?php echo $this->_vars['item']; ?>
"><?php echo l('date_format_'.$this->_vars['item'], 'start', '', 'text', array()); ?></label>								
							</div>

						<?php endforeach; endif; ?>
					</div>
				</div>
			<?php else: ?>
				<div class="format">
					<input type="hidden" name="<?php echo $this->_vars['field']; ?>
" id="<?php echo $this->_vars['values'][0]; ?>
" value="<?php echo $this->_vars['values'][0]; ?>
">
				</div>
			<?php endif; ?>
		<?php endforeach; endif; ?>
		<div class="row">
			<div class="h"><?php echo l('template', 'start', '', 'text', array()); ?></div>
			<div class="v tpl">
				<input autocomplete="off" class="w200 long" type="text" name="tpl" id="tpl" value="<?php echo $this->_vars['format']['current']['tpl']; ?>
"><br/>
				<i><?php if (is_array($this->_vars['format']['available']) and count((array)$this->_vars['format']['available'])): foreach ((array)$this->_vars['format']['available'] as $this->_vars['field'] => $this->_vars['field_data']): ?>
						<span class="sample">[<?php echo $this->_vars['field']; ?>
]</span>
					<?php endforeach; endif; ?></i>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('example', 'start', '', 'text', array()); ?></div>
			<div id="example" class="v"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/settings/date_formats"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>

<?php echo '
<script type="text/javascript">
	$(function(){
		new date_formats({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\'
		});
	});
</script>
'; ?>


<div class="clr"></div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>