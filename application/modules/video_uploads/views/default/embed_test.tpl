{include file="header.tpl"}
<div class="lc">

</div>
<div class="rc">
	<div class="content-block">
		<h1>Тест Embed code</h1>
		<div class="content-value">

			<div class="edit_block">

				<form action="" method="post">
				<div class="r">
					<div class="f">Enable services: </div>
					<div class="v">{foreach item=item key=key from=$services}{if $key}, {/if}{$item}{/foreach}</div>
				</div>
				<div class="r">
					<div class="f">Embed code: </div>
					<div class="v"><textarea name="embed">{$data.embed}</textarea></div>
				</div>
				<br>
		
				<div class="r">
					<div class="l"><input type="submit" class='btn' value="{l i='btn_send' gid='start' type='button'}" name="btn_save"></div>
					<div class="b">&nbsp;</div>
				</div>
				</form>
				
				{if $video_data}
				<div class="clr"></div>
				<br>
				<h3>Полученные данные</h3>
				{foreach item=item key=key from=$video_data}
					<div class="r">
						<div class="f">{$key}: </div>
						<div class="v">{$item}</div>
					</div>
				{/foreach}
				{if $embed}
				<div class="r">
					<div class="f">Returned embed: </div>
					<div class="v">{$embed|escape}</div>
				</div>
				<div class="r">{$embed}</div>
				{/if}
				{/if}
			</div>
			<div class="clr"></div>
		</div>
	</div>
</div>
<div class="clr"></div>
{include file="footer.tpl"}