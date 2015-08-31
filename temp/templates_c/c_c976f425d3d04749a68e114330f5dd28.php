<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:21:14 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_social_networking_menu'), $this); if ($this->_vars['allow_add']): ?>
    <div class="actions">
        <ul>
            <li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/service_edit"><?php echo l('link_add_service', 'social_networking', '', 'text', array()); ?></a></div></li>
        </ul>
        &nbsp;
    </div>
<?php else: ?>
    <div class="actions">
        <ul>

        </ul>
        &nbsp;
    </div>
<?php endif; ?>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
    <tr>
        <th class="first"><?php echo l('field_name', 'social_networking', '', 'text', array()); ?></th>
        <th class="w100"><?php echo l('field_oauth', 'social_networking', '', 'text', array()); ?></th>
        <th class="w100"><?php echo l('field_active', 'social_networking', '', 'text', array()); ?></th>
		<th class="w100"></th>
        <?php if ($this->_vars['allow_edit'] || $this->_vars['allow_delete']): ?>
            <th class="w100"><?php echo l('actions', 'social_networking', '', 'text', array()); ?></th>
        <?php endif; ?>
    </tr>
    <?php if (is_array($this->_vars['services']) and count((array)$this->_vars['services'])): foreach ((array)$this->_vars['services'] as $this->_vars['item']): ?>
        <?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
        <tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
            <td><?php echo $this->_vars['item']['name']; ?>
</td>
            
            <td class="center">
                <?php if ($this->_vars['item']['oauth_enabled']): ?>
                    <?php if ($this->_vars['item']['oauth_status']): ?>
                        <a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/oauth_active/<?php echo $this->_vars['item']['id']; ?>
/0" title="<?php echo l('note_disable_login', 'social_networking', '', 'text', array()); ?>"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" ></a>
                        <?php else: ?>
                        <a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/oauth_active/<?php echo $this->_vars['item']['id']; ?>
/1" title="<?php echo l('note_enable_login', 'social_networking', '', 'text', array()); ?>"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" ></a>
                        <?php endif; ?>
                    <?php endif; ?>
            </td>
            <td class="center">
                <?php if ($this->_vars['item']['status']): ?>
                    <a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/service_active/<?php echo $this->_vars['item']['id']; ?>
/0" title="<?php echo l('note_disable_widget', 'social_networking', '', 'text', array()); ?>"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" ></a>
                    <?php else: ?>
                    <a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/service_active/<?php echo $this->_vars['item']['id']; ?>
/1" title="<?php echo l('note_enable_widget', 'social_networking', '', 'text', array()); ?>"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" ></a>
                    <?php endif; ?>
            </td>
			<td class="icons">
				<?php if ($this->_vars['item']['app_enabled']): ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/application/<?php echo $this->_vars['item']['id']; ?>
/"><img width="16" height="16" border="0" title="<?php echo l('link_application', 'social_networking', '', 'text', array()); ?>" alt="<?php echo l('link_application', 'social_networking', '', 'text', array()); ?>" src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png"></a>
				<?php endif; ?>
			</td>
            <?php if ($this->_vars['allow_edit'] || $this->_vars['allow_delete']): ?>
                <td class="icons">
                    <?php if ($this->_vars['allow_edit']): ?>
                        <a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/service_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_service', 'social_networking', '', 'text', array()); ?>" title="<?php echo l('link_edit_service', 'social_networking', '', 'text', array()); ?>"></a>
                        <?php endif; ?>
                        <?php if ($this->_vars['allow_delete']): ?>
                        <a href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/service_delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_service', 'social_networking', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_service', 'social_networking', '', 'text', array()); ?>" title="<?php echo l('link_delete_service', 'social_networking', '', 'text', array()); ?>"></a>
                        <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; else: ?>
        <tr><td class="center zebra" colspan=4><?php echo l('no_services', 'social_networking', '', 'text', array()); ?></td></tr>
    <?php endif; ?>
</table>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
