{include file="header.tpl"}
{js module='install' file='product_install.js'}
{literal}
<script>
var product_install;
$(function(){
	product_install=new productInstall({siteUrl: '{/literal}{$site_root}{literal}', installType: 'module'});
});
</script>
{/literal}

<div class="filter-form">
	<div class="install_main_window">
		<div class="pad">
			<h3>Installation status</h3>
			<div class="bar-level1" id="overall_bar"><div class="bar">0%</div></div>
			<br>
			<div id="modules_reload">
				{$start_html}
			</div>
		</div>
	</div>
</div>
<div class="clr"></div>
{include file="footer.tpl"}
