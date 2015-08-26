<?php

$rr = file_get_contents('http://qrcoder.ru/code/?'."www.pilotgroup.net.".'&6&0');

echo '<pre>';
print_r($rr);
echo '</pre>';
exit;

//file_put_contents("filename.jpg",file_get_contents('http://qrcoder.ru/code/?'.$link.'&6&0'));
