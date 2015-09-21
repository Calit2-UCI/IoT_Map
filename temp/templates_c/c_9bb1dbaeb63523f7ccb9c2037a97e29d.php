<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 01:46:00 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content-block">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
	<div class="news wysiwyg">
		
		<?php if ($this->_vars['data']['img']): ?>
			<?php if ($this->_vars['data']['media']['img']['thumbs']['big']): ?>
			<?php 
$this->assign('text_news_photo', l('text_news_photo', 'news', '', 'button', array_merge(array(),$this->_vars['data'])));
 ?>
				<img src="<?php echo $this->_vars['data']['media']['img']['thumbs']['big']; ?>
" align="left" hspace="5" vspace="5" alt="<?php echo $this->_vars['text_news_photo']; ?>
" title="<?php echo $this->_vars['text_news_photo']; ?>
" />
			<?php endif; ?>
		<?php endif; ?>
		<span class="date"><?php echo $this->_run_modifier($this->_vars['data']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</span><br>
		<?php if (! $this->_vars['data']['content']): ?><span class="annotation"><?php echo $this->_vars['data']['annotation']; ?>
</span><br>
		<?php else: ?><span class="annotation"><?php echo $this->_vars['data']['content']; ?>
</span><br><?php endif; ?>
		<?php if ($this->_vars['data']['video_content']['embed']): ?>
			<?php echo $this->_vars['data']['video_content']['embed']; ?>
<br>
		<?php endif; ?>

		<?php if ($this->_vars['data']['feed_link']):  echo l('feed_source', 'news', '', 'text', array()); ?>: <a href="<?php echo $this->_vars['data']['feed_link']; ?>
"><?php echo $this->_vars['data']['feed']['title']; ?>
</a><?php endif; ?>
		<div class="clr"></div>
		<?php echo tpl_function_block(array('name' => 'comments_form','module' => 'comments','gid' => news,'id_obj' => $this->_vars['data']['id'],'hidden' => 0,'count' => $this->_vars['data']['comments_count']), $this);?>
		<br><a href="<?php echo tpl_function_seolink(array('module' => 'news','method' => 'index'), $this);?>"><?php echo l('link_back_to_news', 'news', '', 'text', array()); ?></a>
	</div>
</div>

<?php echo tpl_function_block(array('name' => 'show_social_networks_like','module' => 'social_networking'), $this); echo tpl_function_block(array('name' => 'show_social_networks_share','module' => 'social_networking'), $this); echo tpl_function_block(array('name' => 'show_social_networks_comments','module' => 'social_networking'), $this);?>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
