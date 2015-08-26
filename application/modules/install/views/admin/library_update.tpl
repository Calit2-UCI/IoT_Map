{include file="header.tpl"}
{literal}
<script>
var update;
$(function(){
	update=new librariesUpdate({
		siteUrl: '{/literal}{$site_root}{literal}',
		library_gid: '{/literal}{$library.gid}{literal}',
		library_version: '{/literal}{$library.update.version}{literal}',
		library_update_url: '{/literal}{$library.update.file}{literal}',
		library_archive_file: '{/literal}{$library.update.file_name}{literal}'
	});
});
</script>
{/literal}

<form method="post" action="{$data.action}">
	<div class="filter-form">
		<h3>Library update</h3>

		<div class="form">
			<div class="row">
				<div class="h"><b>Available library:</b></div>
				<div class="v">{$library.gid} V{$library.version}.00 ({$library.name})</div>
			</div>
			<div class="row">
				<div class="h"><b>Update:</b></div>
				<div class="v">V{$library.update.version}.00 (<a href="{$library.update.file}" target="_blank">{$library.update.file}</a>)</div>
			</div>
			<br><br>

			<div class="row hided" id="get_block">
				<div class="h">Download library archive:</div>
				<div class="v action-block">-</div>
			</div>
			<div class="row hided" id="unpack_block">
				<div class="h">Unpack archive:</div>
				<div class="v action-block">-</div>
			</div>
			<div class="row hided" id="copy_block">
				<div class="h">Copy files:</div>
				<div class="v action-block">-</div>
			</div>
		</div>
	</div>
	<div class="btn fright"><div class="l"><a href="{$site_url}admin/install/libraries">Back</a></div></div>
</form>
<div class="clr">
{include file="footer.tpl"}
