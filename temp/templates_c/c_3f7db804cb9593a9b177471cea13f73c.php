<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 08:49:32 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'easyTooltip.min.js'), $this);?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">

<table cellspacing="0" cellpadding="0" class="data" width="100%">
    <tr>
        <th class="first"><?php echo l('field_name', 'social_networking', '', 'text', array()); ?></th>
        <th class="w100"><?php echo l('widget_like', 'social_networking', '', 'text', array()); ?></th>
        <th class="w100"><?php echo l('widget_share', 'social_networking', '', 'text', array()); ?></th>
        <th class="w100"><?php echo l('widget_comments', 'social_networking', '', 'text', array()); ?></th>
    </tr>
	<?php if (count ( $this->_vars['services'] ) > 0): ?>
    <?php if (is_array($this->_vars['services']) and count((array)$this->_vars['services'])): foreach ((array)$this->_vars['services'] as $this->_vars['key'] => $this->_vars['item']): ?>
        <?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
        <tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
            <td><?php echo $this->_vars['item']['name']; ?>
 <?php if (! $this->_vars['item']['status']): ?>(<?php echo l('no_active_widgets', 'social_networking', '', 'text', array()); ?>)<?php endif; ?></td>
            <td class="center"><?php if (in_array ( 'like' , $this->_vars['widgets_actions'][$this->_vars['key']] )): ?><input type="checkbox" name="like[<?php echo $this->_vars['item']['gid']; ?>
]" <?php if (! $this->_vars['item']['status']): ?>disabled<?php else:  $this->assign('gid', $this->_vars['item']['gid']);  if ($this->_vars['data']['data']['like'][$this->_vars['gid']]): ?>checked<?php endif;  endif; ?> /><?php endif; ?></td>
            <td class="center"><?php if (in_array ( 'share' , $this->_vars['widgets_actions'][$this->_vars['key']] )): ?><input type="checkbox" name="share[<?php echo $this->_vars['item']['gid']; ?>
]" <?php if (! $this->_vars['item']['status']): ?>disabled<?php else:  $this->assign('gid', $this->_vars['item']['gid']);  if ($this->_vars['data']['data']['share'][$this->_vars['gid']]): ?>checked<?php endif;  endif; ?> /><?php endif; ?></td>
            <td class="center"><?php if (in_array ( 'comments' , $this->_vars['widgets_actions'][$this->_vars['key']] )): ?><input type="checkbox" name="comments[<?php echo $this->_vars['item']['gid']; ?>
]" value="<?php echo $this->_vars['item']['id']; ?>
" <?php if (! $this->_vars['item']['status']): ?>disabled<?php else:  $this->assign('gid', $this->_vars['item']['gid']);  if ($this->_vars['data']['data']['comments'][$this->_vars['gid']]): ?>checked<?php endif;  endif; ?> /><?php endif; ?></td>
        </tr>
	<?php endforeach; endif; ?>
    <?php else: ?>
        <tr><td class="center zebra" colspan=4><?php echo l('no_services', 'social_networking', '', 'text', array()); ?></td></tr>
    <?php endif; ?>
</table>

		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/pages/"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<script type='text/javascript'>
	<?php echo '
	$(function(){
		$(".tooltip").each(function(){
			$(this).easyTooltip({
				useElement: \'tt_\'+$(this).attr(\'id\')
			});
		});
	});
	'; ?>

</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>