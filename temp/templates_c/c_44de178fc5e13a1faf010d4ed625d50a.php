<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:13:32 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_js(array('module' => 'dynamic_blocks','file' => 'dynamic_blocks_layout'), $this);?>

<div class="actions">
	<ul>
		<li><div class="l"><a href="#" id="update_layout"><?php echo l('link_save_block_sorting', 'dynamic_blocks', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_blocks/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_blocks', 'dynamic_blocks', '', 'text', array()); ?></a></li>
		<li class="active"><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_layout/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_layout', 'dynamic_blocks', '', 'text', array()); ?></a></li>
		<?php if ($this->_vars['presets_count'] > 1): ?><li><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_preset/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_preset', 'dynamic_blocks', '', 'text', array()); ?></a></li><?php endif; ?>
	</ul>
	&nbsp;
</div>

<div class="filter-form">
<form id="layout_form" action="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/save_layout/<?php echo $this->_vars['area']['id']; ?>
" method="post">
<div id="area_layout">
<ul name="parent_0" class="sort connected" id="clsr0ul">
<li class="sep">&nbsp;</li>
<?php if (is_array($this->_vars['layout']) and count((array)$this->_vars['layout'])): foreach ((array)$this->_vars['layout'] as $this->_vars['row'] => $this->_vars['blocks']): ?>
	<?php if (is_array($this->_vars['blocks']) and count((array)$this->_vars['blocks'])): foreach ((array)$this->_vars['blocks'] as $this->_vars['key'] => $this->_vars['item']): ?>
<li id="item_<?php echo $this->_vars['item']['id']; ?>
" class="col <?php if ($this->_vars['item']['width'] < 100): ?>col<?php echo $this->_vars['item']['width'];  endif; ?> <?php if ($this->_vars['key'] == 0): ?>first<?php endif; ?>" data-min-width=<?php echo $this->_vars['item']['block_data']['min_width']; ?>
>
	<h3><?php echo l($this->_vars['item']['block_data']['name_i'], $this->_vars['item']['block_data']['lang_gid'], '', 'text', array()); ?></h3>
	<?php if ($this->_vars['item']['block_data']['params']['html']['type'] == 'text'): ?><div><?php echo $this->_run_modifier($this->_vars['item']['params']['html'], 'escape', 'plugin', 1); ?>
</div><?php endif; ?>
	<input type="hidden" name="data[<?php echo $this->_vars['item']['id']; ?>
]" value="<?php echo $this->_vars['item']['width']; ?>
">
</li>
	<?php endforeach; endif; ?>
<li class="sep">&nbsp;</li>
<?php endforeach; endif; ?>
</ul>
</div>
</form>
</div>
<script><?php echo '
	var layout;
	$(function(){
		layout = new dynamicBlocksLayout({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
		});
	});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
