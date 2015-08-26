{include file="header.tpl" load_type='ui'}

	<div class="content-block mb20 mt20">
		<h1>{l i='header_user_register' gid='network'}</h1>
        <p class="header-comment">{l i='text_user_register' gid='network'}</p>
        
        <form method="post">
            <input type="hidden" name="redirect" value="{$redirect|escape}">
            <input type="submit" name="btn_agree" value="{l i='btn_agree' gid='network'}">
            <input type="submit" name="btn_not_agree" value="{l i='btn_not_agree' gid='network'}" class="bg-grey">
            <input type="submit" name="btn_skip" value="{l i='btn_skip' gid='network'}" class="bg-grey">
        </form>
	</div>	
			
{include file="footer.tpl"}
