<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:47:47 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<div class="filter-form">
    <div class="tbl">
        <?php if (is_array($this->_vars['requirements']) and count((array)$this->_vars['requirements'])): foreach ((array)$this->_vars['requirements'] as $this->_vars['item']): ?>
        <div class="row <?php if ($this->_vars['item']['result']): ?>green<?php else: ?>red<?php endif; ?>">
            <div class="value"><?php echo $this->_vars['item']['value']; ?>
</div>
            <div class="name"><?php echo $this->_vars['item']['name']; ?>
</div>
            <div class="clr"></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "network". $this->module_templates.  $this->get_current_theme_gid('admin', '"network"'). "settings-form.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="clr"></div>
    
<div class="edit-form network-content n250">
    <div class="row header">
        <?php echo l('admin_client_status', 'network', '', 'text', array()); ?>
    </div>
    <div class="row">
        <div class="h activity">
            <div class="net_client_started<?php if (! $this->_vars['clients_started']): ?> hide<?php endif; ?>">
                <font class="net_client_status success"><?php echo l('admin_client_started', 'network', '', 'text', array()); ?></font>
                (<a class="net_client_stop" href="<?php echo $this->_vars['site_url']; ?>
admin/network/stop"><?php echo l('admin_client_stop', 'network', '', 'text', array()); ?></a>)
            </div>
            <div class="net_client_stopped<?php if ($this->_vars['clients_started']): ?> hide<?php endif; ?>">
                <font class="net_client_status error"><?php echo l('admin_client_stopped', 'network', '', 'text', array()); ?></font>
                <?php if ($this->_vars['data_is_correct']): ?>
                    (<a class="net_client_stop" href="<?php echo $this->_vars['site_url']; ?>
admin/network/start"><?php echo l('admin_client_start', 'network', '', 'text', array()); ?></a>)
                <?php else: ?>
                    (<?php echo l('admin_error_data', 'network', '', 'text', array()); ?>)
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($this->_vars['net_show_log']): ?>
        <div class="row header">Log</div>
        <div class="row">
            <div class="header">Fast:</div>
            <pre><div class="wp100" id="fast">Empty</div></pre>
            <br>
            <div class="header">Slow:</div>
            <pre><div class="wp100" id="slow">Empty</div></pre>
        </div>
    <?php endif; ?>
</div>
<script><?php echo '
    $(function(){
        new adminNetwork({
            siteUrl: site_url,
            status: Boolean(';  echo $this->_vars['clients_started'];  echo '),
            showLog: Boolean(';  echo $this->_vars['clients_started'];  echo ')
        });
    });
</script>'; ?>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
