{include file="header.tpl" header_type='index'}
<div class="lc">

</div>
<div class="rc">

	<div class="content-block">
		<h1 class="inl">File Upload Test</h1>
	
		<div class="content-value">
		
			<div class="edit_block">
		
			<form action="" method="post" enctype="multipart/form-data">
			<div class="r">
				<div class="f">Config: </div>
				<div class="v">
					<select name="config">
					<option value="">...</option>
					{foreach item=item from=$configs}<option value="{$item.gid}">{$item.name}</option>{/foreach}					
					</select>				
				</div>
			</div>
		
			<div class="r">
				<div class="f">File: </div>
				<div class="v">
					<input type="file" name="file">				
				</div>
			</div>
		
			<div class="r">
				<div class="l"><input type="submit" class='btn' value="Upload" name="btn_save"></div>
				<div class="b">&nbsp;</div>
			</div>
			</form>
		
			</div>
		</div>
	</div>
		
	
	<br><br><br><br>
	<br><br><br><br>
	<br><br><br><br>
	<br><br><br><br>
</div>
<div class="clr"></div>
{include file="footer.tpl"}