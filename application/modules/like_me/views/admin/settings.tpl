{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
    <div class="edit-form n200">
        <div class="row header">{l i='admin_header_settings' gid='like_me'}</div>
        <div class="row zebra">
            <div class="h">{l i='field_matches_per_page' gid='like_me'}: </div>
            <div class="v"><input type="text" value="{$data.matches_per_page}" name="matches_per_page" class="short"></div>
        </div>
        <div class="row">
            <div class="h">{l i='field_play_local_used' gid='like_me'}: </div>
            <div class="v"><input id="play_local_used" type="checkbox" value="1" name="play_local_used" {if $data.play_local_used}checked{/if}/></div>
        </div>
        <div id="play_local_area" class="row">
            <div class="h">{l i='field_play_local_area' gid='like_me'}: </div>
            <div class="v">
                <div>
                    <div><input type="radio" value="id_country" name="play_local_area" {if $data.play_local_area eq 'id_country'}checked{/if}/>&nbsp;|&nbsp;{l i='field_play_local_country' gid='like_me'}</div>
                    <div><input type="radio" value="id_region" name="play_local_area" {if $data.play_local_area eq 'id_region'}checked{/if}/>&nbsp;|&nbsp;{l i='field_play_local_region' gid='like_me'}</div>
                   <div><input type="radio" value="id_city" name="play_local_area" {if $data.play_local_area eq 'id_city'}checked{/if}/>&nbsp;|&nbsp;{l i='field_play_local_city' gid='like_me'}</div>
                </div>
            </div>
        </div>
        <div class="row zebra">
            <div class="h">{l i='field_play_more' gid='like_me'}: </div>
            <div class="v">
                <div>
                    <div><input type="checkbox" value="1" name="play_more[watch_again]" {if $data.play_more.watch_again}checked{/if}/>&nbsp;|&nbsp;{l i='field_play_more_watch_again' gid='like_me'}</div>
                    <div><input type="checkbox" value="1" name="play_more[search]" {if $data.play_more.search}checked{/if}/>&nbsp;|&nbsp;{l i='field_play_more_search' gid='like_me'}</div>
                    <div><input type="checkbox" value="1" name="play_more[perfect]" {if $data.play_more.perfect}checked{/if}/>&nbsp;|&nbsp;{l i='field_play_more_perfect' gid='like_me'}</div>
                </div>
            </div>
        </div>
        <div class="row zebra">
            <div class="h">{l i='field_chat_more' gid='like_me'}:</div>
            <div class="v">
				<select name="chat_more" class="long">
					{foreach item='item' key='key' from=$data.chat_more}
						<option value="{$key}" {if $item.selected}selected{/if}>{$item.name}</option>
					{/foreach}
				</select>
			</div>
        </div>
        <div class="row">
            <div class="h">{l i='field_chat_message' gid='like_me'}:</div>
            <div class="v">
                <div>
                    {foreach item='lang_item' key='lang_id' from=$data.chat_message}
                        {if $lang_id eq $current_lang_id}
                            <textarea name="chat_message[{$lang_id}]" rows="5" cols="80" class="long pb2" lang-editor="value" lang-editor-type="data-chat_message" lang-editor-lid="{$lang_id}">{$lang_item|escape}</textarea>
                        {else}
                            <input type="hidden" name="chat_message[{$lang_id}]" value="{$lang_item|escape}" lang-editor="value" lang-editor-type="data-chat_message" lang-editor-lid="{$lang_id}" />
                        {/if}
                    {/foreach}
                    <a href="#" lang-editor="button" lang-editor-type="data-chat_message"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16"></a>
		</div>
            </div>
        </div>
		{*
        <div class="row zebra">
            <div class="h">{l i='field_bonus_count' gid='like_me'}: </div>
            <div class="v"><input type="text" value="{$data.bonus_count}" name="bonus_count" class="short"></div>
        </div>
        <div class="row">
            <div class="h">{l i='field_bonus_likes' gid='like_me'}: </div>
            <div class="v"><input type="text" value="{$data.bonus_likes}" name="bonus_likes" class="short"></div>
        </div>
        <div class="row zebra">
            <div class="h">{l i='field_bonus_type' gid='like_me'}: </div>
            <div class="v">
                <div>
                    <div><input type="radio" value="likes" name="bonus_type" {if $data.bonus_type eq 'likes'}checked{/if}/>&nbsp;|&nbsp;{l i='field_bonus_type_likes' gid='like_me'}</div>
                    <div><input type="radio" value="views" name="bonus_type" {if $data.bonus_type eq 'views'}checked{/if}/>&nbsp;|&nbsp;{l i='field_bonus_type_views' gid='like_me'}</div>
                </div>
            </div>
        </div>*}
    </div>
    <div class="btn">
        <div class="l">
            <input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}">
        </div>
    </div>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	var status = $('#play_local_used').prop('checked');
	if(status){
		$('#play_local_area').show();
	}else{
		$('#play_local_area').hide();
	}
	$('#play_local_used').click(function(){
		$('#play_local_area').toggle();
	});
});
{/literal}</script>
{block name='lang_inline_editor' module='start' textarea='1'}
{include file="footer.tpl"}