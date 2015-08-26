{include file="header.tpl"}
{js module='install' file='product_install.js'}
{literal}
<script>
var product_install;
$(function(){
	product_install=new productInstall({siteUrl: '{/literal}{$site_root}{literal}', currentLang: '{/literal}{$lang_id}{literal}'});
	product_install.langs_update();
});
</script>
{/literal}

<div class="filter-form">
	<div class="install_main_window">
		<div class="pad">
			<h3>Update languages</h3>
			<div class="bar-level1" id="overall_bar"><div class="bar">0%</div></div>
			<br>
			<h3 id="module_name"></h3>
			<div id="module_desc"></div>
			<div id="modules_reload"></div>
		</div>
	</div>
</div>
<div class="clr"></div>
{include file="footer.tpl"}
