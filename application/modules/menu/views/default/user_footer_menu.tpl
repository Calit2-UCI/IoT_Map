<ul>
{foreach key=key item=item from=$menu}
<li{if $key > 0}{if $key is div by 4} class="clr"{/if}{/if}>
	{$item.value}
	{if !empty($item.sub)}
	<ul>
	{foreach item=subitem from=$item.sub}<li><a id="footer_{$item.gid}_{$subitem.gid}" href="{$subitem.link}">{$subitem.value}</a></li>{/foreach}	
	</ul>
	{/if}
</li>
{/foreach}
<div> <a href="http://uci.edu/"> <img src="http://bme240.eng.uci.edu/students/09s/ysantoro/Images/UCIrvine.gif" align="right" > </a> </div>
</ul>
