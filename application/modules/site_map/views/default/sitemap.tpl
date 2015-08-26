{include file="header.tpl"}
{literal}
<script type="text/javascript">
	function equalHeight(group) {
		tallest = 0;
		group.each(function() {
			thisHeight = $(this).height();
			if(thisHeight > tallest) {
				tallest = thisHeight;
			}
		});
		group.height(tallest);
	}
</script>
{/literal}
<div class="sitemap">
	<h1 class="inl">{seotag tag='header_text'}</h1>
	{assign var="line" value=1 }
	{foreach item=item key=key from=$blocks}
		{literal}
		<script type="text/javascript">
			$(function(){
				equalHeight($(".line{/literal}{$line}{literal}"));
			});
		</script>
		{/literal}
		{if $key is div by 4}<div class="clr"></div>{if $key}{math equation="x + 1" x=$line assign="line"}<div class="horizontal_line"></div>{/if}{/if}
		{math equation="x + 1" x=$key assign="counter"}
		<div class="line{$line} block {if !($counter is div by 4)}right_border{/if}">{include file="sitemap_level.tpl"  module="site_map" list=$item}</div>
	{/foreach}
</div>
<div class="clr"></div>
{include file="footer.tpl"}