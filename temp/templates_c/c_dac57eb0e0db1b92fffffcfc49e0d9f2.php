<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-06 00:02:13 Pacific Daylight Time */ ?>

<?php if (! empty ( $this->_vars['user_data'] )): ?>
	<div class="media-gallery mt20 center" data-profile_id="<?php echo $this->_vars['user_data']['id']; ?>
">
		<div class="user">
			<div class="photo pos-rel">
				<div id="congratulations" class="center"></div>
				<span>
					<img src="<?php echo $this->_vars['user_data']['media']['user_logo']['thumbs']['grand']; ?>
" alt="<?php echo $this->_vars['user_data']['output_name']; ?>
" title="<?php echo $this->_vars['user_data']['output_name']; ?>
" />
				</span>
			</div>
		</div>
		<div class="mt10 mb20">
			<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['user_data']), $this);?>"><?php echo $this->_vars['user_data']['output_name']; ?>
</a>,&nbsp;<?php echo $this->_vars['user_data']['age']; ?>

		</div>
		<div id="action-button" class="text-overflow">
			<span class="mr20"><input id="skip_button" type="button" value="<?php echo l('button_skip', 'like_me', '', 'text', array()); ?>"></span>
			<span class="ml20"><input id="like_button" type="button" value="<?php echo l('button_like', 'like_me', '', 'text', array()); ?>"></span>		
		</div>
	</div>
<?php else: ?>
	<div>
		<div class="mt20"><h2><?php echo l('empty_users_list', 'like_me', '', 'text', array()); ?></h2></div>
		<div class="mt20">
			<?php if (is_array($this->_vars['play_more']) and count((array)$this->_vars['play_more'])): foreach ((array)$this->_vars['play_more'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<?php $this->assign('field', 'field_play_more_'.$this->_vars['key']); ?>
				<span class="mr20"><input type="button" value="<?php echo l($this->_vars['field'], 'like_me', '', 'text', array()); ?>" id="go-<?php echo $this->_vars['key']; ?>
"></span>
			<?php endforeach; endif; ?>
		</div>
	</div>
<?php endif; ?>