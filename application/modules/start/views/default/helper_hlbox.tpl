{strip}
<div class="hlBox" id="{$hlb_id}_box" data-multiselect="{$hlb_multiselect}" data-defaults={json_encode data=$hlb_selected} data-input="{$hlb_input}">
	<div class="data">
		<div id="{$hlb_id}_inputs"></div>
		<ul>
			{foreach item=item key=key from=$hlb_value}
				<li data-value="{$key}">{$item}</li>
			{/foreach}
		</ul>
	</div>
</div>
{/strip}
<script>hlboxes.push('{$hlb_id}');</script>