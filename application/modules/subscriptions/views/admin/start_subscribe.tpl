<div class="load_content">
    <b>{l i='total_users' gid='subscriptions'}:{$total_users}</b><br>
    {l i='sended' gid='subscriptions'}:<span id="count_letters">0</span>
</div>

<script type="text/javascript">
        var total_users = '{$total_users}';
        var limit = 1;
        var page = 1;
        var start_url = '{$site_url}admin/subscriptions/ajax_send_subscription/{$id_subscription}/';
 {literal}
        function start_send(url){
                $.ajax({
                    url: url, 
                    type: 'GET',
                    cache: false,
                    success: function(data){
                            data = eval('(' + data + ')');
                            count_letters = $('#count_letters').text();
                            $('#count_letters').text(parseInt(count_letters) + parseInt(data.sended));
                            if (data.have_to_send == 1){
                                    page = parseInt(page + 1);
                                    start_send(start_url + page + '/' + limit);
                            }
                    }
            });
        };
        if (total_users > 0){
            start_send(start_url + page + '/' + limit);
        }
 
 {/literal}
 </script>