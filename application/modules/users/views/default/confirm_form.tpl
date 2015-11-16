{include file="header.tpl"}
<div class="content">
<div class="content-block">

<h1>{seotag tag='header_text'}</h1>

<div class="content-value">
<p>{l i='text_confirm' gid='users'}</p>

<div class="edit_block">
<form action="" method="post" >
<div class="r">
	<div class="f">{l i='field_confirm_code' gid='users'}: </div>
	<div class="v"><input type="text" name="code" value=""></div>
</div>
<div class="r">
	<div class="f">&nbsp;</div>
	<div class="v"><input type="submit" class='btn' value="{l i='btn_ok' gid='start'  type='button'}" name="btn_save"></div>
</div>
</form>

</div>
</div>
</div>
<div class="clr"></div>
</div>
{include file="footer.tpl"}
