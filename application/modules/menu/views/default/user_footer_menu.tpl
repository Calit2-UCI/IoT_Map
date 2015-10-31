<div> <a href="http://uci.edu/" target="_blank"> <img src="{$site_root}application\modules\menu\views\default\seal-blue.jpg" alt="UCI Seal LOGO" !--style="width:165px;height:63px;"-- align="right" style="border:1px"> </a> </div>

<!--big: http://bme240.eng.uci.edu/students/09s/ysantoro/Images/UCIrvine.gif-->
<!--median: http://www.calit2.net/images/articles/UCILogo.jpg-->
<!--small: https://upload.wikimedia.org/wikipedia/en/archive/a/ad/20150908000018%21Ucirvine_logo.png-->
<!--seal: https://communications.uci.edu/img/graphic-identity/seal/seal-blue.png-->
<ul>
{foreach key=key item=item from=$menu}
<li{if $key > 0}{if $key is div by 4} class="clr"{/if}{/if}>
	{$item.value}
	{if !empty($item.sub)}
	<ul>
	{foreach item=subitem from=$item.sub}<li><a id="footer_{$item.gid}_{$subitem.gid}" href="{$subitem.link}">{$subitem.value}</a></li>
	{/foreach}	
	</ul>
	{/if}
	
</li>
{/foreach}
</ul>
