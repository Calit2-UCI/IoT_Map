<?php

require __DIR__ . '/libs/local.php';
require __DIR__ . '/libs/daemon.php';
require __DIR__ . '/libs/ElephantIO/Client.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// TODO: Придумать, где брать путь
$logs_dir = __DIR__ . '/../../../../temp/logs/network/';

try {
	$daemon = new Daemon($logs_dir);
	$pid = $daemon->run();
	// TODO: Добавить периодическую проверку pid-файла на всякий случай.
} catch (Exception $e) {
	exit($e->getMessage());
}

$local = new Local();

$events_dir = $local->get_events_dir();
if(!is_dir($events_dir)) {
	mkdir($events_dir, 0777, true);
}

$local->save_daemon_pid($pid);
$local->log('info', 'Daemon started with pid "' . $pid . '"', 'fast');
$conn_data = $local->get_connection_data();

// TODO: Реакция на отключение сервера.
$elephant = new ElephantIO\Client(
		$conn_data['url'], // Server url
		'socket.io', // Socket.io path
		2, // Protocol
		true, // Read
		true, // Check ssl peer
		$local->is_debug(), // Debug
		$conn_data['namespace'], // Namespace
		'127.0.0.1', // Command address
		10000, // Command port
		'file', // Command type
		$events_dir // Command dir
);
$elephant->setLogger($local);
$elephant->setLicence($conn_data['key'], $conn_data['domain']);

foreach ($local->get_events() as $event) {
	$elephant->on($event, function($data) use ($event, $local) {
		$local->log('info', 'Event received: ' . $event . '(' . serialize($data) . ')', 'fast');
		$local->handle($event, $data);
	});
}

pcntl_signal(15, array($elephant, 'close'));

$elephant->init(true, true, true, 10000);
