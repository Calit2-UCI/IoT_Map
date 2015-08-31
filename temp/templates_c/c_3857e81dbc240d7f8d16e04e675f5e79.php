<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:38 Pacific Daylight Time */ ?>

<?php $this->assign('thumb_name', $this->_vars['recent_thumb']['name']); ?>
<div class="highlight p10 mb20 fltl" id="recent_photos">
    <h1>
		<span class="maxw230 ib text-overflow"><?php echo l('header_recent_photos', 'media', '', 'text', array()); ?></span>
		<span class="fright" id="refresh_recent_photos">
			<i class="fa-refresh icon-big edge hover"></i>
		</span>
	</h1>
    <?php if (is_array($this->_vars['recent_photos_data']['media']) and count((array)$this->_vars['recent_photos_data']['media'])): foreach ((array)$this->_vars['recent_photos_data']['media'] as $this->_vars['item']): ?>
		<span class="a" data-click="view-media" data-user-id="<?php echo $this->_vars['item']['id_owner']; ?>
" data-id-media="<?php echo $this->_vars['item']['id']; ?>
">
		   <div class="fleft small ml5">
				<?php 
$this->assign('text_media_photo', l('text_media_photo', 'media', '', 'button', array_merge(array(),$this->_vars['item'])));
 ?>
				<img class="small" src="<?php echo $this->_vars['item']['media']['mediafile']['thumbs'][$this->_vars['thumb_name']]; ?>
" width="<?php echo $this->_vars['recent_thumb']['width']; ?>
" alt="<?php echo $this->_vars['text_media_photo']; ?>
" title="<?php echo $this->_vars['text_media_photo']; ?>
" />
			</div>
		</span>
    <?php endforeach; endif; ?>
</div>
<script><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'media.js','module' => 'media','return' => 'path'), $this); echo '", 
			function(){
				recent_mediagallery = new media({
					siteUrl: site_url,
					gallery_name: \'recent_mediagallery\',
					galleryContentPage: 1,
					idUser: 0,
					all_loaded: 1,
					lang_delete_confirm: "';  echo l('delete_confirm', 'media', '', 'text', array());  echo '",
					galleryContentDiv: \'recent_photos\',
					post_data: {filter_duplicate: 1},
					load_on_scroll: false,
					sorterId: \'\',
					direction: \'asc\'
				});
			},
			\'recent_mediagallery\', 
			{async: false}
		);
	});
</script>'; ?>

