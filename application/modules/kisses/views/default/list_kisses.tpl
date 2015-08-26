<div class="content-block load_content">
	<h1>{l i='kisses_form_title' gid='kisses'}</h1>
	<div class="modal-body scroll inside">
		
		{if $kisses|@count > 0}
			<form id="kisses_form" action="" method="POST" role="form">
				<div>{l i='kisses_annotation' gid='kisses'}</div>
				<div class="scroll_f_kiss">
				<ul>
					{foreach from=$kisses item=kiss}
					{*assign var='name' value='name_'+$lang_id*}
					<li>
						<div class=""><input type="radio" class="" value="{$kiss.id}" id="kiss-{$kiss.id}" name="kiss" /></div>
						<div><label for="kiss-{$kiss.id}"><img src="{$file_url}{$kiss.image|escape}" alt="{$kiss.id}" /></label></div>
					</li>
					{/foreach}
				</ul>
				</div>
				
				<div class="r">
					<div class="v">
						<textarea name="message" id="message" maxlength="{$maxlength}" row="5" cols="50" class="" autocomplete="false"></textarea>
					</div>
				</div>
				<input type="hidden" value="{$object_id}" name="object_id">
				<input type="button" name="btn_send" value="{l i='kiss' gid='kisses' type='button'}" id="btn_send_kisses" class="btn">
						<div id="symbols" class="fright">{$maxlength}</div>
				<!--a href="#" class="load_content_close">{l i='btn_close' gid='kisses'}</a-->
			</form>
		{else}
			{l i='no_kisses' gid='kisses'}
		{/if}
		
	</div>
</div>

<script>{literal}
$(function(){
	$('#kisses_form img').on("click", function(){
		$("#kisses_form img").removeClass("active");
		$(this).addClass("active");
	});
	
	$('label img').on('click', function() {
		$("#" + $(this).parents("label").attr("for")).click();
	});
	
    var maxLength = $('#message').attr('maxlength');
    $('#message').keyup(function()
    {
        var curLength = $('#message').val().length;
        $(this).val($(this).val().substr(0, maxLength));
        var remaning = maxLength - curLength;
        if (remaning < 0) remaning = 0;
        $('#symbols').html(remaning);
        if (remaning < 10)
        {
            $('#symbols').addClass('warning');
        }
        else
        {
            $('#symbols').removeClass('warning');
        }
    });
    
});

{/literal}</script>
