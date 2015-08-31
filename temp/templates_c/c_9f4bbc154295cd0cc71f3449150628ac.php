<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.math.php');
$this->register_function("math", "tpl_function_math"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:06:58 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo '
<script type="text/javascript">
	function equalHeight(group) {
		tallest = 0;
		group.each(function() {
			thisHeight = $(this).height();
			if(thisHeight > tallest) {
				tallest = thisHeight;
			}
		});
		group.height(tallest);
	}
</script>
'; ?>

<div class="sitemap">
	<h1 class="inl"><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
	<?php $this->assign('line', 1); ?>
	<?php if (is_array($this->_vars['blocks']) and count((array)$this->_vars['blocks'])): foreach ((array)$this->_vars['blocks'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php echo '
		<script type="text/javascript">
			$(function(){
				equalHeight($(".line';  echo $this->_vars['line'];  echo '"));
			});
		</script>
		'; ?>

		<?php if (!($this->_vars['key'] % 4)): ?><div class="clr"></div><?php if ($this->_vars['key']):  echo tpl_function_math(array('equation' => "x + 1",'x' => $this->_vars['line'],'assign' => "line"), $this);?><div class="horizontal_line"></div><?php endif;  endif; ?>
		<?php echo tpl_function_math(array('equation' => "x + 1",'x' => $this->_vars['key'],'assign' => "counter"), $this);?>
		<div class="line<?php echo $this->_vars['line']; ?>
 block <?php if (!(! ( $this->_vars['counter'] % 4) ) ): ?>right_border<?php endif; ?>"><?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "site_map". $this->module_templates.  $this->get_current_theme_gid('', '"site_map"'). "sitemap_level.tpl", array('list' => $this->_vars['item'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?></div>
	<?php endforeach; endif; ?>
</div>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>