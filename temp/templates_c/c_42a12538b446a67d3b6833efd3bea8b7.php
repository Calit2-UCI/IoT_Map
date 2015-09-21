<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:50:05 Pacific Daylight Time */ ?>

<div class="content-block">
	<div class="edit_block">
                <?php if ($this->_vars['billing_systems']): ?>
                <form action="<?php echo $this->_vars['site_url']; ?>
users_payments/save_payment/" method="post" role="form" id="payment_form">
                        <div class="r ptb20">
                                <div class="f"><input type="hidden" value="" name="system_gid" id="system_gid" /></div>
                                <div class="v">
                                <?php if (is_array($this->_vars['billing_systems']) and count((array)$this->_vars['billing_systems'])): foreach ((array)$this->_vars['billing_systems'] as $this->_vars['item']): ?>
                                        <?php if ($this->_vars['item']['logo_url']): ?>
                                                <a href="#" onclick="system_gid_change('<?php echo $this->_vars['item']['gid']; ?>
'); return false;" id="system_gid_img"><img src="<?php echo $this->_vars['item']['logo_url']; ?>
" title="<?php echo $this->_vars['item']['name']; ?>
" alt="<?php echo $this->_vars['item']['name']; ?>
" class="mrb10 fltl h100 box-sizing"></a>
                                                
                                        <?php else: ?>
                                                
                                        <?php endif; ?>
                                <?php endforeach; endif; ?>
                                </div>
                        </div>

                        <div class="r form-group hide" id="operators">
                                <span class="h2"><?php echo l('field_operator', 'users_payments', '', 'text', array()); ?>:</span>
                                <div id="operators_block"></div>
                        </div>

                        <div class="r form-group hide" id="amount">
                                <span class="h2"><?php echo l('field_enter_amount', 'users_payments', '', 'text', array()); ?>:</span>
                                <div id="amount_block"></div>
                        </div>

                        <div class="r form-group hide" id="details">
                                <label><?php echo l('field_info_data', 'payments', '', 'text', array()); ?>:</label>
                                <div id="details_block"></div>
                        </div>
                    
                        <div class="r hide" id="errors">
                                <i>Empty operators list</i>
                        </div>

                        <button type="submit" name="btn_payment_save" class="btn btn-primary" value="1">
                                <?php echo l('btn_send', 'start', '', 'text', array()); ?>
                        </button>
                </form>

                <?php if (is_array($this->_vars['billing_systems']) and count((array)$this->_vars['billing_systems'])): foreach ((array)$this->_vars['billing_systems'] as $this->_vars['item']): ?>
                <div id="system_<?php echo $this->_vars['item']['gid']; ?>
" class="hide" data-tarifs="<?php echo $this->_vars['item']['tarifs_type']; ?>
">
                        <div id="operators_<?php echo $this->_vars['item']['gid']; ?>
">
                                <select name="operator" class="form-control middle">
                                        <?php if (is_array($this->_vars['item']['operators_data']) and count((array)$this->_vars['item']['operators_data'])): foreach ((array)$this->_vars['item']['operators_data'] as $this->_vars['operator_key'] => $this->_vars['operator_item']): ?>
                                        <?php if ($this->_vars['item']['tarifs_status'][$this->_vars['operator_key']]): ?>
                                        <option value="<?php echo $this->_vars['item']['gid']; ?>
_<?php echo $this->_vars['operator_key']; ?>
"><?php echo $this->_vars['operator_item']; ?>
</option>
                                        <?php endif; ?>
                                        <?php endforeach; endif; ?>
                                        <?php if ($this->_vars['item']['tarifs_type'] == 2): ?>
                                        <option value="<?php echo $this->_vars['item']['gid']; ?>
"><?php echo l('text_tarif_custom', 'users_payments', '', 'text', array()); ?></option>
                                        <?php endif; ?>
                                </select>
                        </div>
                        <?php if (is_array($this->_vars['item']['operators_data']) and count((array)$this->_vars['item']['operators_data'])): foreach ((array)$this->_vars['item']['operators_data'] as $this->_vars['operator_key'] => $this->_vars['operator_item']): ?>
                        <div id="amount_<?php echo $this->_vars['item']['gid']; ?>
_<?php echo $this->_vars['operator_key']; ?>
">
                                <select name="amount" class="form-control middle">
                                        <?php if (is_array($this->_vars['item']['tarifs_data'][$this->_vars['operator_key']]) and count((array)$this->_vars['item']['tarifs_data'][$this->_vars['operator_key']])): foreach ((array)$this->_vars['item']['tarifs_data'][$this->_vars['operator_key']] as $this->_vars['tarif_item']): ?>
                                        <option value="<?php echo $this->_vars['tarif_item']; ?>
">
                                                <?php echo tpl_function_block(array('name' => currency_format_output,'module' => start,'value' => $this->_vars['tarif_item'],'cur_gid' => $this->_vars['base_currency']['gid']), $this);?>
                                        </option>
                                        <?php endforeach; endif; ?>
                                </select>
                        </div>
                        <?php endforeach; endif; ?>
                        <div id="amount_<?php echo $this->_vars['item']['gid']; ?>
">
                                <input type="text" name="amount" class="form-control middle">&nbsp;
                                <?php echo tpl_function_block(array('name' => currency_output,'module' => start,'cur_gid' => $this->_vars['base_currency']['gid']), $this);?>
                        </div>
                        <div id="details_<?php echo $this->_vars['item']['gid']; ?>
"><?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  echo $this->_vars['item']['info_data'];  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?></div>
                </div>
                <?php endforeach; endif; ?>
                <script><?php echo '
                        function system_gid_change(value) {
                                $(\'#amount, #operators, #tarifs, #details, #errors\').hide();
                                if(value){
                                        $(\'#system_gid\').val(value);
                                        var tarifs_type =  $(\'#system_\'+value).data(\'tarifs\');
                                        if(tarifs_type > 0){
                                                if($(\'#operators_\'+value+\' select\').html()) {
                                                        var operators = $(\'#operators_\'+value).html();
                                                        var operator = $(\'#operators\').show()
                                                                .find(\'#operators_block\')
                                                                .html(operators)
                                                                .find(\'select\')
                                                                .trigger(\'change\');
                                                        $(\'#amount .h2\').html("';  echo l('field_choose_amount', 'users_payments', '', 'text', array());  echo '");
                                                } else {
                                                        $(\'#errors\').show();
                                                }
                                        }else{
                                                var amount = $(\'#amount_\'+value).html();
                                                $(\'#amount\').show().find(\'#amount_block\').html(amount);
                                        }
                                        var details = $(\'#details_\'+value).html();
                                        if(details.length) $(\'#details\').show().find(\'#details_block\').html(details);
                                }
                        }
                        $(function(){
                                $(\'#operators\').on(\'change\', \'select\', function(){
                                        var amount = $(\'#amount_\'+this.value).html();
                                        $(\'#amount\').show().find(\'#amount_block\').html(amount);
                                });
                        });
                '; ?>
</script>
                <?php else: ?>
                        <div class="r">
                                <i><?php echo l('error_empty_billing_system_list', 'users_payments', '', 'text', array()); ?></i>
                        </div>
                <?php endif; ?>
        </div>
</div>
