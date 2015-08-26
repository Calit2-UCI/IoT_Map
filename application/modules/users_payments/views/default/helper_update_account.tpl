<div class="content-block">
	<div class="edit_block">
                {if $billing_systems}
                <form action="{$site_url}users_payments/save_payment/" method="post" role="form" id="payment_form">
                        <div class="r ptb20">
                                <div class="f"><input type="hidden" value="" name="system_gid" id="system_gid" /></div>
                                <div class="v">
                                {foreach item=item from=$billing_systems}
                                        {if $item.logo_url}
                                                <a href="#" onclick="system_gid_change('{$item.gid}'); return false;" id="system_gid_img"><img src="{$item.logo_url}" title="{$item.name}" alt="{$item.name}" class="mrb10 fltl h100 box-sizing"></a>
                                                {*<input type="image" data-pjax-submit="0" class="mrb10 fltl h100 box-sizing" src="{$item.logo_url}" onclick="$('#system_gid').val('{$item.gid}');" title="{$item.name}" alt="{$item.name}" />*}
                                        {else}
                                                {*<input type="submit" data-pjax-submit="0" class="mrb10 h100 box-sizing" value="{$item.name}" onclick="$('#system_gid').val('{$item.gid}');" />*}
                                        {/if}
                                {/foreach}
                                </div>
                        </div>

                        <div class="r form-group hide" id="operators">
                                <span class="h2">{l i='field_operator' gid='users_payments'}:</span>
                                <div id="operators_block"></div>
                        </div>

                        <div class="r form-group hide" id="amount">
                                <span class="h2">{l i='field_enter_amount' gid='users_payments'}:</span>
                                <div id="amount_block"></div>
                        </div>

                        <div class="r form-group hide" id="details">
                                <label>{l i='field_info_data' gid='payments'}:</label>
                                <div id="details_block"></div>
                        </div>
                    
                        <div class="r hide" id="errors">
                                <i>Empty operators list</i>
                        </div>

                        <button type="submit" name="btn_payment_save" class="btn btn-primary" value="1">
                                {l i='btn_send' gid='start'}
                        </button>
                </form>

                {foreach item=item from=$billing_systems}
                <div id="system_{$item.gid}" class="hide" data-tarifs="{$item.tarifs_type}">
                        <div id="operators_{$item.gid}">
                                <select name="operator" class="form-control middle">
                                        {foreach item=operator_item key=operator_key from=$item.operators_data}
                                        {if $item.tarifs_status[$operator_key]}
                                        <option value="{$item.gid}_{$operator_key}">{$operator_item}</option>
                                        {/if}
                                        {/foreach}
                                        {if $item.tarifs_type eq 2}
                                        <option value="{$item.gid}">{l i='text_tarif_custom' gid='users_payments'}</option>
                                        {/if}
                                </select>
                        </div>
                        {foreach item=operator_item key=operator_key from=$item.operators_data}
                        <div id="amount_{$item.gid}_{$operator_key}">
                                <select name="amount" class="form-control middle">
                                        {foreach item=tarif_item from=$item.tarifs_data[$operator_key]}
                                        <option value="{$tarif_item}">
                                                {block name=currency_format_output module=start 
                                                        value=$tarif_item cur_gid=$base_currency.gid}
                                        </option>
                                        {/foreach}
                                </select>
                        </div>
                        {/foreach}
                        <div id="amount_{$item.gid}">
                                <input type="text" name="amount" class="form-control middle">&nbsp;
                                {block name=currency_output module=start cur_gid=$base_currency.gid}
                        </div>
                        <div id="details_{$item.gid}">{strip}{$item.info_data}{/strip}</div>
                </div>
                {/foreach}
                <script>{literal}
                        function system_gid_change(value) {
                                $('#amount, #operators, #tarifs, #details, #errors').hide();
                                if(value){
                                        $('#system_gid').val(value);
                                        var tarifs_type =  $('#system_'+value).data('tarifs');
                                        if(tarifs_type > 0){
                                                if($('#operators_'+value+' select').html()) {
                                                        var operators = $('#operators_'+value).html();
                                                        var operator = $('#operators').show()
                                                                .find('#operators_block')
                                                                .html(operators)
                                                                .find('select')
                                                                .trigger('change');
                                                        $('#amount .h2').html("{/literal}{l i='field_choose_amount' gid='users_payments'}{literal}");
                                                } else {
                                                        $('#errors').show();
                                                }
                                        }else{
                                                var amount = $('#amount_'+value).html();
                                                $('#amount').show().find('#amount_block').html(amount);
                                        }
                                        var details = $('#details_'+value).html();
                                        if(details.length) $('#details').show().find('#details_block').html(details);
                                }
                        }
                        $(function(){
                                $('#operators').on('change', 'select', function(){
                                        var amount = $('#amount_'+this.value).html();
                                        $('#amount').show().find('#amount_block').html(amount);
                                });
                        });
                {/literal}</script>
                {else}
                        <div class="r">
                                <i>{l i='error_empty_billing_system_list' gid='users_payments'}</i>
                        </div>
                {/if}
        </div>
</div>
