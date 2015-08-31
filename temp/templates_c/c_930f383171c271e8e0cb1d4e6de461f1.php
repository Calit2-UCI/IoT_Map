<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:37 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if (is_array($this->_vars['events']) and count((array)$this->_vars['events'])): foreach ((array)$this->_vars['events'] as $this->_vars['e']): ?>
	<?php if ($this->_vars['e']['html']): ?>
		<?php $this->assign('e_user_id', $this->_vars['e']['id_poster']); ?>
		<?php if ($this->_vars['users'][$this->_vars['e_user_id']]): ?>
			<?php $this->assign('e_user', $this->_vars['users'][$this->_vars['e_user_id']]); ?>
		<?php else: ?>
			<?php $this->assign('e_user', $this->_vars['users'][0]); ?>
		<?php endif; ?>
		<div id="wall_event_<?php echo $this->_vars['e']['id']; ?>
" class="user-content" gid="<?php echo $this->_vars['e']['event_type_gid']; ?>
">
			<div class="image small">
				<?php 
$this->assign('text_wall_photo', l('text_wall_photo', 'wall', '', 'button', array_merge(array(),$this->_vars['e'])));
 ?>
				<a href="<?php if (! empty ( $this->_vars['e_user']['id'] )):  echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['e_user']), $this); else:  echo tpl_function_seolink(array('module' => 'users','method' => 'untitled'), $this); endif; ?>"><img id="avatar_<?php echo $this->_vars['e_user']['id']; ?>
_e_<?php echo $this->_vars['e']['id']; ?>
"  src="<?php echo $this->_vars['e_user']['media']['user_logo']['thumbs']['small']; ?>
" alt="<?php echo $this->_vars['text_wall_photo']; ?>
" title="<?php echo $this->_vars['text_wall_photo']; ?>
" /></a>
			</div>
			<div class="content">
				<div class="fleft"><a href="<?php if (! empty ( $this->_vars['e_user']['id'] )):  echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['e_user']), $this); else:  echo tpl_function_seolink(array('module' => 'users','method' => 'untitled'), $this); endif; ?>"><?php echo $this->_vars['e_user']['output_name']; ?>
</a>&nbsp;&nbsp;</div>
				<?php echo $this->_vars['e']['html']; ?>

				<span class="fright"><?php echo tpl_function_block(array('name' => 'like_block','module' => 'likes','gid' => 'wevt'.$this->_vars['e']['id'],'type' => 'button'), $this);?></span>
				<div><?php echo tpl_function_block(array('name' => 'comments_form','module' => 'comments','gid' => 'wall_events','id_obj' => $this->_vars['e']['id'],'hidden' => 1,'count' => $this->_vars['e']['comments_count']), $this);?></div>
			</div>
		</div>
	<?php endif;  endforeach; endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
