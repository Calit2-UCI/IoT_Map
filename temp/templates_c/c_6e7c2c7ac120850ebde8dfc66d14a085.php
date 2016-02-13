<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 01:12:34 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="lef">
	<div class="edit-form n100">
		<div class="row header"><?php echo l('admin_header_pages_in_group', 'banners', '', 'text', array()); ?> : <?php echo $this->_vars['group_data']['name']; ?>
</div>
		<div class="row zebra">
			<ol id="group_pages" class="connectSort">
				<?php if (is_array($this->_vars['group_pages']) and count((array)$this->_vars['group_pages'])): foreach ((array)$this->_vars['group_pages'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<li id="group_pages_<?php echo $this->_vars['key']; ?>
"><b class="name"><?php echo $this->_vars['item']['name']; ?>
</b><br><i><?php echo $this->_vars['site_url']; ?>
<span class="link"><?php echo $this->_vars['item']['link']; ?>
</span></i></li>
				<?php endforeach; endif; ?>
			</ol>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="button" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" onclick="javascript: save_pages();"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/banners/groups_list"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header"><?php echo l('admin_header_free_pages', 'banners', '', 'text', array()); ?></div>

		<div class="row">&nbsp;
			<select onchange="javascript: load_pages(this.value);">
			<option value="0">...</option>
			<?php if (is_array($this->_vars['modules']) and count((array)$this->_vars['modules'])): foreach ((array)$this->_vars['modules'] as $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['module_name']; ?>
 (<?php echo $this->_vars['item']['module_data']['module_name']; ?>
)</option><?php endforeach; endif; ?>
			</select>
		</div>

		<div class="row zebra">
			<ol id="module_pages" class="connectSort">
				<br><br>
			</ol>
		</div>
	</div>
</div>
<div class="clr"></div>

<script type="text/javascript">
var url = '<?php echo $this->_vars['site_url']; ?>
admin/banners/ajax_get_modules_pages/';
var save_url = '<?php echo $this->_vars['site_url']; ?>
admin/banners/ajax_save_group_pages/<?php echo $this->_vars['group_data']['id']; ?>
';
var return_url = '<?php echo $this->_vars['site_url']; ?>
admin/banners/groups_list';
<?php echo '
$(function(){
	$("#group_pages, #module_pages").sortable({
		connectWith: \'.connectSort\',
		placeholder: \'limiter\',
		scroll: true,
		forcePlaceholderSize: true
	}).disableSelection();
});
function load_pages(val){
	if(val == 0){
		$("#module_pages").html();
		return;
	}

	$.ajax({
		url: url + val, 
		cache: false,
		success: function(data){
			$("#module_pages").html(data);
			$("#group_pages").sortable({
				connectWith: \'.connectSort\',
				scroll: true,
				forcePlaceholderSize: true
			}).disableSelection();
			$("#module_pages").sortable({
				connectWith: \'.connectSort\',
				items: \'li.sortable\',
				scroll: true,
				forcePlaceholderSize: true
			}).disableSelection();
		}
	});

}

function save_pages(){
	var data = new Object();
	$("#group_pages li").each(function(i){
		var id = $(this).attr(\'id\');
		data[i] = {
			name: $(\'#\'+id+\' .name\').html(),
			link: $(\'#\'+id+\' .link\').html()
		};
	});
	$.ajax({
		url: save_url, 
		type: \'POST\',
		data: {pages: data}, 
		cache: false,
		success: function(data){
			location.href = return_url;
		}
	});
}
'; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>