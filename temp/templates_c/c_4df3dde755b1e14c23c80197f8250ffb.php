<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.pagination.php');
$this->register_function("pagination", "tpl_function_pagination"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:37:22 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content-block">
	<h1>
		<?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?>
		<div class="fright">
			<a target="_blank" class="icon-rss icon-big edge hover zoom20" href="<?php echo $this->_vars['site_url']; ?>
news/rss"></a>
		</div>
	</h1>

	<?php if (is_array($this->_vars['news']) and count((array)$this->_vars['news'])): foreach ((array)$this->_vars['news'] as $this->_vars['item']): ?>
		<div class="news news-item">
			<a href="<?php echo tpl_function_seolink(array('module' => 'news','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['name']; ?>
</a>
			<?php if ($this->_vars['item']['img']): ?>
				<?php if ($this->_vars['item']['media']['img']['thumbs']['small']): ?>
				<?php 
$this->assign('text_news_photo', l('text_news_photo', 'news', '', 'button', array_merge(array(),$this->_vars['item'])));
 ?>
					<img src="<?php echo $this->_vars['item']['media']['img']['thumbs']['small']; ?>
" align="left" alt="<?php echo $this->_vars['text_news_logo']; ?>
" title="<?php echo $this->_vars['text_news_logo']; ?>
" />
				<?php endif; ?>
			<?php endif; ?>
			<span class="date"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</span><br>
			<span class="annotation"><?php echo $this->_vars['item']['annotation']; ?>
</span><br>
			<div class="links">
				<?php if ($this->_vars['item']['feed']):  echo l('feed_source', 'news', '', 'text', array()); ?>: <a href="<?php echo $this->_vars['item']['feed']['site_link']; ?>
"><?php echo $this->_vars['item']['feed']['title']; ?>
</a><br><?php endif; ?>
				<a href="<?php echo tpl_function_seolink(array('module' => 'news','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo l('link_view_more', 'news', '', 'text', array()); ?></a>
			</div>
			<div>
				<?php echo tpl_function_block(array('name' => 'comments_form','module' => 'comments','gid' => news,'id_obj' => $this->_vars['item']['id'],'hidden' => 1,'count' => $this->_vars['item']['comments_count']), $this);?>
			</div>
			<div class="clr"></div>
		</div>
	<?php endforeach; else: ?>
		<div class="empty"><?php echo l("no_news_yet_header", 'news', '', 'text', array()); ?></div>
	<?php endif; ?>
	<div class="clr"></div>
	<?php if ($this->_vars['news']): ?><div class="line top"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'full'), $this);?></div><?php endif; ?>
</div>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
