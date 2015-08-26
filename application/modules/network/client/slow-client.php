<?php

define('NET_CLIENT_PATH', __DIR__);

require __DIR__ . '/libs/local.php';
include_once NET_CLIENT_PATH . '/libs/loader.php';

if (!empty($argv) && !empty($argv[1])) {
	$action = $argv[1];
} else {
	// TODO: Убрать
	$action = filter_input(INPUT_GET, 'a');
}
if (!$action) {
	return false;
}
// TODO: Переделать в демона.

$local = new Local();
$conn_data = $local->get_connection_data();
$loader = new Loader($local);

$loader->load_api($conn_data['key'], $conn_data['domain']);
$return = $loader->action($action);

if (!empty($return['log'])) {
	echo 'Action: ' . $action . '(' . date('y-m-d H:i') . ')' . "\n";
	echo json_encode($return['log']) . "\n";
}
