<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:56:53 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content">
<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>

	<div class="tab-submenu bg-highlight_bg">
		<div class="ib">
			<ul id="gallery_filters">
				<li data-param="all"<?php if ($this->_vars['gallery_param'] == 'all'): ?> class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'media','method' => 'all'), $this);?>"><span><?php echo l('all', 'media', '', 'text', array()); ?></span></a></li>
				<li data-param="photo"<?php if ($this->_vars['gallery_param'] == 'photo'): ?> class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'media','method' => 'photo'), $this);?>"><span><?php echo l('photo', 'media', '', 'text', array()); ?></span></a></li>
				<li data-param="video"<?php if ($this->_vars['gallery_param'] == 'video'): ?> class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'media','method' => 'video'), $this);?>"><span><?php echo l('video', 'media', '', 'text', array()); ?></span></a></li>
				<li data-param="albums"<?php if ($this->_vars['gallery_param'] == 'albums'): ?> class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'media','method' => 'albums'), $this);?>"><span><?php echo l('albums', 'media', '', 'text', array()); ?></span></a></li>
			</ul>
			<span id="gallery_albums"<?php if ($this->_vars['gallery_param'] != 'albums'): ?> class="hide"<?php endif; ?>><?php echo $this->_vars['albums']; ?>
</span>
			<span id="gallery_media_sorter"<?php if ($this->_vars['gallery_param'] == 'albums'): ?> class="hide"<?php endif; ?>>
				<select>
					<?php if (is_array($this->_vars['media_sorter']['links']) and count((array)$this->_vars['media_sorter']['links'])): foreach ((array)$this->_vars['media_sorter']['links'] as $this->_vars['key'] => $this->_vars['item']): ?>
						<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['media_sorter']['order']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
					<?php endforeach; endif; ?>
				</select>
				<i data-role="sorter-dir" class="fa-long-arrow <?php if ($this->_vars['media_sorter']['direction'] == 'ASC'): ?>up<?php else: ?>down<?php endif; ?> icon-big pointer plr5"></i>
			</span>
		</div>
		<div class="fright">
			<ul>
				<li><s id="add_photo" class="a btn-link"><i class="fa-camera icon-big edge hover"><i class="fa-mini-stack fa-plus bottomright"></i></i><i><?php echo l('add_photo', 'media', '', 'text', array()); ?></i></s></li>
				<li><s id="add_video" class="a btn-link"><i class="fa-facetime-video icon-big edge hover"><i class="fa-mini-stack fa-plus bottomright"></i></i><i><?php echo l('add_video', 'media', '', 'text', array()); ?></i></s></li>
			</ul>
		</div>
	</div>
	
	<div class="edit_block">
		<div id="gallery" class="gallery user-gallery medium"></div>
	</div>
</div>
	
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script><?php echo '
	$(function(){
		loadScripts(
			'; ?>
["<?php echo tpl_function_js(array('module' => 'media','file' => 'gallery.js','return' => 'path'), $this);?>", "<?php echo tpl_function_js(array('file' => 'media.js','module' => 'media','return' => 'path'), $this);?>"]<?php echo ', 
			function(){
				sitegallery = new gallery({
					id: \'gallery\',
					site_url: site_url,
					button_title: \'';  echo l('show_more', 'media', '', 'text', array());  echo '\',
					load_on_scroll: true,
					id_category: 0
				});
				mediagallery = new media({
					siteUrl: site_url,
					galleryContentPage: 1,
					idUser: 0,
					all_loaded: 1,
					lang_delete_confirm: "';  echo l('delete_confirm', 'media', '', 'text', array());  echo '",
					galleryContentDiv: \'gallery\',
					post_data: {filter_duplicate: 1},
					load_on_scroll: false,
					sorterId: \'gallery_media_sorter\',
					is_guest: \'';  echo $this->_vars['is_guest'];  echo '\'
				});
				sitegallery.init().load();
			},
			[\'sitegallery\', \'mediagallery\'], 
			{async: false}
		);
	});
</script>'; ?>

</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
