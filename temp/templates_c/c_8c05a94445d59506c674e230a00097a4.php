<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-21 01:58:57 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<div class="view small">
		<div class="image">
			<div id="user_photo" class="pos-rel dimp100<?php if ($this->_vars['data']['user_logo']): ?> pointer<?php endif; ?>">
				<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['data'])));
 ?>
				<img src="<?php echo $this->_vars['data']['media']['user_logo']['thumbs']['middle']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
" />
			</div>
		</div>
		<div class="info">
			
			<div class="body">
				<h1>
					<span class="users-profile-h1"><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></span>
					<span data-role="online_status" class="fright online-status"><s class="<?php echo $this->_vars['data']['statuses']['online_status_text']; ?>
"><?php echo $this->_vars['data']['statuses']['online_status_lang']; ?>
</s></span>
				</h1>
				<div>
					<div class="fright"><?php echo l('views', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['views_count']; ?>
</div>
					<div class="fleft clearfix">
						<div class="fleft">
						
							<!--remove age-->
							<!--<?php echo l('field_age', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['age']; ?>
-->						
						
							<?php if ($this->_vars['data']['location']): ?>
								<i class="delim-alone"></i><span class=""><?php echo $this->_vars['data']['location']; ?>
</span>
																	<i class="delim-alone"></i>
									<a href="javascript:void(0);" id="view_map_link" class="target_blank"><?php echo l('link_view_map', 'geomap', '', 'text', array()); ?></a>
															<?php endif; ?>
						</div>
						<div class="fleft">
							<?php echo tpl_function_block(array('name' => 'send_rating_block','module' => 'ratings','object_id' => $this->_vars['data']['id'],'type_gid' => 'users_object','responder_id' => $this->_vars['data']['id'],'success' => $this->_vars['rating_callback'],'is_owner' => $this->_vars['is_user_owner'],'template' => 'form'), $this);?>
						</div>
					</div>
				</div>
			</div>			
			
						<script type='text/javascript'><?php echo '
				$(function(){		
					loadScripts(
						["';  echo tpl_function_js(array('file' => 'users-map.js','module' => 'users','return' => 'path'), $this); echo '"],
						function(){
							user_map = new usersMap({
								siteUrl: site_url,
								user_id: \'';  echo $this->_vars['data']['id'];  echo '\',
							});
						},
						[\'user_map\'],
						{async: true}
					);
				});
			</script>'; ?>

						
			<div class="actions noPrint">
				<?php echo tpl_function_block(array('name' => 'send_message_button','module' => 'mailbox','id_user' => $this->_vars['data']['id']), $this);?>
				
				<?php echo tpl_function_block(array('name' => 'friendlist_links','module' => 'friendlist','id_user' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'blacklist_button','module' => 'blacklist','id_user' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'favourites_button','module' => 'favourites','id_user' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_helper(array('func_name' => 'im_chat_add_button','module' => 'im','func_param' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'video_chat_button','module' => 'video_chat','user_id' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'wink','module' => 'winks','user_id' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'kisses_list','module' => 'kisses','user_id' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'button','module' => 'associations','id_user' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['data']['id'],'type_gid' => 'users_object','template' => 'button'), $this);?>
			</div>
		</div>
	</div>

	<div class="edit_block" id="profile_tab_sections">
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "view_profile_menu.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		<div class="view-user">
			<?php if (! $this->_vars['profile_section'] || $this->_vars['profile_section'] == 'profile'): ?>
				<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "view_users_profile.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
			<?php elseif ($this->_vars['profile_section'] == 'wall'): ?>
				<?php echo tpl_function_block(array('name' => 'wall_block','module' => 'wall_events','place' => 'viewprofile','id_wall' => $this->_vars['user_id']), $this);?>
			<?php elseif ($this->_vars['profile_section'] == 'gallery'): ?>
				<?php echo tpl_function_block(array('name' => 'media_block','module' => 'media','param' => $this->_vars['subsection'],'page' => '1','user_id' => $this->_vars['user_id'],'location_base_url' => $this->_vars['location_base_url']), $this);?>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  if ($this->_vars['data']['user_logo']): ?>
	<script type='text/javascript'><?php echo '
		$(function(){
			loadScripts(
				["';  echo tpl_function_js(array('file' => 'users-avatar.js','module' => 'users','return' => 'path'), $this); echo '"],
				function(){
					user_avatar = new avatar({
						site_url: site_url,
						id_user: ';  echo $this->_vars['user_id'];  echo '
					});
				},
				[\'user_avatar\'],
				{async: true}
			);
		});
	</script>'; ?>

<?php endif;  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
