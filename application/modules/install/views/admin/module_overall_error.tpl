<h3>The following modules are required to continue the installation: </h3>
<div>
{foreach item=item from=$not_exist_modules}
<b>{$item}</b><br>
{/foreach}
</div>
<br><br>
<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('overall_product');" name="save_install_login" value="Refresh"></div></div>
<div class="clr"></div>
