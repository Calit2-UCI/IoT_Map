<div class="view-user">
	{*<div class="">
		<img src="{$data.user.user_logo}" alt="" title="" />
	</div>*}
	<div>
		<h4>
			<span>{if $data.user.is_guest && $data.user_name}{$data.user_name}{else}{$data.user.output_name}{/if}</span>
		</h4>
		<div>{$data.text|nl2br}</div>
	</div>
</div>