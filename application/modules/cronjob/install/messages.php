<?php

$cron_file = "/path/to/php -f ".SITE_PATH."cl_index.php /cronjob/";
$install_messages[] = "This cron file should be set up on your server:<br><b>".$cron_file."</b><br>Cron periodicity: every <b>5 min (*/5 * * * *)</b><br>Important: do not delete blank spaces in the cron path!";

$deinstall_messages[] = "Please dont foget to delete cron file <b>".$cron_file."</b>";
