<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-06 00:02:12 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<h1><?php echo tpl_function_seotag(array('tag' => "header_text"), $this);?></h1>
<div class="content-block" id="like_me-block">
	<div class="edit_block">
		<div class="tabs tab-size-15 noPrint">
			<ul>
				<?php if ($this->_vars['data']['play_local_used']): ?>
				<li<?php if ($this->_vars['action'] == 'play_global'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'like_me','method' => 'index','action' => 'play_global'), $this);?>"><?php echo l('header_play_global', 'like_me', '', 'text', array()); ?></a></li>
				<li<?php if ($this->_vars['action'] == 'play_local'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'like_me','method' => 'index','action' => 'play_local'), $this);?>"><?php echo l('header_play_local', 'like_me', '', 'text', array()); ?></a></li>
				<?php else: ?>
				<li<?php if ($this->_vars['action'] == 'play_global'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'like_me','method' => 'index','action' => 'play_global'), $this);?>"><?php echo l('header_play', 'like_me', '', 'text', array()); ?></a></li>
				<?php endif; ?>
				<li<?php if ($this->_vars['action'] == 'matches'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'like_me','method' => 'index','action' => 'matches'), $this);?>"><?php echo l('header_matches', 'like_me', '', 'text', array()); ?></a></li>
			</ul>
		</div>
		<div id="action-block"></div>
	</div>
</div>
<script>
<?php if (! empty ( $this->_vars['user_data']['have_more'] )): ?>
	var all_loaded = <?php if ($this->_vars['user_data']['have_more']): ?>0<?php else: ?>1<?php endif; ?>;
<?php else: ?>
	var all_loaded = 0;
<?php endif; ?>
<?php echo '
$(function(){
	loadScripts(
		["';  echo tpl_function_js(array('module' => 'like_me','file' => 'like_me.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('module' => 'like_me','file' => 'match_me.js','return' => 'path'), $this); echo '"],
		function(){
			var action_id = \'';  echo $this->_vars['action'];  echo '\';
			like_me = new LikeMe({
				siteUrl: site_url,
				action_id: action_id,
			});
			match_me = new MatchMe({
				siteUrl: site_url,
				all_loaded: all_loaded,
				show_more_lang: "';  echo l('button_show_more', 'like_me', '', 'text', array());  echo '",
			});
		},
		[\'like_me\', \'match_me\'],
		{async: true}
	);
});
</script>'; ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>