<?php
// config/pusher_helper.php
require_once __DIR__ . '/../vendor/autoload.php';

function getPusherInstance() {
    $options = array(
        'cluster' => getenv('PUSHER_CLUSTER') ?: 'ap1',
        'useTLS' => true
    );
    return new Pusher\Pusher(
        getenv('PUSHER_KEY'),
        getenv('PUSHER_SECRET'),
        getenv('PUSHER_APP_ID'),
        $options
    );
}
?>