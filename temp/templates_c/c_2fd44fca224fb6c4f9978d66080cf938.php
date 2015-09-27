<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-22 02:32:26 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
    <div class="edit-form n200">
        <div class="row header"><?php echo l('admin_header_settings', 'like_me', '', 'text', array()); ?></div>
        <div class="row zebra">
            <div class="h"><?php echo l('field_matches_per_page', 'like_me', '', 'text', array()); ?>: </div>
            <div class="v"><input type="text" value="<?php echo $this->_vars['data']['matches_per_page']; ?>
" name="matches_per_page" class="short"></div>
        </div>
        <div class="row">
            <div class="h"><?php echo l('field_play_local_used', 'like_me', '', 'text', array()); ?>: </div>
            <div class="v"><input id="play_local_used" type="checkbox" value="1" name="play_local_used" <?php if ($this->_vars['data']['play_local_used']): ?>checked<?php endif; ?>/></div>
        </div>
        <div id="play_local_area" class="row">
            <div class="h"><?php echo l('field_play_local_area', 'like_me', '', 'text', array()); ?>: </div>
            <div class="v">
                <div>
                    <div><input type="radio" value="id_country" name="play_local_area" <?php if ($this->_vars['data']['play_local_area'] == 'id_country'): ?>checked<?php endif; ?>/>&nbsp;|&nbsp;<?php echo l('field_play_local_country', 'like_me', '', 'text', array()); ?></div>
                    <div><input type="radio" value="id_region" name="play_local_area" <?php if ($this->_vars['data']['play_local_area'] == 'id_region'): ?>checked<?php endif; ?>/>&nbsp;|&nbsp;<?php echo l('field_play_local_region', 'like_me', '', 'text', array()); ?></div>
                   <div><input type="radio" value="id_city" name="play_local_area" <?php if ($this->_vars['data']['play_local_area'] == 'id_city'): ?>checked<?php endif; ?>/>&nbsp;|&nbsp;<?php echo l('field_play_local_city', 'like_me', '', 'text', array()); ?></div>
                </div>
            </div>
        </div>
        <div class="row zebra">
            <div class="h"><?php echo l('field_play_more', 'like_me', '', 'text', array()); ?>: </div>
            <div class="v">
                <div>
                    <div><input type="checkbox" value="1" name="play_more[watch_again]" <?php if ($this->_vars['data']['play_more']['watch_again']): ?>checked<?php endif; ?>/>&nbsp;|&nbsp;<?php echo l('field_play_more_watch_again', 'like_me', '', 'text', array()); ?></div>
                    <div><input type="checkbox" value="1" name="play_more[search]" <?php if ($this->_vars['data']['play_more']['search']): ?>checked<?php endif; ?>/>&nbsp;|&nbsp;<?php echo l('field_play_more_search', 'like_me', '', 'text', array()); ?></div>
                    <div><input type="checkbox" value="1" name="play_more[perfect]" <?php if ($this->_vars['data']['play_more']['perfect']): ?>checked<?php endif; ?>/>&nbsp;|&nbsp;<?php echo l('field_play_more_perfect', 'like_me', '', 'text', array()); ?></div>
                </div>
            </div>
        </div>
        <div class="row zebra">
            <div class="h"><?php echo l('field_chat_more', 'like_me', '', 'text', array()); ?>:</div>
            <div class="v">
				<select name="chat_more" class="long">
					<?php if (is_array($this->_vars['data']['chat_more']) and count((array)$this->_vars['data']['chat_more'])): foreach ((array)$this->_vars['data']['chat_more'] as $this->_vars['key'] => $this->_vars['item']): ?>
						<option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['item']['selected']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
</option>
					<?php endforeach; endif; ?>
				</select>
			</div>
        </div>
        <div class="row">
            <div class="h"><?php echo l('field_chat_message', 'like_me', '', 'text', array()); ?>:</div>
            <div class="v">
                <div>
                    <?php if (is_array($this->_vars['data']['chat_message']) and count((array)$this->_vars['data']['chat_message'])): foreach ((array)$this->_vars['data']['chat_message'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
                        <?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>
                            <textarea name="chat_message[<?php echo $this->_vars['lang_id']; ?>
]" rows="5" cols="80" class="long pb2" lang-editor="value" lang-editor-type="data-chat_message" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_run_modifier($this->_vars['lang_item'], 'escape', 'plugin', 1); ?>
</textarea>
                        <?php else: ?>
                            <input type="hidden" name="chat_message[<?php echo $this->_vars['lang_id']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['lang_item'], 'escape', 'plugin', 1); ?>
" lang-editor="value" lang-editor-type="data-chat_message" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
" />
                        <?php endif; ?>
                    <?php endforeach; endif; ?>
                    <a href="#" lang-editor="button" lang-editor-type="data-chat_message"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16"></a>
		</div>
            </div>
        </div>
		
    </div>
    <div class="btn">
        <div class="l">
            <input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>">
        </div>
    </div>
</form>
<div class="clr"></div>
<script><?php echo '
$(function(){
	var status = $(\'#play_local_used\').prop(\'checked\');
	if(status){
		$(\'#play_local_area\').show();
	}else{
		$(\'#play_local_area\').hide();
	}
	$(\'#play_local_used\').click(function(){
		$(\'#play_local_area\').toggle();
	});
});
'; ?>
</script>
<?php echo tpl_function_block(array('name' => 'lang_inline_editor','module' => 'start','textarea' => '1'), $this);?>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>